<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Location extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city',
        'address',
        'phone',
        'email',
        'active',
    ];

    public function staffs() {
        return $this->hasMany(Staff::class);
    }
    public function members() {
        return $this->hasMany(Member::class);
    }
    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    // // A location has many staff
    // public function staff() {
    //     return $this->hasMany(Staff::class, 'location_id');
    // }

    // // location has many members
    // public function member() {
    //     return $this->hasMany(Member::class, 'location_id');
    // }

    // // location has many bookings
    // public function booking() {
    //     return $this->hasMany(Booking::class, 'location_id');
    // }

    // public function toArray() {
    //     $data = parent::toArray();
    //     $data['staff'] = $this->staff;
    //     $data['member'] = $this->member;
    //     $data['booking'] = $this->booking;
    //     return $data;
    // }

    // // Accessor for the 'name' attribute,
    // // so you can use $location->name
    // public function getNameAttribute() {
    //     return $this->name;
    // }
}