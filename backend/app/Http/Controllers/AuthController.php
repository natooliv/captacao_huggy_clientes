<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class AuthController extends Controller
{

    public function authenticateWithGoogle(Request $request)
    {

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjI2MjJjZjY5ZTk4MWQwZWYyNTNlOWRkOWRjYzJlNmY2ZmQwMTVhNDJiYjJjZDIxOTI0MzQ3YjFlZDNmYTAxMjE5MDM0OTA0NzBkYjNkZjExIn0...';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://script.google.com/macros/s/AKfycbyCWOGS1Zk6u3ts4dv8Ke0W5wj_drJmxk0pdf22mhrXh-jCWq20Jq6L6zhHyKfRR_Vc/exec');

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Erro na chamada ao script'], $response->status());
        }
    }
}
