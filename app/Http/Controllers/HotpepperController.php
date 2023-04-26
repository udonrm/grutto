<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HotpepperController extends Controller
{
    public function searchRestaurants(Request $request)
    {
        $apiKey = env('HOTPEPPER_API_KEY');
        $queryParams = $request->query();
        $queryParams['key'] = $apiKey;
        $queryParams['format'] = 'json';

        $response = Http::get('https://webservice.recruit.co.jp/hotpepper/gourmet/v1/', $queryParams);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'API request failed'], $response->status());
    }
}
