<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent_regency extends Model
{
    protected $fillable = [
        'agent_id',
        'regency_id',
    ];
    public function agent()
    {
        return $this->BelongsTo(Agent::class, 'agent_id');
    }
    public function regency()
    {
        return $this->BelongsTo(Regency::class, 'regency_id');
    }
}
