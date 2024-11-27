<?php

namespace App\Http\Controllers;

use App\Models\DurasiKonsultasi;
use Illuminate\Http\Request;

class DurasiKonsultasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenisKonsultasi = DurasiKonsultasi::all();

        foreach ($jenisKonsultasi as $jenis) {
            $jam = floor($jenis->durasi / 60);
            $menit = $jenis->durasi % 60;
            if ($jam == 0) {
                $jenis->formatted_durasi = "{$menit} Menit";
            } else {
                $jenis->formatted_durasi = $menit == 0 ? "{$jam} Jam" : "{$jam} Jam {$menit} Menit";
            }
        }

        return view('data-penjadwalan.jenis-konsultasi.index', compact('jenisKonsultasi'));
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
            'konsultasi' => 'required',
            'durasi_jam' => 'required|integer|min:0',
            'durasi_menit' => 'required|integer|min:0|max:59',
        ]);

        $totalDurasi = ($request->durasi_jam * 60) + $request->durasi_menit;

        DurasiKonsultasi::create([
            'konsultasi' => $request->konsultasi,
            'durasi' => $totalDurasi,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis Konsultasi berhasil ditambahkan.');
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
            'konsultasi' => 'required',
            'durasi_jam' => 'required|integer|min:0',
            'durasi_menit' => 'required|integer|min:0|max:59',
        ]);

        $totalDurasi = ($request->durasi_jam * 60) + $request->durasi_menit;

        $jenis = DurasiKonsultasi::findOrFail($id);
        $jenis->update([
            'konsultasi' => $request->konsultasi,
            'durasi' => $totalDurasi,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis Konsultasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenis = DurasiKonsultasi::findOrFail($id);
        $jenis->delete();

        return redirect()->route('jenis.index')->with('success', 'Jenis Konsultasi berhasil dihapus.');
    }
}
