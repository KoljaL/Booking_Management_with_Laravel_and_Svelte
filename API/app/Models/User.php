<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // both the Staff and Member models have timestamps, 
    // so we don't need it here
    public $timestamps = false;
    const ROLE_STAFF = 'staff';
    const ROLE_MEMBER = 'member';

    public function staff() {
        return $this->hasOne(Staff::class);
    }
    public function member() {
        return $this->hasOne(Member::class);
    }

    public function isAdmin() {
        return $this->staff->is_admin;
    }

    // public function member() {
    //     return $this->hasOne(Member::class, 'user_id', 'id');
    // }

    // public function staff() {
    //     return $this->hasOne(Staff::class, 'user_id', 'id');
    // }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'invite_token'
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