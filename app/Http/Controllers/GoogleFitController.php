<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleFitController extends Controller
{
    // ─── OAuth Scopes yang dibutuhkan ─────────────────────────────────
    private const SCOPES = [
        'https://www.googleapis.com/auth/fitness.heart_rate.read',
        'https://www.googleapis.com/auth/fitness.blood_pressure.read',
        'https://www.googleapis.com/auth/fitness.activity.read',
        'https://www.googleapis.com/auth/fitness.body.read',
    ];

    // ─── Step 1: Redirect pasien ke Google OAuth ──────────────────────
    public function connect(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return redirect()->route('patient.lookup');

        $params = http_build_query([
            'client_id'     => config('services.google.client_id'),
            'redirect_uri'  => route('google-fit.callback'),
            'response_type' => 'code',
            'scope'         => implode(' ', self::SCOPES),
            'access_type'   => 'offline',   // agar dapat refresh token
            'prompt'        => 'consent',    // paksa tampil consent agar dapat refresh token
            'state'         => csrf_token(), // CSRF protection
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    // ─── Step 2: Terima authorization code dari Google ────────────────
    public function callback(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return redirect()->route('patient.lookup');

        // Validasi CSRF state
        if ($request->input('state') !== csrf_token()) {
            return redirect()->route('self-input.show')
                ->withErrors(['google_fit' => 'State tidak valid. Silakan coba lagi.']);
        }

        $code = $request->input('code');
        if (!$code) {
            $error = $request->input('error', 'unknown');
            // Jika user batalkan akses → kembali dengan pesan ramah
            if ($error === 'access_denied') {
                return redirect()->route('self-input.show')
                    ->with('info', 'Koneksi ke Google Fit dibatalkan.');
            }
            return redirect()->route('self-input.show')
                ->withErrors(['google_fit' => 'Gagal mendapat izin dari Google: ' . $error]);
        }

        // Tukar authorization code → access token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri'  => route('google-fit.callback'),
            'grant_type'    => 'authorization_code',
        ]);

        if ($tokenResponse->failed()) {
            return redirect()->route('self-input.show')
                ->withErrors(['google_fit' => 'Gagal mendapat access token dari Google.']);
        }

        $tokens = $tokenResponse->json();

        // Simpan token ke database pasien
        $patient = Patient::find($patientId);
        $patient->update([
            'google_fit_access_token'    => $tokens['access_token'],
            'google_fit_refresh_token'   => $tokens['refresh_token'] ?? $patient->google_fit_refresh_token,
            'google_fit_token_expires_at'=> now()->addSeconds($tokens['expires_in'] ?? 3600),
        ]);

        return redirect()->route('self-input.show')
            ->with('success', '✓ Google Fit berhasil dihubungkan! Sekarang kamu bisa ambil data dari Mi Band.');
    }

    // ─── Step 3: Ambil data vital terbaru dari Google Fit API ─────────
    public function fetch(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return response()->json(['error' => 'Unauthorized'], 403);

        $patient = Patient::find($patientId);
        if (!$patient || !$patient->google_fit_access_token) {
            return response()->json([
                'error'       => 'not_connected',
                'message'     => 'Google Fit belum dihubungkan.',
                'connect_url' => route('google-fit.connect'),
            ], 401);
        }

        // Refresh token jika sudah expired
        $accessToken = $this->getValidAccessToken($patient);
        if (!$accessToken) {
            return response()->json([
                'error'       => 'token_expired',
                'message'     => 'Sesi Google Fit kamu expired. Silakan hubungkan ulang.',
                'connect_url' => route('google-fit.connect'),
            ], 401);
        }

        // Ambil data 24 jam terakhir
        $endTimeNs   = now()->timestamp * 1_000_000_000;
        $startTimeNs = now()->subHours(24)->timestamp * 1_000_000_000;

        $result = [
            'heart_rate'        => null,
            'systolic'          => null,
            'diastolic'         => null,
            'oxygen_saturation' => null,
            'weight'            => null,
            'steps'             => null,
            'source'            => 'google_fit',
            'fetched_at'        => now()->toISOString(),
        ];

        // ── Detak Jantung ──────────────────────────────────────────────
        $hrData = $this->fetchDataset(
            $accessToken,
            'derived:com.google.heart_rate.bpm:com.google.android.gms:merge_heart_rate_bpm',
            $startTimeNs,
            $endTimeNs
        );
        if ($hrData && !empty($hrData['point'])) {
            $latest = end($hrData['point']);
            $result['heart_rate'] = (int) round($latest['value'][0]['fpVal'] ?? 0);
        }

        // ── Berat Badan ────────────────────────────────────────────────
        $weightData = $this->fetchDataset(
            $accessToken,
            'derived:com.google.weight:com.google.android.gms:merge_weight',
            $startTimeNs,
            $endTimeNs
        );
        if ($weightData && !empty($weightData['point'])) {
            $latest = end($weightData['point']);
            $result['weight'] = round($latest['value'][0]['fpVal'] ?? 0, 1);
        }

        // ── Langkah Kaki (Opsional) ───────────────────────────────────
        $stepsData = $this->fetchDataset(
            $accessToken,
            'derived:com.google.step_count.delta:com.google.android.gms:estimated_steps',
            $startTimeNs,
            $endTimeNs
        );
        if ($stepsData && !empty($stepsData['point'])) {
            $total = collect($stepsData['point'])
                ->sum(fn($p) => $p['value'][0]['intVal'] ?? 0);
            $result['steps'] = (int) $total;
        }

        // Cek apakah ada data yang berhasil diambil
        $hasData = $result['heart_rate'] || $result['systolic'] || $result['weight'];
        if (!$hasData) {
            return response()->json([
                'error'   => 'no_data',
                'message' => 'Tidak ada data kesehatan dalam 24 jam terakhir di Google Fit. Pastikan Mi Fitness sudah disinkronkan ke Health Connect & Google Fit.',
                'result'  => $result,
            ]);
        }

        return response()->json([
            'ok'     => true,
            'result' => $result,
        ]);
    }

    // ─── Cek apakah Google Fit sudah terhubung ─────────────────────────
    public function status(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return response()->json(['connected' => false]);

        $patient = Patient::find($patientId);
        $connected = $patient && !empty($patient->google_fit_access_token);
        $expired   = $connected && $patient->google_fit_token_expires_at < now();

        return response()->json([
            'connected'   => $connected,
            'expired'     => $expired,
            'connect_url' => route('google-fit.connect'),
        ]);
    }

    // ─── Disconnect (hapus token) ──────────────────────────────────────
    public function disconnect(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return redirect()->route('patient.lookup');

        $patient = Patient::find($patientId);
        if ($patient) {
            $patient->update([
                'google_fit_access_token'    => null,
                'google_fit_refresh_token'   => null,
                'google_fit_token_expires_at'=> null,
            ]);
        }

        return redirect()->route('self-input.show')
            ->with('success', 'Google Fit berhasil diputuskan.');
    }

    // ─── Helper: refresh token jika expired ───────────────────────────
    private function getValidAccessToken(Patient $patient): ?string
    {
        // Kalau masih valid (dengan buffer 5 menit)
        if ($patient->google_fit_token_expires_at > now()->addMinutes(5)) {
            return $patient->google_fit_access_token;
        }

        // Coba refresh
        if (!$patient->google_fit_refresh_token) return null;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id'     => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $patient->google_fit_refresh_token,
            'grant_type'    => 'refresh_token',
        ]);

        if ($response->failed()) return null;

        $tokens = $response->json();
        $patient->update([
            'google_fit_access_token'    => $tokens['access_token'],
            'google_fit_token_expires_at'=> now()->addSeconds($tokens['expires_in'] ?? 3600),
        ]);

        return $tokens['access_token'];
    }

    // ─── Helper: ambil satu dataset dari Google Fit REST API ──────────
    private function fetchDataset(string $token, string $dataSourceId, int $startNs, int $endNs): ?array
    {
        $url = "https://www.googleapis.com/fitness/v1/users/me/dataSources/{$dataSourceId}/datasets/{$startNs}-{$endNs}";

        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            \Log::warning('[GoogleFit] Failed to fetch dataset', [
                'dataSource' => $dataSourceId,
                'status'     => $response->status(),
            ]);
            return null;
        }

        return $response->json();
    }
}
