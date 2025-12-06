<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SSOAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SSOSinkronisasiController extends Controller
{
    public function index(SSOAuth $auth): View
    {
        $cacheKey  = 'sso_auth';
        $authData  = Cache::get($cacheKey);

        $hasAuth   = is_array($authData);

        $xToken        = $hasAuth ? ($authData['headers']['X-Token'] ?? null) : null;
        $dataUrl       = $hasAuth ? ($authData['data_url'] ?? null) : null;
        $createdAt     = $hasAuth ? ($authData['created_at'] ?? null) : null;
        $expiredAt     = $hasAuth ? ($authData['expired_at'] ?? null) : null;

        $remainingMinutes = null;
        $status           = 'none';

        if ($expiredAt) {
            $remainingMinutes = now()->diffInMinutes($expiredAt, false);
            if ($remainingMinutes > 0) {
                $status = 'active';
            } else {
                $status = 'expired';
            }
        } elseif ($hasAuth) {
            $status = 'active';
        }

        $maskedToken = null;
        if ($xToken) {
            $len = strlen($xToken);
            if ($len <= 10) {
                $maskedToken = $xToken;
            } else {
                $maskedToken = substr($xToken, 0, 6) . '••••' . substr($xToken, -4);
            }
        }

        $shortUrl = null;
        if ($dataUrl) {
            $len = strlen($dataUrl);
            if ($len <= 60) {
                $shortUrl = $dataUrl;
            } else {
                $shortUrl = substr($dataUrl, 0, 35) . '...' . substr($dataUrl, -15);
            }
        }

        return view('admin.sso.sinkronisasi', [
            'hasAuth'          => $hasAuth,
            'maskedToken'      => $maskedToken,
            'fullToken'        => $xToken,
            'dataUrl'          => $dataUrl,
            'shortUrl'         => $shortUrl,
            'createdAt'        => $createdAt,
            'expiredAt'        => $expiredAt,
            'remainingMinutes' => $remainingMinutes,
            'status'           => $status,
        ]);
    }

    public function refresh(SSOAuth $auth): RedirectResponse
    {
        try {
            $authData = $auth->refreshAuth();
            return back()->with('success', 'Token SSO berhasil diperbarui. Berlaku sampai: ' . $authData['expired_at']->format('d-m-Y H:i'));
        } catch (\Throwable $e) {
            report($e);
            return back()->with('failed', 'Gagal memperbarui token SIAKAD: ' . $e->getMessage());
        }
    }
}
