<?php

namespace App\Services;

use App\Services\SSOAuth;
use Illuminate\Support\Facades\Http;

class SSOClient
{
    public function __construct(
        protected SSOAuth $auth
    ) {}

    public function getFakultasFromApi(): array
    {
        $auth = $this->auth->getAuth();

        $url     = $auth['data_url'];
        $headers = $auth['headers'];

        $payload = [
            'filter' => 'fakultas',
        ];

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->connectTimeout(10)
            ->post($url, $payload);

        if ($response->status() === 401) {
            $auth    = $this->auth->refreshAuth();
            $url     = $auth['data_url'];
            $headers = $auth['headers'];

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->connectTimeout(10)
                ->post($url, $payload);
        }

        $response->throw();

        return $response->json('data.data') ?? [];
    }

    public function getProdiByFakultas($idFakultas): array
    {
        $auth = $this->auth->getAuth();

        $url     = $auth['data_url'];
        $headers = $auth['headers'];

        $payload = [
            'filter'      => 'program_studi',
            'id_fakultas' => $idFakultas,
        ];

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->connectTimeout(10)
            ->post($url, $payload);

        if ($response->status() === 401) {
            $auth    = $this->auth->refreshAuth();
            $url     = $auth['data_url'];
            $headers = $auth['headers'];

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->connectTimeout(10)
                ->post($url, $payload);
        }

        $response->throw();

        return $response->json('data.data') ?? [];
    }

    public function getMahasiswa(): array
    {
        $auth    = $this->auth->getAuth();
        $url     = $auth['data_url'];
        $headers = $auth['headers'];

        $payload = [
            'filter'     => 'mahasiswa',
            'pagination' => 'off'
        ];

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->connectTimeout(10)
            ->post($url, $payload);

        if ($response->status() === 401) {
            $auth    = $this->auth->refreshAuth();
            $url     = $auth['data_url'];
            $headers = $auth['headers'];

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->connectTimeout(10)
                ->post($url, $payload);
        }

        $response->throw();

        $root = $response->json('data') ?? [];

        if (is_array($root) && isset($root['data']) && is_array($root['data'])) {
            return $root['data'];
        }

        return is_array($root) ? $root : [];
    }

    public function getKaryawanByLembaga(int $idLembaga): array
    {
        $auth    = $this->auth->getAuth();
        $url     = $auth['data_url'];
        $headers = $auth['headers'];

        $payload = [
            'filter'      => 'karyawan',
            'id_lembaga'  => $idLembaga,
            'pagination'  => 'off',
        ];

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->connectTimeout(10)
            ->post($url, $payload);

        if ($response->status() === 401) {
            $auth    = $this->auth->refreshAuth();
            $url     = $auth['data_url'];
            $headers = $auth['headers'];

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->connectTimeout(10)
                ->post($url, $payload);
        }

        $response->throw();

        $data = $response->json('data') ?? [];

        if (is_array($data) && isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        }

        return is_array($data) ? $data : [];
    }
}
