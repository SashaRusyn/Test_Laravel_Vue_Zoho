<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    public function store(Request $request)
    {
        $accessToken = session('zoho_access_token');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://www.zohoapis.eu/crm/v2/Deals', [
                    'data' => [
                        [
                            'Deal_Name' => $request->input('name'),
                            'Stage' => $request->input('stage'),
                            'Closing_Date' => $request->input('closingDate'),
                            'Account_Name' => $request->input('accountName')
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
