<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agentRegency()
    {
        return $this->hasMany(Agent_regency::class, 'agent_id');
    }

    public function appoinment(){
        return $this->hasMany(Appoinment::class);
    }

    public function negotiation(){
        return $this->hasMany(negotiation::class);
    }
}
