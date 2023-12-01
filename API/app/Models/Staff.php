<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Staff extends Model {
    use HasFactory, SoftDeletes;

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

    public function toArray() {
        $staff = parent::toArray();
        if ($this->user) {
            $staff['email'] = $this->user->email;
            $staff['role'] = $this->user->role;
            $staff['location_city'] = $this->location->city;
        }
        return $staff;
    }

    // protected $dateFormat = 'd.m.Y H:i:s';

    // protected function serializeDate(DateTimeInterface $date): string {
    //     return $date->format('d.m.Y H:i:s');
    // }

    // public function members() {
    //     return $this->hasMany(Member::class, 'staff_id');
    // }
    // public function location() {
    //     // return $this->belongsTo(Location::class);
    //     return $this->belongsTo(Location::class, 'location_id');
    // }
}