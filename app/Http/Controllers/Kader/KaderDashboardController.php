<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\HealthRecord;
use Inertia\Inertia;

class KaderDashboardController extends Controller
{
    public function index()
    {
        $totalPatients = Patient::count();
        $totalRecords  = HealthRecord::count();

        $recentRecords = HealthRecord::with('patient')
            ->where('recorded_by', auth()->id())
            ->latest('recorded_at')
            ->take(5)
            ->get();

        $recentPatients = Patient::latest()->take(5)->get();

        return Inertia::render('Kader/Dashboard', [
            'stats' => [
                'totalPatients' => $totalPatients,
                'totalRecords'  => $totalRecords,
            ],
            'recentRecords'  => $recentRecords,
            'recentPatients' => $recentPatients,
        ]);
    }
}
