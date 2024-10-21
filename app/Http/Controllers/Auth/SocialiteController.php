<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectOnGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar.events'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function openGoogleAccountDetails()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = auth()->user();
        if ($user) {
            // Cek jika Google mengembalikan refresh token
            $refreshToken = $googleUser->refreshToken ?? $user->google_refresh_token;

            $user->update([
                'google_id' => $googleUser->id,
                'google_access_token' => $googleUser->token,
                'google_refresh_token' => $refreshToken,
            ]);
        }

        session()->flash('alert-success', 'Account linked successfully!');
        return redirect()->to('/home');
    }
}
