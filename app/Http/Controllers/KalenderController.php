<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Booking;
use App\Models\Profile;
use App\Models\ZoomToken;
use Illuminate\Http\Request;
use App\Models\JamOperasional;
use App\Models\DurasiKonsultasi;
use App\Models\GoogleCalendarToken;
use Google\Client as Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
        $event = Booking::all();

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

    private function sendZoomInvitation($event, $joinUrl)
    {
        $userEmail = Auth::user()->email;
        $penerima = Auth::user()->name;
        $topik = $event->jenis_konsultasi;
        $tanggal = $event->start_date->format('d F Y');
        $waktu = $event->start_date->format('H:i') . ' WIB';
        $durasi = $event->durasi_konsultasi ?? 120;

        if ($durasi >= 60) {
            $jam = intdiv($durasi, 60);
            $menit = $durasi % 60;
            $durasi = $menit > 0 ? "{$jam} jam {$menit} menit" : "{$jam} jam";
        } else {
            $durasi = "{$durasi} menit";
        }

        try {
            Mail::send('emails.zoom-invitation', [
                'penerima' => $penerima,
                'topik' => $topik,
                'tanggal' => $tanggal,
                'waktu' => $waktu,
                'durasi' => $durasi,
                'zoomLink' => $joinUrl,
            ], function ($message) use ($userEmail) {
                $message->to($userEmail)
                    ->subject('Zoom Meeting Invitation');
            });

            Log::info('Zoom invitation email sent to ' . $userEmail);
        } catch (\Exception $e) {
            Log::error('Error sending Zoom invitation email: ' . $e->getMessage());
        }
    }


    private function getZoomAccessToken()
    {
        $admin = User::where('role', 'admin')->first();
        $zoomToken = ZoomToken::where('user_id', $admin->id)->first();

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
                ZoomToken::where('user_id', $admin->id)->update([
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
        $admin = User::where('role', 'admin')->first();
        $adminEmail = $admin->email; // Email admin dari database sebagai host
        $userEmail = Auth::user()->email; // Email user yang membuat event sebagai participant

        $adminZoomToken = ZoomToken::where('user_id', $admin->id)->first();

        $accessToken = $this->getZoomAccessToken();
        if (!$accessToken) {
            Log::error('Failed to get Zoom access token.');
            return null; // Gagal mendapatkan token Zoom, tidak dapat membuat meeting
        }

        $durasi = ($event->durasi_konsultasi ?? 120) + 30;

        try {
            $client = new Client();
            $response = $client->post('https://api.zoom.us/v2/users/' . $admin->email . '/meetings', [
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
                        'waiting_room' => true,
                    ],
                ],
            ]);

            $meetingData = json_decode($response->getBody()->getContents(), true);
            if (!isset($meetingData['join_url'])) {
                Log::error('Zoom meeting not created.');
            } else {
                Log::info('Zoom meeting created. Join URL: ' . $meetingData['join_url']);
            }

            // Kirim undangan Zoom ke participant
            $this->sendZoomInvitation($event, $meetingData['join_url']);

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

        try {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date'), 'Asia/Jakarta');
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('end_date'), 'Asia/Jakarta');

            // Simpan ke database lokal
            $event = Booking::create([
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

            // Ambil admin dengan Google Refresh Token
            $admin = User::where('role', 'admin')->first();
            if (!$admin) {
                return response()->json(['error' => 'Admin tidak ditemukan'], 403);
            }

            $googleCalendarToken = GoogleCalendarToken::where('user_id', $admin->id)->first();
            if (!$googleCalendarToken || !$googleCalendarToken->google_refresh_token) {
                return response()->json(['error' => 'Admin tidak memiliki token Google'], 403);
            }

            $refreshToken = $googleCalendarToken->google_refresh_token;
            $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken, $admin->id);
            if (!$accessToken) {
                return response()->json(['error' => 'Gagal memperbarui token Google'], 500);
            }

            // Buat Zoom Meeting
            $zoomMeeting = $this->createZoomMeeting($event);
            if (!$zoomMeeting) {
                return response()->json(['error' => 'Gagal membuat Zoom meeting'], 500);
            }

            // Simpan link Zoom ke database
            $event->update(['zoom_link' => $zoomMeeting]);

            // Konfigurasi Google Calendar API Client
            $client = new Google_Client();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);

            // Buat deskripsi event untuk Google Calendar
            $fullDescription = "Jenis Konsultasi: " . $request->input('jenis_konsultasi') . "\n"
                . $request->input('deskripsi') . "\n\n"
                . "{$admin->name} is inviting you to a scheduled Zoom meeting.\n"
                . "Join Zoom Meeting:\n" . $zoomMeeting . "\n";

            // Buat event di Google Calendar
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
            $event->update(['google_event_id' => $createdEvent->getId()]);

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
        $event = Booking::find($id);

        // Memastikan event ditemukan dan dimiliki oleh pengguna saat ini
        if (!$event || $event->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'jenis_konsultasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'durasi_konsultasi' => 'nullable|integer|min:1',
        ]);

        try {
            $eventData = [];
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date'), 'Asia/Jakarta');
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('end_date'), 'Asia/Jakarta');
                $eventData['start_date'] = $startDateTime;
                $eventData['end_date'] = $endDateTime;
            }

            if ($request->has('jenis_konsultasi')) {
                $eventData['jenis_konsultasi'] = $request->input('jenis_konsultasi');
            }

            if ($request->has('deskripsi')) {
                $eventData['deskripsi'] = $request->input('deskripsi');
            }

            if ($request->has('durasi_konsultasi')) {
                $eventData['durasi_konsultasi'] = $request->input('durasi_konsultasi');
            } else if (isset($startDateTime) && isset($endDateTime)) {
                $eventData['durasi_konsultasi'] = $startDateTime->diffInMinutes($endDateTime);
            }

            // Update di database lokal
            $event->update($eventData);


            // Update di Google Calendar jika Google Event ID tersedia
            if (!empty($event->google_event_id)) {
                $admin = User::where('role', 'admin')->first();
                if (!$admin) {
                    return response()->json(['error' => 'Admin tidak ditemukan'], 403);
                }

                $googleCalendarToken = GoogleCalendarToken::where('user_id', $admin->id)->first();
                if (!$googleCalendarToken || !$googleCalendarToken->google_refresh_token) {
                    return response()->json(['error' => 'Admin tidak memiliki token Google'], 403);
                }

                $refreshToken = $googleCalendarToken->google_refresh_token;
                $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken, $admin->id);

                if (!$accessToken) {
                    return response()->json(['error' => 'Gagal memperbarui token Google'], 500);
                }

                $client = new Google_Client();
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Calendar($client);

                $googleEvent = $service->events->get('primary', $event->google_event_id);

                // Update tanggal di Google Calendar
                if (isset($startDateTime) && isset($endDateTime)) {
                    $googleEvent->setStart(new Google_Service_Calendar_EventDateTime([
                        'dateTime' => $startDateTime->toIso8601String(),
                        'timeZone' => 'Asia/Jakarta',
                    ]));
                    $googleEvent->setEnd(new Google_Service_Calendar_EventDateTime([
                        'dateTime' => $endDateTime->toIso8601String(),
                        'timeZone' => 'Asia/Jakarta',
                    ]));
                }

                // Update deskripsi di Google Calendar jika diperlukan
                if ($request->has('deskripsi') || $request->has('jenis_konsultasi')) {
                    $fullDescription = "Jenis Konsultasi: " . $request->input('jenis_konsultasi', $event->jenis_konsultasi) . "\n"
                        . $request->input('deskripsi', $event->deskripsi) . "\n\n"
                        . "{$admin->name} is inviting you to a scheduled Zoom meeting.\n"
                        . "Join Zoom Meeting:\n" . $event->zoom_link . "\n";
                    $googleEvent->setDescription($fullDescription);
                }

                $service->events->update('primary', $event->google_event_id, $googleEvent);
            }

            return response()->json($event);
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
        $event = Booking::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event tidak ditemukan'], 404);
        }

        try {
            // Cek admin dan token Google
            $admin = User::where('role', 'admin')->first();
            if (!$admin) {
                return response()->json(['error' => 'Admin tidak ditemukan'], 403);
            }

            $googleCalendarToken = GoogleCalendarToken::where('user_id', $admin->id)->first();
            if (!$googleCalendarToken || !$googleCalendarToken->google_refresh_token) {
                return response()->json(['error' => 'Admin tidak memiliki token Google'], 403);
            }

            $refreshToken = $googleCalendarToken->google_refresh_token;
            $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken, $admin->id);

            if (!$accessToken) {
                // Jika token akses Google tidak valid, hapus event dari database saja
                $event->delete();
                return response()->json(['success' => 'Event berhasil dihapus dari database. Token Google tidak valid.']);
            }

            $client = new Google_Client();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);

            if (!empty($event->google_event_id)) {
                try {
                    $service->events->delete('primary', $event->google_event_id);
                } catch (\Exception $ex) {
                    // Jika event pada Google Calendar tidak ditemukan
                    $event->delete();
                    return response()->json(['success' => 'Event berhasil dihapus dari database. Event Google Calendar tidak ditemukan.']);
                }
            }
        } catch (\Exception $ex) {
            // Jika terjadi kesalahan saat menghapus event dari Google Calendar
            $event->delete();
            return response()->json(['success' => 'Event berhasil dihapus dari database. Kesalahan saat menghapus dari Google Calendar.' . $ex->getMessage()]);
        }

        // Hapus event dari database
        $event->delete();
        return response()->json(['success' => 'Event berhasil dihapus dari database dan Google Calendar.']);
    }

    private function generateAccessTokenFromRefreshToken($refreshToken, $userId)
    {
        $googleClientId = config('services.google.client_id');
        $googleClientSecret = config('services.google.client_secret');

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $googleClientId,
                'client_secret' => $googleClientSecret,
                'refresh_token' => $refreshToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Perbarui access token dan refresh token di database
                $newAccessToken = $data['access_token'];
                $newRefreshToken = $data['refresh_token'] ?? $refreshToken;

                GoogleCalendarToken::where('user_id', $userId)->update([
                    'google_access_token' => $newAccessToken,
                    'google_refresh_token' => $newRefreshToken,
                ]);

                return $newAccessToken;
            } else {
                $error = $response->json();
                Log::error('Failed to refresh Google access token', [
                    'error' => $error,
                    'user_id' => $userId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error during token refresh', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
            ]);
        }

        return null; // Kembalikan null jika gagal
    }
}
