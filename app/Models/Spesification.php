<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spesification extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'description',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
