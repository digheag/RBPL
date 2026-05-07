<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'username',
        'telp_number',
        'profile',
        'role',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //user punya 1 data agent 
    public function agent(){
        return $this->hasOne(Agent::class, 'user_id');
    }

    public function negotiationAsBuyer(){
        return $this->hasMany(negotiation::class, 'buyer_id');
    }

    public function negotiationAsSeller(){
        return $this->hasMany(negotiation::class, 'seller_id');
    }

    public function transactionsAsSeller(){
    return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function transactionsAsBuyer(){
        return $this->hasMany(Transaction::class, 'buyer_id');
    }
}
