<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use App\Models\GoogleCalendarToken;
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
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = auth()->user();
            if ($user) {
                DB::transaction(function () use ($googleUser, $user) {
                    // Cek jika refresh token disediakan
                    $refreshToken = $googleUser->refreshToken ?? null;

                    // Cari atau buat token baru untuk user
                    $googleToken = GoogleCalendarToken::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'google_id' => $googleUser->id,
                            'google_access_token' => $googleUser->token,
                            'google_refresh_token' => $refreshToken,
                        ]
                    );
                });
            }

            session()->flash('alert-success', 'Google account connected successfully!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah
            session()->flash('alert-danger', 'Failed to connected Google account: ' . $e->getMessage());
        }

        return redirect()->to('/home')->with('status', 'Google account connected successfully!');
    }
}
