<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HealthRecordController extends Controller
{
    public function index()
    {
        $patient = Patient::findOrFail(session('patient_id'));

        $records = $patient->healthRecords()
            ->latest('recorded_at')
            ->paginate(10);

        return Inertia::render('History', [
            'records' => $records,
        ]);
    }

    public function destroy(HealthRecord $healthRecord)
    {
        // Pastikan record milik pasien yang sedang login
        if ($healthRecord->patient_id !== session('patient_id')) {
            abort(403);
        }

        $healthRecord->delete();

        return redirect()->route('history')->with('success', 'Data berhasil dihapus.');
    }
}
