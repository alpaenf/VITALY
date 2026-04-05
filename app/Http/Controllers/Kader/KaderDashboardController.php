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
        $patientsHighBp = [];
        $patientsHighSugar = [];
        $patientsObesity = [];

        foreach ($patientsAll as $p) {
            $latest = $p->healthRecords->sortByDesc('recorded_at')->first();
            if ($latest) {
                $analyzedPatients++;
                if ($latest->systolic >= 140 || $latest->diastolic >= 90) {
                    $countHighBp++;
                    $patientsHighBp[] = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'systolic' => $latest->systolic,
                        'diastolic' => $latest->diastolic,
                    ];
                }
                if ($latest->blood_sugar >= 200) {
                    $countHighSugar++;
                    $patientsHighSugar[] = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'blood_sugar' => $latest->blood_sugar,
                    ];
                }
                if ($latest->weight && $latest->height) {
                    $bmi = $latest->weight / pow($latest->height / 100, 2);
                    if ($bmi >= 25) {
                        $countObesity++;
                        $patientsObesity[] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'imt' => round($bmi, 1),
                        ];
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
                'totalPatients'    => $totalPatients,
                'totalRecords'     => $totalRecords,
                'analyzedPatients' => $analyzedPatients,
                'countHighBp'      => $countHighBp,
                'countHighSugar'   => $countHighSugar,
                'countObesity'     => $countObesity,
                'pctHighBp'        => $pctHighBp,
                'pctHighSugar'     => $pctHighSugar,
                'pctObesity'       => $pctObesity,
                'patientsHighBp'   => $patientsHighBp,
                'patientsHighSugar'=> $patientsHighSugar,
                'patientsObesity'  => $patientsObesity,
            ],
            'recentRecords'  => $recentRecords,
            'recentPatients' => $recentPatients,
        ]);
    }
}
