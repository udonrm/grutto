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
        $queryParams['count'] = 5;
        $queryParams['start'] = ($request->input('page', 1) - 1) * $queryParams['count'] + 1;

        $response = Http::get('https://webservice.recruit.co.jp/hotpepper/gourmet/v1/', $queryParams);

        if ($response->successful()) {
            $responseData = $response->json();
            return response()->json($responseData['results']);
        }

        return response()->json(['error' => 'API request failed'], $response->status());
    }

    public function showRestaurants($id)
    {
        $apiKey = env('HOTPEPPER_API_KEY');
        $response = Http::get('https://webservice.recruit.co.jp/hotpepper/gourmet/v1/', [
            'key' => $apiKey,
            'id' => $id,
            'format' => 'json',
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            $restaurant = $responseData['results']['shop'][0];
            return view('show', compact('restaurant'));
        }

        return response()->json(['error' => 'API request failed'], $response->status());
    }
}
