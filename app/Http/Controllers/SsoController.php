<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        if ($response['success'] && $response['data'] != null) {
            $responseData = $response['data'];
            if (isset($responseData['nim'])) {
                $save['nama'] = $responseData['nama'];
                $save['jenis_kelamin'] = $responseData['jenis_kelamin'];
                $save['tempat_lahir'] = $responseData['tempat_lahir'] ?? null;
                $save['tanggal_lahir'] = $responseData['tanggal_lahir'] ?? null;
                $save['nim'] = $responseData['nim'];
                $save['prodi_id'] = $responseData['id_prodi'];
                $save['fakultas_id'] = $responseData['id_fakultas'];
                $save['email'] = $responseData['email'];
                Mahasiswa::updateOrCreate(['nim' =>  $responseData['nim']], $save);
                User::updateOrCreate(
                    ['identifier' => $responseData['nim']],
                    [
                        'nama' => $responseData['nama'],
                        'type' => 'mahasiswa',
                        'reference_id' => $responseData['nim'],
                        'password' => Hash::make($responseData['nim']),
                    ]
                );

                if (Auth::attempt(['identifier' => $responseData['nim'], 'password' => $responseData['nim']])) {
                    $callbackUrl = str_replace("https://sso.unuja.ac.id", "http://sso.unuja.ac.id:8080", $responseData['callback_session']);
                    $logoutUrl = str_replace("https://sso.unuja.ac.id", "http://sso.unuja.ac.id:8080", $responseData['logout_session']);

                    $phpSessionId = $request->session()->getId();

                    $data = [
                        "logout" => "http://sipermata.unuja.ac.id:8080/sso/logout/" . $phpSessionId,
                    ];
                    $this->makeCurlRequest($callbackUrl, $access_token, $xToken, $UserAgent, $data);
                    $request->session()->put('logout_session', $logoutUrl);
                    return redirect()->route('mahasiswa.dashboard');
                } else {
                    return "Coba Kembali, Akses Anda Gagal";
                }
            } else if (isset($responseData['id_penduduk'])) {

                $user = User::where('identifier', $responseData['id_penduduk'])->first();

                if (!$user) {
                    abort(401, 'User belum terdaftar / tidak punya akses.');
                }

                Auth::login($user, $request->boolean('remember'));

                $callbackUrl = str_replace("https://sso.unuja.ac.id", "http://sso.unuja.ac.id:8080", $responseData['callback_session']);
                $logoutUrl   = str_replace("https://sso.unuja.ac.id", "http://sso.unuja.ac.id:8080", $responseData['logout_session']);

                $phpSessionId = $request->session()->getId();

                $data = [
                    "logout" => "http://sipermata.unuja.ac.id:8080/sso/logout/" . $phpSessionId,
                ];

                $this->makeCurlRequest($callbackUrl, $access_token, $xToken, $UserAgent, $data);
                $request->session()->put('logout_session', $logoutUrl);

                return match ($user->role) {
                    'BAK'   => redirect()->route('bak.dashboard'),
                    'DEKAN' => redirect()->route('dekan.dashboard'),
                    'admin' => redirect()->route('admin.dashboard'),
                    default => redirect('/login'),
                };
            } else {
                return response()->json(['error' => 'Invalid user data'], 400);
            }
        } else {
            return $response;
        }
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
