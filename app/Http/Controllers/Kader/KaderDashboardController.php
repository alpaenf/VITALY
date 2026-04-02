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

        $patientsAll = Patient::with('healthRecords')->get();
        $countHighBp = 0;
        $countHighSugar = 0;
        $countObesity = 0;
        $analyzedPatients = 0;

        foreach ($patientsAll as $p) {
            $latest = $p->healthRecords->sortByDesc('recorded_at')->first();
            if ($latest) {
                $analyzedPatients++;
                if ($latest->systolic >= 140 || $latest->diastolic >= 90) {
                    $countHighBp++;
                }
                if ($latest->blood_sugar >= 200) {
                    $countHighSugar++;
                }
                if ($latest->weight && $latest->height) {
                    $bmi = $latest->weight / pow($latest->height / 100, 2);
                    if ($bmi >= 25) {
                        $countObesity++;
                    }
                }
            }
        }

        $pctHighBp = $analyzedPatients > 0 ? round(($countHighBp / $analyzedPatients) * 100) : 0;
        $pctHighSugar = $analyzedPatients > 0 ? round(($countHighSugar / $analyzedPatients) * 100) : 0;
        $pctObesity = $analyzedPatients > 0 ? round(($countObesity / $analyzedPatients) * 100) : 0;

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
                'pctHighBp'     => $pctHighBp,
                'pctHighSugar'  => $pctHighSugar,
                'pctObesity'    => $pctObesity,
            ],
            'recentRecords'  => $recentRecords,
            'recentPatients' => $recentPatients,
        ]);
    }
}
