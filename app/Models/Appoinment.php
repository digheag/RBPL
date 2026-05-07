<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appoinment extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'seller_id',
        'district_id',
        'property_name',
        'property_address',
        'actual_time_schedule',
        'is_approved_by_agen',
    ];

    public function agent(){
        return $this->belongsTo(Agent::class);
    }

    public function appoinment_schedules(){
        return $this->hasMany(Appoinment_schedule::class, 'appointment_id');
    }

    public function district(){
        return $this->belongsTo(District::class, 'district_id');
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }
}
