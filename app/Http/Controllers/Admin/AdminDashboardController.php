<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\AiAnalysis;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPatients  = Patient::count();
        $totalKaders    = User::where('role', 'kader')->count();
        $totalRecords   = HealthRecord::count();
        $totalAnalyses  = AiAnalysis::count();

        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)->count();

        $recentPatients = Patient::latest()
            ->take(5)
            ->get(['id', 'nik', 'name', 'gender', 'created_at']);

        $monthlyData = HealthRecord::selectRaw('MONTH(recorded_at) as month, COUNT(*) as count')
            ->whereYear('recorded_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyChart = collect(range(1, 12))->map(fn($m) => [
            'month' => $m,
            'count' => $monthlyData[$m]->count ?? 0,
        ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalPatients'       => $totalPatients,
                'totalKaders'         => $totalKaders,
                'totalRecords'        => $totalRecords,
                'totalAnalyses'       => $totalAnalyses,
                'newPatientsThisMonth'=> $newPatientsThisMonth,
            ],
            'recentPatients' => $recentPatients,
            'monthlyChart'   => $monthlyChart,
        ]);
    }
}
