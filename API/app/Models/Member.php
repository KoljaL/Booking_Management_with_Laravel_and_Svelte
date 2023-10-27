<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model {
    use HasFactory;

    // $fillable is used to specify which attributes can be mass-assigned
    // that is, which attributes can be passed into the create() method
    protected $fillable = ["name", "email", "password", "phone", "location_id", "jc_number", "max_booking", "active", "archived", "staff_id"];

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



    // combine the update of the User and Member models into a single transaction
    // so that if one fails, the other will be rolled back
    public function updateMemberAndUser($userData, $memberData) {
        DB::beginTransaction();
        try {
            // Update User attributes
            $this->user->update($userData);
            // Update Member attributes
            $this->update($memberData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // return error message 
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    // with the 'user' relationship, you can use $member->user->name
    // to get the name of the user associated with this member
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Eager load the 'user' relationship by default, 
    // so you don't have to do Member::with('user')->get();
    // protected $with = ['user'];

    // include the 'name' and 'email' attributes into the $member object
    // so you can use $member->name and $member->email
    public function toArray() {
        $data = parent::toArray();
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['role'] = $this->user->role;
        return $data;
    }

    // Accessor for the 'name' attribute, 
    // so you can use $member->name
    public function getNameAttribute() {
        return $this->user->name;
    }
    // Accessor for the 'email' attribute, 
    // so you can use $member->email
    public function getEmailAttribute() {
        return $this->user->email;
    }

    // Accessor for the 'role' attribute,
    // so you can use $member->role
    public function getRoleAttribute() {
        return $this->user->role;
    }

    public function staff() {
        return $this->belongsTo(Staff::class);
    }

    public function booking() {
        return $this->hasMany(Booking::class);
    }


    public function location() {
        return $this->belongsTo(Location::class);
    }
}