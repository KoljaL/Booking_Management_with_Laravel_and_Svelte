<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;

    // A booking belongs to one Member
    public function member() {
        // return $this->belongsTo(Member::class);
        return $this->belongsTo(Member::class, 'member_id');
    }

    // A booking belongs to one Location
    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    //     // include the 'name' and 'email' attributes into the $member object
//     // so you can use $member->name and $member->email
//     public function toArray() {
//         $data = parent::toArray();
//         $data['member_name'] = $this->name;
//         return $data;
//     }
//     // Accessor for the 'name' attribute, 
//     // so you can use $member->name
//     public function getNameAttribute() {
//         return $this->member->name;
//     }
}