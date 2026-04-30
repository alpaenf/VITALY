<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SelfInputController extends Controller
{
    public function show()
    {
        $patientId = session('patient_id');
        if (!$patientId) return redirect()->route('patient.lookup');

        return Inertia::render('SelfInput');
    }

    public function store(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) return response()->json(['error' => 'Unauthorized'], 403);

        $validated = $request->validate([
            'systolic'          => 'nullable|integer|min:60|max:250',
            'diastolic'         => 'nullable|integer|min:40|max:150',
            'heart_rate'        => 'nullable|integer|min:30|max:220',
            'oxygen_saturation' => 'nullable|numeric|min:70|max:100',
            'blood_sugar'       => 'nullable|numeric|min:30|max:600',
            'temperature'       => 'nullable|numeric|min:34|max:42',
            'weight'            => 'nullable|numeric|min:1|max:300',
            'height'            => 'nullable|numeric|min:50|max:250',
            'source'            => 'nullable|string|in:manual,iomt',
        ]);

        // At least one vital must be present
        $hasData = collect($validated)->except('source')->filter()->isNotEmpty();
        if (!$hasData) {
            return back()->withErrors(['general' => 'Harap isi minimal satu data kesehatan.']);
        }

        HealthRecord::create([
            'patient_id'        => $patientId,
            'systolic'          => $validated['systolic']          ?? null,
            'diastolic'         => $validated['diastolic']         ?? null,
            'heart_rate'        => $validated['heart_rate']        ?? null,
            'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
            'blood_sugar'       => $validated['blood_sugar']       ?? null,
            'temperature'       => $validated['temperature']       ?? null,
            'weight'            => $validated['weight']            ?? null,
            'height'            => $validated['height']            ?? null,
            'recorded_at'       => now(),
            'notes'             => '[Sumber: ' . strtoupper($validated['source'] ?? 'manual') . ' - Input Mandiri]',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('dashboard')->with('success', 'Data kesehatan berhasil disimpan!');
    }
}
