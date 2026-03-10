<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KaderPatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->withCount('healthRecords')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Kader/Patients', [
            'patients' => $patients,
            'filters'  => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'           => 'required|string|size:16|unique:patients,nik',
            'name'          => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender'        => 'nullable|in:male,female',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('kader.patients.show', $patient)
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function show(Patient $patient)
    {
        $records = $patient->healthRecords()
            ->latest('recorded_at')
            ->paginate(10);

        $latestRecord = $patient->healthRecords()
            ->latest('recorded_at')
            ->first();

        return Inertia::render('Kader/PatientDetail', [
            'patient'      => $patient,
            'records'      => $records,
            'latestRecord' => $latestRecord,
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender'        => 'nullable|in:male,female',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('kader.patients')->with('success', 'Pasien berhasil dihapus.');
    }

    public function showInput(Patient $patient)
    {
        return Inertia::render('Kader/InputData', [
            'patient' => $patient,
        ]);
    }

    public function storeInput(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'systolic'          => 'nullable|integer|min:50|max:300',
            'diastolic'         => 'nullable|integer|min:30|max:200',
            'heart_rate'        => 'nullable|integer|min:20|max:300',
            'blood_sugar'       => 'nullable|numeric|min:0|max:1000',
            'weight'            => 'nullable|numeric|min:1|max:500',
            'height'            => 'nullable|numeric|min:1|max:300',
            'temperature'       => 'nullable|numeric|min:30|max:45',
            'oxygen_saturation' => 'nullable|integer|min:0|max:100',
            'notes'             => 'nullable|string|max:1000',
            'recorded_at'       => 'nullable|date',
        ]);

        $validated['patient_id']  = $patient->id;
        $validated['recorded_by'] = auth()->id();
        $validated['recorded_at'] = $validated['recorded_at'] ?? now();

        HealthRecord::create($validated);

        return redirect()->route('kader.patients.show', $patient)
            ->with('success', 'Data kesehatan berhasil disimpan.');
    }

    public function search(Request $request)
    {
        $nik = $request->input('nik');

        $patient = Patient::where('nik', $nik)->first();

        if (!$patient) {
            return response()->json(['patient' => null]);
        }

        return response()->json(['patient' => $patient]);
    }
}
