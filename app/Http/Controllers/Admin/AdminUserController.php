<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->withCount('healthRecords', 'aiAnalyses')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Patients', [
            'patients' => $patients,
            'filters'  => ['search' => $search],
        ]);
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'healthRecords' => fn($q) => $q->latest('recorded_at')->take(50),
            'aiAnalyses'    => fn($q) => $q->latest()->take(10),
        ]);

        return Inertia::render('Admin/UserDetail', [
            'user'     => $patient,
            'records'  => $patient->healthRecords,
            'analyses' => $patient->aiAnalyses,
        ]);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return back()->with('success', 'Pasien berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->withCount('healthRecords', 'aiAnalyses')
            ->latest()
            ->get();

        $filename = 'pasien_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($patients) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'NIK', 'Nama', 'Gender', 'Tanggal Lahir', 'No HP', 'Total Data', 'Total Analisis', 'Terdaftar']);
            foreach ($patients as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->nik,
                    $p->name,
                    $p->gender ?? '-',
                    $p->date_of_birth?->format('d/m/Y') ?? '-',
                    $p->phone ?? '-',
                    $p->health_records_count,
                    $p->ai_analyses_count,
                    $p->created_at->format('d/m/Y'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
