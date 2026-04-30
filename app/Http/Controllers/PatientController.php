<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function showLookup()
    {
        if (session('patient_id')) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('PatientLookup', [
            'googleName' => session('tamu_google_name'),
        ]);
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        $patient = Patient::where('nik', $request->nik)->first();

        if (!$patient) {
            return back()->withErrors([
                'nik' => 'NIK tidak ditemukan. Belum terdaftar? Silakan daftar mandiri.',
            ]);
        }

        $request->session()->put('patient_id', $patient->id);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['patient_id', 'tamu_google_verified', 'tamu_google_name', 'register_google_email', 'register_google_name']);
        return redirect()->route('patient.lookup');
    }

    // ── Self Registration ─────────────────────────────────────────────

    public function showRegister()
    {
        // Must have passed Google verification
        if (!session('register_google_email')) {
            return redirect()->route('patient.lookup');
        }

        return Inertia::render('SelfRegister', [
            'googleName'  => session('register_google_name'),
            'googleEmail' => session('register_google_email'),
        ]);
    }

    public function register(Request $request)
    {
        if (!session('register_google_email')) {
            return redirect()->route('patient.register');
        }

        $validated = $request->validate([
            'nik'           => 'required|string|size:16|unique:patients,nik',
            'name'          => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender'        => 'nullable|in:male,female',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di sistem. Silakan masuk dengan NIK tersebut.',
        ]);

        $patient = Patient::create([
            'nik'             => $validated['nik'],
            'name'            => $validated['name'],
            'date_of_birth'   => $validated['date_of_birth'],
            'gender'          => $validated['gender'] ?? null,
            'phone'           => $validated['phone'] ?? null,
            'address'         => $validated['address'] ?? null,
            'google_email'    => session('register_google_email'),
            'self_registered' => true,
        ]);

        // Auto-login patient
        $request->session()->put('patient_id', $patient->id);
        $request->session()->forget(['register_google_email', 'register_google_name']);

        return back()->with('registered_patient_id', $patient->id);
    }

    public function saveDevice(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return response()->json(['ok' => false], 403);

        $request->validate(['device_id' => 'required|string|max:100']);

        Patient::where('id', $patientId)->update(['device_id' => $request->device_id]);

        return response()->json(['ok' => true]);
    }
}
