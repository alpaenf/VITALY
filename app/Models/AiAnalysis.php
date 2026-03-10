<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'prompt',
        'result',
        'records_analyzed',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
