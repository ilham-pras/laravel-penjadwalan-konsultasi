<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userProfile = Profile::where('user_id', auth()->id())->first();

        return view('profile.index', compact('userProfile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenisKelaminOptions = Profile::select('jenis_kelamin')->distinct()->pluck('jenis_kelamin');

        return view('profile.create', compact('jenisKelaminOptions'));
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
            'perusahaan' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        Profile::create([
            'user_id' => auth()->user()->id,
            'perusahaan' => $request->input('perusahaan'),
            'alamat' => $request->input('alamat'),
            'no_telp' => $request->input('no_telp'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
        ]);

        return redirect()->route('login')->with('success', 'Profil berhasil dibuat.');
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
            'nama_lengkap' => 'required',
            'perusahaan' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $userId = auth()->user()->id;
        $profile = Profile::where('user_id', $userId)->first();
        if (!$profile) {
            return redirect()->route('profile.index')->with('error', 'Profil tidak ditemukan.');
        }

        $profile->update([
            'perusahaan' => $request->perusahaan,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        $user = $profile->user;
        $user->name = $request->nama_lengkap;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
