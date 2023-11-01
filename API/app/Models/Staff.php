<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    use HasFactory;

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function members() {
        return $this->hasMany(Member::class);
    }
    public function bookings() {
        return $this->hasMany(Booking::class);
    }


    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function members() {
    //     return $this->hasMany(Member::class, 'staff_id');
    // }
    // public function location() {
    //     // return $this->belongsTo(Location::class);
    //     return $this->belongsTo(Location::class, 'location_id');
    // }
}