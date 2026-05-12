<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'device_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function aiAnalyses()
    {
        return $this->hasMany(AiAnalysis::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }
}
