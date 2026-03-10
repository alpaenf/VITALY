<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patient;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'recorded_by',
        'systolic',
        'diastolic',
        'heart_rate',
        'blood_sugar',
        'weight',
        'height',
        'temperature',
        'oxygen_saturation',
        'notes',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'weight' => 'float',
        'height' => 'float',
        'temperature' => 'float',
        'blood_sugar' => 'float',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightM = $this->height / 100;
            return round($this->weight / ($heightM * $heightM), 1);
        }
        return null;
    }

    public function getBmiStatusAttribute(): ?string
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }

    public function getBloodPressureStatusAttribute(): ?string
    {
        if (!$this->systolic || !$this->diastolic) return null;
        if ($this->systolic < 120 && $this->diastolic < 80) return 'Normal';
        if ($this->systolic < 130 && $this->diastolic < 80) return 'Elevated';
        if ($this->systolic < 140 || $this->diastolic < 90) return 'High Stage 1';
        return 'High Stage 2';
    }
}
