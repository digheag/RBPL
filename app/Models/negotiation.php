<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Negotiation extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'seller_id',
        'agent_id',
        'buyer_id',
        'offer_price',
        'description',
        'is_agen_approve',
        'is_seller_approve',
    ];

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function agen(){
        return $this->belongsTo(Agent::class, 'agen_id');
    }

    public function property(){
        return $this->belongsTo(Property::class);
    }

    public function transaction(){
        return $this->hasOne(transaction::class);
    }
}
