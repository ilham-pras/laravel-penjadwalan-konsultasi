<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ZoomController extends Controller
{
    public function redirectToZoom()
    {
        $clientId = env('ZOOM_CLIENT_ID');
        $redirectUri = urlencode(env('ZOOM_REDIRECT_URI'));
        $state = base64_encode(Auth::id()); // Simpan user ID sebagai state

        $zoomAuthUrl = "https://zoom.us/oauth/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";

        return redirect($zoomAuthUrl);
    }


    public function handleZoomCallback(Request $request)
    {
        $code = $request->query('code');
        $state = base64_decode($request->query('state'));

        if (!$code) {
            return redirect()->route('home')->with('status', 'Authorization failed. Code not found.');
        }

        $user = Auth::user();
        if ($user->id != $state) {
            return redirect()->route('home')->with('status', 'Invalid state parameter.');
        }

        try {
            // Kirim permintaan token ke Zoom
            $client = new Client();
            $response = $client->post('https://zoom.us/oauth/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => env('ZOOM_REDIRECT_URI'),
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan token ke database
            DB::table('zoom_tokens')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_at' => now()->addSeconds($data['expires_in']),
                ]
            );

            return redirect()->route('home')->with('status', 'Zoom account connected successfully!');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Failed to connect Zoom account: ' . $e->getMessage());
        }
    }

}
