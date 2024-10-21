<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $events = Event::orderBy('start_date', 'asc')->get();
        } else {
            $events = Event::where('user_id', auth()->id())->orderBy('start_date', 'asc')->get();
        }

        return view('home', compact('events'));
    }
}
