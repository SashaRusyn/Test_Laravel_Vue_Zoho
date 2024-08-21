<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // dd(session()->get('zoho_access_token'));
        $accessToken = session()->get('zoho_access_token');

        $response = Http::withToken($accessToken)
            ->get('https://www.zohoapis.eu/crm/v2/Accounts');

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Failed to retrieve accounts'], $response->status());
        }
    }

    public function store(Request $request)
    {
        $accessToken = session('zoho_access_token');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://www.zohoapis.eu/crm/v2/Accounts', [
                    'data' => [
                        [
                            'Account_Name' => $request->input('accountName'),
                            'Website' => $request->input('accountWebsite'),
                            'Phone' => $request->input('accountPhone'),
                        ]
                    ]
                ]);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $response->json()['message'] ?? 'Unknown error occurred'
            ]);
        }
    }
}
