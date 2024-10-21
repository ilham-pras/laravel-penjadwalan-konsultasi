<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\JamOperasional;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index()
    {
        $events = [];
        $event = Event::with('user')->get();

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
                'deskripsi' => $datajadwal->deskripsi,
                'google_event_id' => $datajadwal->google_event_id,
                'zoom_link' => $datajadwal->zoom_link,
                'no_telp' => $datajadwal->user->no_telp
            ];
        }

        $jamOperasional = JamOperasional::all();
        return view('konsultasi.index', compact('events', 'jamOperasional'));
    }
}
