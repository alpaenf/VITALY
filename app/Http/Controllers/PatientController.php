<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function showLookup()
    {
        // Jika sudah ada sesi pasien, redirect ke dashboard
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
                'nik' => 'NIK tidak ditemukan dalam sistem.',
            ]);
        }

        $request->session()->put('patient_id', $patient->id);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['patient_id', 'tamu_google_verified', 'tamu_google_name']);
        return redirect()->route('patient.lookup');
    }
}
