<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property_image extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'name',
        'url',
        'is_banner',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
