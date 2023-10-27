<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    // both the Staff and Member models have timestamps, 
    // so we don't need it here
    public $timestamps = false;
    const ROLE_STAFF = 'staff';
    const ROLE_MEMBER = 'member';

    public function member() {
        return $this->hasOne(Member::class);
    }

    public function staff() {
        return $this->hasOne(Staff::class);
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}


// public function member() {
//     if ($this->role === self::ROLE_MEMBER) {
//         return $this->hasOne(Member::class)->onDelete(function ($member) {
//             $member->delete();
//         });
//     } else {
//         return $this->hasOne(Member::class);
//     }
// }
// return $this->hasOne(Member::class)->onDelete('cascade');