<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function create()
    {
        return view('profile.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|string|max:15',
        ]);

        $event = Profile::create([
            'perusahaan' => $request->input('perusahaan'),
            'alamat' => $request->input('alamat'),
            'no_telp' => $request->input('no_telp'),
        ]);

        return redirect()->route('login');
    }
}
