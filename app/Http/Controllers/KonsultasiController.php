<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\JamOperasional;
use App\Models\DurasiKonsultasi;

class KonsultasiController extends Controller
{
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

        return view('data-penjadwalan.jadwal-konsultasi.index', compact('events', 'jamOperasional', 'profile', 'durasiKonsultasi'));
    }
}
