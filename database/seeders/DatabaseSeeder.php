<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\HealthRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin VITALY',
            'email' => 'admin@VITALY.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $kader = User::create([
            'name' => 'Health Agent VITALY',
            'email' => 'kader@VITALY.com',
            'password' => Hash::make('password'),
            'role' => 'kader',
        ]);

        $patient = Patient::create([
            'nik'           => '3201012505950001',
            'name'          => 'Budi Santoso',
            'date_of_birth' => '1995-05-25',
            'gender'        => 'male',
            'phone'         => '081234567890',
            'address'       => 'Jl. Contoh No. 1, Jakarta',
            'device_id'     => null,
        ]);

        $records = [
            ['systolic' => 118, 'diastolic' => 78, 'heart_rate' => 72, 'blood_sugar' => 90, 'weight' => 70, 'height' => 170, 'temperature' => 36.5, 'oxygen_saturation' => 98, 'recorded_at' => now()->subDays(7)],
            ['systolic' => 122, 'diastolic' => 80, 'heart_rate' => 75, 'blood_sugar' => 95, 'weight' => 70.5, 'height' => 170, 'temperature' => 36.7, 'oxygen_saturation' => 97, 'recorded_at' => now()->subDays(5)],
            ['systolic' => 120, 'diastolic' => 79, 'heart_rate' => 70, 'blood_sugar' => 88, 'weight' => 70, 'height' => 170, 'temperature' => 36.6, 'oxygen_saturation' => 98, 'recorded_at' => now()->subDays(3)],
            ['systolic' => 119, 'diastolic' => 77, 'heart_rate' => 73, 'blood_sugar' => 92, 'weight' => 69.8, 'height' => 170, 'temperature' => 36.4, 'oxygen_saturation' => 99, 'recorded_at' => now()->subDays(1)],
        ];

        foreach ($records as $record) {
            HealthRecord::create(array_merge($record, [
                'patient_id'  => $patient->id,
                'recorded_by' => $kader->id,
            ]));
        }

        // ── Pasien Kritis (IoMT Demo) ────────────────────────────────
        $patientKritis = Patient::create([
            'nik'           => '3201015508700002',
            'name'          => 'Siti Rahayu',
            'date_of_birth' => '1970-08-15',
            'gender'        => 'female',
            'phone'         => '082345678901',
            'address'       => 'Jl. Mawar No. 5, Bogor',
            'device_id'     => 'VTL-M18BX9',
        ]);

        $recordsKritis = [
            ['systolic' => 155, 'diastolic' => 98, 'heart_rate' => 95, 'blood_sugar' => 185, 'weight' => 78, 'height' => 158, 'temperature' => 37.1, 'oxygen_saturation' => 96, 'recorded_at' => now()->subDays(14)],
            ['systolic' => 162, 'diastolic' => 102, 'heart_rate' => 100, 'blood_sugar' => 210, 'weight' => 78.5, 'height' => 158, 'temperature' => 37.3, 'oxygen_saturation' => 95, 'recorded_at' => now()->subDays(7)],
            ['systolic' => 185, 'diastolic' => 115, 'heart_rate' => 112, 'blood_sugar' => 245, 'weight' => 79, 'height' => 158, 'temperature' => 37.2, 'oxygen_saturation' => 94, 'notes' => 'Data otomatis ditarik via Bluetooth IoMT (VITALY Pulse v2.0)', 'recorded_at' => now()->subDays(1)],
        ];

        foreach ($recordsKritis as $record) {
            HealthRecord::create(array_merge($record, [
                'patient_id'  => $patientKritis->id,
                'recorded_by' => $kader->id,
            ]));
        }
    }
}
