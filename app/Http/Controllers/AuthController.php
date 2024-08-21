<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function index()
    {
        if (
            (session()->has('zoho_access_token') && Http::withToken(session()->get('zoho_access_token'))
                ->get('https://www.zohoapis.eu/crm/v2/Accounts?per_page=1')->successful())
        ) {
            // echo 'coool';
        } else {
            $refreshToken = env('ZOHO_REFRESH_TOKEN');

            $response = Http::asForm()->post('https://accounts.zoho.eu/oauth/v2/token', [
                'refresh_token' => $refreshToken,
                'client_id' => env('ZOHO_CLIENT_ID'),
                'client_secret' => env('ZOHO_CLIENT_SECRET'),
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                session(['zoho_access_token' => $data['access_token']]);
            }
        }

        // dd(session()->get('zoho_access_token'));
        // dd(session()->all());
        return view('welcome');
    }
}
