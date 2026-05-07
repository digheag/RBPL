<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appoinment_schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'schedule',
        'is_agen_approve_schedule',
        'is_seller_approve_schedule',
    ];

    public function appoinment(){
        return $this->belongsTo(Appoinment::class);
    }
}
