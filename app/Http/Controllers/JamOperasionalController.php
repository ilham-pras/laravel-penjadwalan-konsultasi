<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JamOperasional;
use Illuminate\Support\Facades\DB;

class JamOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil enum dari kolom hari_mulai
        $enumDays = DB::select(DB::raw("SHOW COLUMNS FROM jam_operasionals WHERE Field = 'hari_mulai'"))[0]
            ->Type; // Mengambil tipe kolom

        // Format ulang nilai enum menjadi array
        preg_match('/enum\((.*)\)$/', $enumDays, $matches);
        $enumDays = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        $jamOperasional = JamOperasional::all();
        return view('data-penjadwalan.jam-operasional.index', compact('jamOperasional', 'enumDays'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'hari_mulai' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hari_selesai' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JamOperasional::create([
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'hari_mulai' => $request->input('hari_mulai'),
            'hari_selesai' => $request->input('hari_selesai'),
            'jam_mulai' => $request->input('jam_mulai'),
            'jam_selesai' => $request->input('jam_selesai'),
        ]);

        return redirect()->route('jam.index')->with('success', 'Jam Operasional berhasil ditambahkan.');
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
        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'hari_mulai' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hari_selesai' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jamOperasional = JamOperasional::findOrFail($id);
        $jamOperasional->update([
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'hari_mulai' => $request->input('hari_mulai'),
            'hari_selesai' => $request->input('hari_selesai'),
            'jam_mulai' => $request->input('jam_mulai'),
            'jam_selesai' => $request->input('jam_selesai'),
        ]);

        return redirect()->route('jam.index')->with('success', 'Jam Operasional berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jamOperasional = JamOperasional::findOrFail($id);
        $jamOperasional->delete();

        return redirect()->route('jam.index')->with('success', 'Jam Operasional berhasil dihapus!');
    }
}
