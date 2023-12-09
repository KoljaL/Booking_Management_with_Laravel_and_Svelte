<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AccessControlTrait;
use DateTimeInterface;

// use Illuminate\Support\Facades\DB;

class Member extends Model {
    use HasFactory, SoftDeletes, AccessControlTrait;

    // $fillable is used to specify which attributes can be mass-assigned 
    // that is, which attributes can be passed into the create() method
    protected $fillable = [
        'name',
        'user_id',
        'location_id',
        'email',
        'staff_id',
        'phone',
        'jc_number',
        'max_booking',
        'active',
    ];
    // $cast is used to specify the data type of the attributes
    // so that when you retrieve the attributes, they will be of the correct data type
    protected $casts = [
        'active' => 'boolean',
    ];

    // $dateFormat is used to specify the format of the date attributes
    // protected $dateFormat = 'd.m.Y H:i:s';

    // serializeDate() is used to specify the format of the date attributes
    // protected function serializeDate(DateTimeInterface $date): string {
    //     return $date->format('d.m.Y H:i:s');
    // }

    // $dates is used to specify which attributes are dates
    // so that when you retrieve the attributes, they will be Carbon objects
    // A carbon object is a wrapper around the PHP DateTime class
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];



    // $with is used to specify which relationships to eager load by default
    // https://laravel.com/docs/10.x/eloquent-relationships#eager-loading-specific-columns
    // protected $with = ['user:id,email,invite_token', 'location:id,city'];


    public function toArray() {
        $member = parent::toArray();
        if ($this->user) {
            $member['email'] = $this->user->email;
            $member['role'] = $this->user->role;
        }
        if ($this->location) {
            $member['location_city'] = $this->location->city;
        }
        // Check if the 'user' relationship is loaded
        // if ($this->relationLoaded('user')) {
        //     $member['email'] = $this->user->email;
        //     $member['role'] = $this->user->role;
        // }

        // // Check if the 'location' relationship is loaded
        // if ($this->relationLoaded('location')) {
        //     $member['location_city'] = $this->location->city;
        // }
        return $member;
    }


    //
    // RELATIONSHIPS
    //

    // A Member belongs to one User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    // return $this->belongsTo(User::class);

    // A Member belongs to one Location
    public function location() {
        return $this->belongsTo(Location::class);
    }

    // A Member belongs to one Staff
    public function staff() {
        return $this->belongsTo(Staff::class);
    }



    // A Member has many Bookings
    public function bookings() {
        return $this->hasMany(Booking::class);
    }



    //
    // SCOPES
    //
    public function scopeShowMembers($query, $request) {
        $show = $request->show ?? 'active';
        $location_id = $request->location;

        if ($show) {
            switch ($show) {
                case 'active':
                    $query->where('active', true);
                    break;
                case 'inactive':
                    $query->where('active', false);
                    break;
                case 'all':
                    $query->withTrashed();
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
            }
        }

        // Apply 'location_id' filter
        if ($location_id) {
            // dd($location_id);
            $query->where('location_id', $location_id);
        }

        // Retrieve the results
        return $query->get();
    }



    /**
     * Scope a query to only include members with bookings.
     * @param mixed $query
     * @param mixed $all
     * @return mixed
     * @description This scope is used for the details page (form) 
     */
    public function scopeWithBookings($query, $all = false) {
        // dd($all);
        if ($all === "allBookings") {
            return $query->with('bookings')->withTrashed();
        } else {
            return $query->with(['bookings' => function ($query) {
                // $query->whereRaw("strftime('%Y-%m-%d', substr(date, 7, 4) || '-' || substr(date, 4, 2) || '-' || substr(date, 1, 2)) >= ?", [now()->format('Y-m-d')])->orderBy('date', 'asc');
                // $query->where('date', '>=', date('d.m.Y'))->orderBy('date', 'asc');
                // $query->whereRaw('strftime("%d.%m.%Y", date) >= ?', [now()->format('Y-m-d')])->orderBy('date', 'asc');
                // $query->where('date', '>=', now()->format('d.m.Y'))->orderBy('date', 'asc');
                // $query->whereDate('date', '>=', date('d.m.Y'))->orderBy('date', 'asc');
                $query->where('date', '>=', date('d.m.Y'))->orderBy('date', 'asc');
            }]);
        }
    }

    /**
     * Scope a query to only include active locations.
     * @param mixed $query
     * @return mixed
     * @description This scope is used for the Dropdown 
     */
    public function scopeShowMembersList($query) {
        return $query->where('active', true)->get(['id', 'name'])
            ->map(function ($member) {
                return [
                    'key' => $member->name,
                    'value' => (string) $member->id,
                ];
            });
    }
    public function scopeShowLocationsList($query) {
        // return $query->get(['id', 'city']);
        // return $query->get(['id as value', 'city as key']);
        return $query->get(['id', 'city'])
            ->map(function ($location) {
                return [
                    'key' => $location->city,
                    'value' => (string) $location->id,
                ];
            });
    }

}



// return $query->where('active', true)->with('user:id,email')->get(['id', 'name', 'location_id']);

// return $query->whereHas('user', function ($query) {
//     $query->select('id', 'email');
// })->get(['id', 'name', 'location_id']);

// $result = $query->where('active', true)->get();

// \DB::enableQueryLog();
// $members = $query->where('active', true)->get(['id', 'name', 'location_id']);
// $members->load('user:id,email');
// dd(\DB::getQueryLog());

// \DB::enableQueryLog();
// $result = $query->with('user:id,email')->get(['id', 'name', 'location_id']);
// dd(\DB::getQueryLog());

// the query log shows this:
// "query" => "select "id", "name", "location_id" from "members" where "members"."deleted_at" is null"

// so the with('user:id,email') is not working


// $member = Member::find(1);  
// dd($member->toArray());

// $members = $query->where('active', true)->get(['id', 'name', 'location_id']);
// $members->load('user:id,email');
// return $members;


// return $query->where('active', true)
//     ->with(['user' => function ($query) {
//         $query->select('id', 'email');
//     }])
//     ->get(['id', 'name', 'location_id']);


// return $query->where('active', true)
//     ->with(['user:id,email'])  
//     ->get(['id', 'name', 'location_id']);

// \DB::enableQueryLog();
// $result = $query->where('active', true)
//     ->with('user:email')
//     ->get(['id', 'name', 'location_id']);
// dd(\DB::getQueryLog());

// show only active members with id, name, location_id and email from user model
// return $query->where('active', true)->with('user')->get(['id', 'name', 'location_id']);

// return $query->where('active', true)->get(['id', 'name', 'location_id']);
// return $query->where('active', true)->get(['id', 'email', 'name', 'location_id']);

// public function allBookings() {
//     return $this->hasMany(Booking::class);
// }

// public function futureBookings() {
//     return $this->hasMany(Booking::class)->where('date', '>=', now());
// }

// public function scopeActiveMembers($query) {
//     return $query->where('active', true);
// }

// public function scopeInactiveMembers($query) {
//     return $query->where('active', false);
// }

// // Scope to retrieve only archived members
// public function scopeDeletedMembers($query) {
//     return $query->onlyTrashed();
// }

// // Scope to retrieve all members (both active and archived)
// public function scopeAllMembers($query) {
//     return $query->withTrashed();
// }

// // Scope to retrieve members with the same location_id as the authenticated staff or all if the staff is an admin
// public function scopeSelectMembersByRole($query, $user) {
//     if ($user->staff->is_admin) {
//         // dd($user->staff->is_admin);
//         // If the staff is an admin, return all members
//         return $query;
//     }
//     // If the staff is not an admin, filter by location_id
//     else {
//         return $query;

//         // dd($user->staff->location_id);
//         // return $query->where('location_id', $user->staff->location_id);
//     }
// }

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


// public function deleteBookings() {
//     return $this->hasMany(Booking::class)
//         ->orderBy('date', 'asc');
// }

// public function currentBookings() {
//     return $this->hasMany(Booking::class)
//         ->where('date', '>=', date('Y-m-d'));
// }


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