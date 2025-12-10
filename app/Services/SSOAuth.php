<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SSOAuth
{
    protected string $cacheKey = 'sso_auth';

    private string $authUrl    = 'http://sso.unuja.ac.id:8080/portal/data/authorize';
    private string $idLembaga  = '7';
    private string $idStruktur = '84';

    public function getAuth(): array
    {
        $cached = Cache::get($this->cacheKey);

        if ($cached && isset($cached['data_url'], $cached['headers'], $cached['expired_at'])) {
            if (now()->lessThan($cached['expired_at'])) {
                return $cached;
            }
        }

        return $this->refreshAuth();
    }

    public function refreshAuth(): array
    {
        $payload = [
            'id_lembaga'  => $this->idLembaga,
            'id_struktur' => $this->idStruktur,
        ];

        $response = Http::post($this->authUrl, $payload);

        if (! $response->successful()) {
            throw new \Exception('Gagal authorize ke API (status ' . $response->status() . '): ' . $response->body());
        }

        $json = $response->json();

        if (! is_array($json)) {
            throw new \Exception('Response authorize bukan JSON yang valid.');
        }

        $dataUrl     = data_get($json, 'data.info.urls.data');
        $tokenHeader = data_get($json, 'data.token_header', []);

        if (! $dataUrl || empty($tokenHeader['X-Token'])) {
            throw new \Exception('Data URL atau X-Token tidak ditemukan di response authorize.');
        }

	$newBase = 'http://sso.unuja.ac.id:8080/portal/data/data';

	$path      = parse_url($dataUrl, PHP_URL_PATH);
	$tokenPart = $path ? basename($path) : null;

	if ($tokenPart) {
    		$dataUrl = rtrim($newBase, '/') . '/' . $tokenPart;
	} else {
    		$dataUrl = $newBase;
	}

        $createdAt = now();
        $expiredAt = now()->addHours(6);

        $authData = [
            'data_url'   => $dataUrl,
            'headers'    => $tokenHeader,
            'created_at' => $createdAt,
            'expired_at' => $expiredAt,
        ];

        Cache::put($this->cacheKey, $authData, $expiredAt);

        return $authData;
    }
}
