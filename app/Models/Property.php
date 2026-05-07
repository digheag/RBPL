<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'appoinment_id',
        'name', 
        'address',
        'price',
        'sold_date',
        'area_in_hectare',
        'description'
    ];

    public function Property_image(){
        return $this->hasMany(Property_image::class);
    }

    public function Spesification(){
        return $this->hasMany(Spesification::class);
    }

    public function Facilities(){
        return $this->hasMany(Facilities::class);
    }

    public function appoinment(){
        return $this->belongsTo(Appoinment::class);
    }

    public function transaction(){
        return $this->hasOne(transaction::class);
    }
}

