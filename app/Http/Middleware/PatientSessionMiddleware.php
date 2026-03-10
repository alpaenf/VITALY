<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Patient;
use Symfony\Component\HttpFoundation\Response;

class PatientSessionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $patientId = $request->session()->get('patient_id');

        if (!$patientId) {
            return redirect()->route('patient.lookup');
        }

        $patient = Patient::find($patientId);

        if (!$patient) {
            $request->session()->forget('patient_id');
            return redirect()->route('patient.lookup');
        }

        // Make patient available on request
        $request->merge(['_patient' => $patient]);

        return $next($request);
    }
}
