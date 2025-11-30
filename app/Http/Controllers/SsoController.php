<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    public function sso(Request $request)
    {
        $url = 'http://sso.unuja.ac.id:8080/portal/me/8ZiVo95nM1xUJzhA';
        $access_token = $request->access_token;
        $xToken = 'FLVtfNC5KrTxVHOJ';
        $UserAgent = $request->header('User-Agent');
        if (!$access_token) {
            return response()->json(['error' => 'Access token is missing'], 400);
        }

        $response = $this->makeCurlRequest($url, $access_token, $xToken, $UserAgent);
        return $response;
    }

    public function logout(string $sessionId)
    {
        Auth::logout();
        $sessionPath = config('session.files');
        $sessionFile = $sessionPath . '/' . $sessionId;

        if (file_exists($sessionFile)) {
            unlink($sessionFile);
        }

        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    function makeCurlRequest($url, $authorizationToken, $xToken, $UserAgent, $data = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => array_filter([
                "Authorization: Bearer $authorizationToken",
                "X-Token: $xToken",
                "User-Agent: $UserAgent",
                $data ? "Content-Type: application/json" : null,
            ]),
            CURLOPT_POSTFIELDS => $data ? json_encode($data) : null,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error: " . $err;
        }
        $decodedResponse = json_decode($response, true);

        return $decodedResponse;
    }
}

