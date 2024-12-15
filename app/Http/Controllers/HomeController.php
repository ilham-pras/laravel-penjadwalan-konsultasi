<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $events = Booking::orderBy('start_date', 'asc')->get();
        } else {
            $events = Booking::where('user_id', auth()->id())->orderBy('start_date', 'asc')->get();
        }

        return view('home', compact('events'));
    }

    public function zoomEmail()
    {
        return view('emails.zoom-invitation');
    }
}
