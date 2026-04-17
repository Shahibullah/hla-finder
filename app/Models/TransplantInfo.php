<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransplantInfo extends Model
{
    protected $fillable = [
        'donor_id',
        'receiver_id',
        'lab_id',
        'transplant_date',
        'organ_type',
        'outcome',
        'condition_notes',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }
}