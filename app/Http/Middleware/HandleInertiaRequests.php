<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $patientId = $request->session()->get('patient_id');
        $patient   = $patientId ? Patient::find($patientId) : null;

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id'   => $request->user()->id,
                    'name' => $request->user()->name,
                    'email'=> $request->user()->email,
                    'role' => $request->user()->role,
                ] : null,
            ],
            'patient' => $patient ? [
                'id'            => $patient->id,
                'nik'           => $patient->nik,
                'name'          => $patient->name,
                'gender'        => $patient->gender,
                'age'           => $patient->age,
                'date_of_birth' => $patient->date_of_birth?->format('Y-m-d'),
                'phone'         => $patient->phone,
                'address'       => $patient->address,
            ] : null,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
