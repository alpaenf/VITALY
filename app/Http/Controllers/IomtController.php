<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  VITALY — IoMT Push Controller                              ║
 * ║  Menerima data kesehatan dari smartwatch via Phone Gateway  ║
 * ║                                                              ║
 * ║  Alur: Mi Band 8 → Mi Fitness → Health Connect →           ║
 * ║        Tasker/Automate → POST /api/iomt/push → VITALY       ║
 * ╚══════════════════════════════════════════════════════════════╝
 */
class IomtController extends Controller
{
    /**
     * Terima push data vital dari HP (Tasker / HTTP Shortcuts / Automate).
     *
     * Header wajib:  X-IoMT-Token: {iomt_token pasien}
     * Content-Type:  application/json
     *
     * Body (semua opsional, minimal 1 harus ada):
     * {
     *   "heart_rate": 78,
     *   "systolic": 120,
     *   "diastolic": 80,
     *   "oxygen_saturation": 98,
     *   "weight": 65.5,
     *   "temperature": 36.7,
     *   "blood_sugar": 95,
     *   "steps": 4500,
     *   "source": "tasker"
     * }
     */
    public function push(Request $request)
    {
        // ── 1. Autentikasi via token ──────────────────────────────────
        $token = $request->header('X-IoMT-Token')
                 ?? $request->input('iomt_token'); // fallback dari body

        if (!$token) {
            return response()->json([
                'ok'      => false,
                'error'   => 'missing_token',
                'message' => 'Header X-IoMT-Token wajib disertakan.',
            ], 401);
        }

        $patient = Patient::where('iomt_token', $token)->first();

        if (!$patient) {
            return response()->json([
                'ok'      => false,
                'error'   => 'invalid_token',
                'message' => 'Token tidak valid atau pasien tidak ditemukan.',
            ], 401);
        }

        // ── 2. Validasi data vital ────────────────────────────────────
        $validated = $request->validate([
            'heart_rate'        => 'nullable|integer|min:30|max:220',
            'systolic'          => 'nullable|integer|min:60|max:260',
            'diastolic'         => 'nullable|integer|min:30|max:160',
            'oxygen_saturation' => 'nullable|numeric|min:70|max:100',
            'weight'            => 'nullable|numeric|min:1|max:300',
            'temperature'       => 'nullable|numeric|min:34|max:42',
            'blood_sugar'       => 'nullable|numeric|min:30|max:600',
            'steps'             => 'nullable|integer|min:0|max:100000',
            'source'            => 'nullable|string|max:50',
        ]);

        // Pastikan minimal 1 data vital ada
        $vitals = collect($validated)->except(['steps', 'source'])->filter();
        if ($vitals->isEmpty()) {
            return response()->json([
                'ok'      => false,
                'error'   => 'no_data',
                'message' => 'Harap kirim minimal satu data vital (heart_rate, systolic, dll).',
            ], 422);
        }

        // ── 3. Simpan ke health_records ───────────────────────────────
        $source = $validated['source'] ?? 'iomt';
        $steps  = $validated['steps'] ?? null;

        $record = HealthRecord::create([
            'patient_id'        => $patient->id,
            'heart_rate'        => $validated['heart_rate']        ?? null,
            'systolic'          => $validated['systolic']          ?? null,
            'diastolic'         => $validated['diastolic']         ?? null,
            'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
            'weight'            => $validated['weight']            ?? null,
            'temperature'       => $validated['temperature']       ?? null,
            'blood_sugar'       => $validated['blood_sugar']       ?? null,
            'recorded_at'       => now(),
            'notes'             => sprintf(
                '[IoMT Gateway] Sumber: %s%s',
                strtoupper($source),
                $steps ? " | Langkah: {$steps} steps" : ''
            ),
        ]);

        return response()->json([
            'ok'         => true,
            'message'    => 'Data berhasil diterima dan disimpan ke VITALY.',
            'patient'    => $patient->name,
            'record_id'  => $record->id,
            'saved_at'   => $record->recorded_at->toISOString(),
            'data_saved' => $vitals->keys()->values(),
        ]);
    }

    /**
     * Generate token IoMT baru untuk pasien yang sedang login.
     * Dipanggil dari dashboard VITALY.
     */
    public function generateToken(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $patient = Patient::find($patientId);
        if (!$patient) {
            return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
        }

        $token = Str::random(48);
        $patient->update(['iomt_token' => $token]);

        return response()->json([
            'ok'    => true,
            'token' => $token,
        ]);
    }

    /**
     * Ambil info token IoMT milik pasien yang login.
     */
    public function tokenInfo(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $patient = Patient::find($patientId);

        return response()->json([
            'has_token'   => !empty($patient?->iomt_token),
            'token'       => $patient?->iomt_token,
            'push_url'    => url('/api/iomt/push'),
        ]);
    }
}
