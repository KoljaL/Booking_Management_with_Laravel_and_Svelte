<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model {
    use HasFactory;

    // $fillable is used to specify which attributes can be mass-assigned
    // that is, which attributes can be passed into the create() method
    // protected $fillable = ["name", "email", "password", "phone", "location_id", "jc_number", "max_booking", "active", "archived", "staff_id"];
    protected $fillable = [
        'user_id',
        'location_id',
        'staff_id',
        'phone',
        'jc_number',
        'max_booking',
        'active',
        'archived',
    ];
    // $cast is used to specify the data type of the attributes
    // so that when you retrieve the attributes, they will be of the correct data type
    protected $casts = [
        'active' => 'boolean',
        'archived' => 'boolean',
    ];

    // $dates is used to specify which attributes are dates
    // so that when you retrieve the attributes, they will be Carbon objects
    // A carbon object is a wrapper around the PHP DateTime class
    protected $dates = ['created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function staff() {
        return $this->belongsTo(Staff::class);
    }
    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    // // A member has one location
    // public function location() {
    //     return $this->belongsTo(Location::class, 'location_id');
    // }

    // // A member was created by one staff
    // public function staff() {
    //     return $this->belongsTo(Staff::class, 'staff_id');
    // }

    // //A membercan have many bookings
    // public function booking() {
    //     return $this->hasMany(Booking::class, 'member_id');
    // }


    // with the 'user' relationship, you can use $member->user->name
    // to get the name of the user associated with this member
    // public function user() {
    //     return $this->belongsTo(User::class, 'user_id');
    // }


    // Eager load the 'user' relationship by default, 
    // so you don't have to do Member::with('user')->get();
    // protected $with = ['user'];

    // include the 'name' and 'email' attributes into the $member object
    // so you can use $member->name and $member->email
    // public function toArray() {
    //     $data = parent::toArray();
    //     if ($this->user) {
    //         $data['name'] = $this->user->name;
    //         $data['email'] = $this->user->email;
    //         $data['role'] = $this->user->role;
    //     }
    //     // $data['name'] = $this->name;
    //     if ($this->email) {
    //         $data['email'] = $this->email;
    //     }
    //     if ($this->user) {
    //         $data['role'] = $this->user->role;
    //     }
    //     return $data;
    // }

    // // Accessor for the 'name' attribute, 
    // // so you can use $member->name
    // public function getNameAttribute() {
    //     return $this->user->name ?? null;
    //     // if ($this->user) {
    //     //     return $this->user->name;
    //     // }
    // }
    // // Accessor for the 'email' attribute, 
    // // so you can use $member->email
    // public function getEmailAttribute() {
    //     return $this->user->email ?? null;
    //     // if ($this->user) {
    //     //     return $this->user->email;
    //     // }
    // }

    // // Accessor for the 'role' attribute,
    // // so you can use $member->role
    // public function getRoleAttribute() {
    //     return $this->user->role ?? null;
    //     // if ($this->user) {
    //     //     return $this->user->role;
    //     // }
    // }


}