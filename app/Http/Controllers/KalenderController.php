<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use GuzzleHttp\Client;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\JamOperasional;
use App\Models\DurasiKonsultasi;
use Illuminate\Support\Facades\DB;
use Google\Client as Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ZoomController;
use Google\Service\Calendar as Google_Service_Calendar;
use Google\Service\Calendar\Event as Google_Service_Calendar_Event;
use Google\Service\Calendar\EventDateTime as Google_Service_Calendar_EventDateTime;

class KalenderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = [];
        $event = Event::all();

        $profile = Profile::where('user_id', auth()->id())->first();
        $jamOperasional = JamOperasional::all();
        $durasiKonsultasi = DurasiKonsultasi::all();

        foreach ($durasiKonsultasi as $jenis) {
            $jam = floor($jenis->durasi / 60);
            $menit = $jenis->durasi % 60;
            if ($jam == 0) {
                $jenis->formatted_durasi = "{$menit} Menit";
            } else {
                $jenis->formatted_durasi = $menit == 0 ? "{$jam} Jam" : "{$jam} Jam {$menit} Menit";
            }
        }

        foreach ($event as $datajadwal) {
            $events[] = [
                'id' => $datajadwal->id,
                'user_id' => $datajadwal->user_id,
                'start' => $datajadwal->start_date,
                'end' => $datajadwal->end_date,
                'title' => $datajadwal->title,
                'nama_lengkap' => $datajadwal->nama_lengkap,
                'perusahaan' => $datajadwal->perusahaan,
                'jenis_konsultasi' => $datajadwal->jenis_konsultasi,
                'durasi_konsultasi' => $datajadwal->durasi_konsultasi,
                'deskripsi' => $datajadwal->deskripsi,
                'google_event_id' => $datajadwal->google_event_id,
                'zoom_link' => $datajadwal->zoom_link,
            ];
        }

        return view('kalender.index', compact('events', 'jamOperasional', 'profile', 'durasiKonsultasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function getZoomAccessToken()
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login
        $zoomToken = DB::table('zoom_tokens')->where('user_id', $userId)->first();

        if (!$zoomToken || now()->greaterThan($zoomToken->expires_at)) {
            // Token kadaluarsa, gunakan refresh token untuk memperbarui
            try {
                $client = new Client();
                $response = $client->post('https://zoom.us/oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $zoomToken->refresh_token,
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                // Perbarui token di database
                DB::table('zoom_tokens')->where('user_id', $userId)->update([
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_at' => now()->addSeconds($data['expires_in']),
                ]);

                return $data['access_token'];
            } catch (\Exception $e) {
                Log::error('Error refreshing Zoom token: ' . $e->getMessage());
                return null;
            }
        }

        return $zoomToken->access_token; // Token masih valid
    }



    private function createZoomMeeting($event)
    {
        $accessToken = $this->getZoomAccessToken();

        if (!$accessToken) {
            Log::error('Failed to get Zoom access token.');
            return null; // Gagal mendapatkan token Zoom, tidak dapat membuat meeting
        }

        $durasi = ($event->durasi_konsultasi ?? 120) + 30;

        try {
            $client = new Client();
            $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'topic' => $event->nama_lengkap,
                    'type' => 2,  // Scheduled meeting
                    'start_time' => $event->start_date->toIso8601String(),
                    'duration'   => $durasi,
                    'timezone' => 'Asia/Jakarta',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => true,
                        'mute_upon_entry' => true,
                        'waiting_room' => false,
                    ],
                ],
            ]);

            $meetingData = json_decode($response->getBody()->getContents(), true);
            if (!isset($meetingData['join_url'])) {
                throw new \Exception('Zoom meeting not created.');
            }

            return $meetingData['join_url'];
        } catch (\Exception $e) {
            Log::error('Error creating Zoom meeting: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'title' => 'required',
            'nama_lengkap' => 'required',
            'perusahaan' => 'required',
            'jenis_konsultasi' => 'required',
            'durasi_konsultasi' => 'required|integer|min:1',
            'deskripsi' => 'required',
        ]);

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date'), 'Asia/Jakarta');
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('end_date'), 'Asia/Jakarta');

        // Simpan ke database lokal
        $event = Event::create([
            'user_id' => auth()->user()->id,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'title' => $request->title,
            'nama_lengkap' => $request->nama_lengkap,
            'perusahaan' => $request->perusahaan,
            'jenis_konsultasi' => $request->jenis_konsultasi,
            'durasi_konsultasi' => $request->durasi_konsultasi,
            'deskripsi' => $request->deskripsi,
        ]);

        // Simpan ke Google Calendar
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin || !$admin->google_refresh_token) {
                return response()->json(['error' => 'Admin tidak ditemukan atau tidak memiliki token Google'], 403);
            }
            $adminName = $admin->name;

            // Buat Zoom Meeting
            $zoomMeeting = $this->createZoomMeeting($event);
            if ($zoomMeeting) {
                $event->zoom_link = $zoomMeeting;
                $event->save();
            }

            $refreshToken = $admin->google_refresh_token;
            $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

            $client = new Google_Client();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);

            $fullDescription = "Jenis Konsultasi: " . $request->input('jenis_konsultasi') . "\n"
                . $request->input('deskripsi') . "\n\n"
                . "$adminName is inviting you to a scheduled Zoom meeting.\n"
                . "Join Zoom Meeting:\n" . $zoomMeeting . "\n";

            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $request->input('nama_lengkap'),
                'location' => $request->input('perusahaan'),
                'description' => $fullDescription,
                'start' => [
                    'dateTime' => $startDateTime->toIso8601String(), // Format to ISO 8601
                    'timeZone' => 'Asia/Jakarta',
                ],
                'end' => [
                    'dateTime' => $endDateTime->toIso8601String(), // Format to ISO 8601
                    'timeZone' => 'Asia/Jakarta',
                ],
            ]);

            $calendarId = 'primary';
            $createdEvent = $service->events->insert($calendarId, $googleEvent);
            if (!$createdEvent || !$createdEvent->getId()) {
                return response()->json(['error' => 'Gagal membuat event di Google Calendar'], 500);
            }

            // Simpan ID event Google ke database
            $event->google_event_id = $createdEvent->getId();
            $event->save();

            return response()->json($event);
        } catch (\Exception $ex) {
            Log::error('Error creating Google Calendar event: ' . $ex->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $ex->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event || $event->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $eventData = [];
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date'), 'Asia/Jakarta');
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('end_date'), 'Asia/Jakarta');

        // Hanya memperbarui tanggal saja
        if ($request->has('start_date') && $request->has('end_date') && !$request->has('jenis_konsultasi') && !$request->has('deskripsi')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $eventData['start_date'] = $startDateTime;
            $eventData['end_date'] = $endDateTime;
        } else {
            // Memperbarui semua field
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'jenis_konsultasi' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'durasi_konsultasi' => 'nullable|integer|min:1',
            ]);

            // Hitung durasi otomatis jika tidak diberikan
            $duration = $request->input('durasi_konsultasi')
                ?? $startDateTime->diffInMinutes($endDateTime);

            $eventData = [
                'start_date' => $startDateTime,
                'end_date' => $endDateTime,
                'jenis_konsultasi' => $request->jenis_konsultasi,
                'deskripsi' => $request->deskripsi,
                'durasi_konsultasi' => $duration,
            ];
        }

        // Memperbarui di database lokal
        $event->update($eventData);

        try {
            // Memperbarui di Google Calendar
            $admin = User::where('role', 'admin')->first();
            if (!$admin || !$admin->google_refresh_token) {
                return response()->json(['error' => 'Admin tidak ditemukan atau tidak memiliki token Google'], 403);
            }

            $refreshToken = $admin->google_refresh_token;
            $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

            $client = new Google_Client();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);

            if (empty($event->google_event_id)) {
                return response()->json(['error' => 'ID event Google Calendar tidak ditemukan'], 404);
            }
            $googleEvent = $service->events->get('primary', $event->google_event_id);

            // Memperbarui tanggal di Google Calendar
            $googleEvent->setStart(new Google_Service_Calendar_EventDateTime([
                'dateTime' => $startDateTime->toIso8601String(), // Format to ISO 8601
                'timeZone' => 'Asia/Jakarta',
            ]));
            $googleEvent->setEnd(new Google_Service_Calendar_EventDateTime([
                'dateTime' => $endDateTime->toIso8601String(), // Format to ISO 8601
                'timeZone' => 'Asia/Jakarta',
            ]));

            // Memperbarui field lain di Google Calendar jika ada
            if ($request->has('jenis_konsultasi') || $request->has('deskripsi')) {
                if ($request->has('deskripsi')) {
                    $adminName = $admin->name;
                    $fullDescription = "Jenis Konsultasi: " . $request->input('jenis_konsultasi') . "\n"
                        . $request->input('deskripsi') . "\n\n"
                        . "$adminName is inviting you to a scheduled Zoom meeting.\n"
                        . "Join Zoom Meeting:\n" . $event->zoom_link . "\n";
                    $googleEvent->setDescription($fullDescription);
                }
            }

            $service->events->update('primary', $event->google_event_id, $googleEvent);

            return response()->json($eventData);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui event: ' . $ex->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event tidak ditemukan'], 404);
        }

        // Hapus event dari Google Calendar jika ada
        try {
            $admin = User::where('role', 'admin')->first();
            $refreshToken = $admin->google_refresh_token;
            $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

            if (!$accessToken) {
                // Jika token akses Google tidak valid, hapus event dari database saja
                $event->delete();
                return response()->json(['success' => 'Event deleted successfully.']);
            }

            $client = new Google_Client();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);

            if (!empty($event->google_event_id)) {
                try {
                    $service->events->delete('primary', $event->google_event_id);
                } catch (\Exception $ex) {
                    // Jika event pada Google Calendar tidak ada, hapus event dari database saja
                    $event->delete();
                    return response()->json(['success' => 'Event deleted successfully.']);
                }
            }
        } catch (\Exception $ex) {
            // Jika terjadi kesalahan saat menghapus event dari Google Calendar, hapus event dari database saja
            $event->delete();
            return response()->json(['success' => 'Event deleted successfully.']);
        }
        $event->delete();
        return response()->json(['success' => 'Event and associated Zoom meeting deleted successfully.']);
    }

    private function generateAccessTokenFromRefreshToken($refreshToken)
    {
        $newAccessToken = null;
        $googleClientId = config('services.google.client_id');
        $googleClientSecret = config('services.google.client_secret');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $googleClientId,
            'client_secret' => $googleClientSecret,
            'refresh_token' => $refreshToken,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $newAccessToken = $data['access_token'];
            $newRefreshToken = $data['refresh_token'] ?? $refreshToken;
        } else {
            $error = $response->json();
        }
        return $newAccessToken;
    }
}
