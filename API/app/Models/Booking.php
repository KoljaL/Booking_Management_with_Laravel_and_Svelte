<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AccessControlTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model {
    use HasFactory, SoftDeletes, AccessControlTrait;

    // $fillable is used to specify which attributes can be mass-assigned 
    // that is, which attributes can be passed into the create() method
    protected $fillable = [
        'member_id',
        'staff_id',
        'location_id',
        'date',
        'time',
        'slots',
        'state',
        'started_at',
        'ended_at',
        'comment_member',
        'comment_staff',
    ];

    // $dates is used to specify which attributes are dates
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'date', 'started_at', 'ended_at'];

    // $with is used to specify which relationships to eager load by default
    // https://laravel.com/docs/10.x/eloquent-relationships#eager-loading-specific-columns
    // protected $with = ['member:id,name', 'location:id,city'];

    public function toArray() {
        $booking = parent::toArray();
        // if ($this->booking) {
        $booking['member_name'] = $this->member->name;
        $booking['location_city'] = $this->location->city;
        // }
        return $booking;
    }

    //
    // RELATIONSHIPS
    //

    // A booking belongs to one Member
    public function member() {
        // return $this->belongsTo(Member::class);
        return $this->belongsTo(Member::class, 'member_id');
    }

    // A booking belongs to one Location
    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
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
    //
    // SCOPES
    //

    public function scopeFilter(Builder $query, $filters) {
        if (!isset($filters['date'])) {
            // $filters['date'] = 'today';
        }
        if (isset($filters['date'])) {
            switch ($filters['date']) {
                case 'upcoming':
                    $query->whereDate('date', '>=', today());
                    break;
                case 'today':
                    $query->whereDate('date', today());
                    break;
                default:
                    $query->whereDate('date', $filters['date']);
            }
        }

        if (isset($filters['member'])) {
            $query->where('member_id', $filters['member']);
        }

        if (isset($filters['show'])) {
            switch ($filters['show']) {
                case 'active':
                    $query; //->where('state', 1);
                    break;
                case 'all':
                    $query->withTrashed();
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
            }
        }

        if (isset($filters['location'])) {
            $query->where('location_id', $filters['location']);
        }

        return $query;
    }


    // protected $dateFormat = 'd.m.Y H:i:s';

    // protected function serializeDate(DateTimeInterface $date): string {
    //     return $date->format('d.m.Y H:i:s');
    // }

}


// public function scopeShowBookings($query, $show, $date) {
//     // public function scopeShowBookings($query, $show, $date, $location_id, $member_id, $staff_id) {
//     // dd($show);
//     // $date = request()->date ?? null;
//     // $show = request()->show ?? null;
//     // dd($date, $show);
//     switch ($show) {
//         case 'all':
//             return $query->withTrashed()->showBookingsByDate($date);
//         case 'deleted':
//             return $query->onlyTrashed()->showBookingsByDate($date);
//         default:
//             return $query->showBookingsByDate($date);
//     }
// }
// public function scopeShowBookingsByDate($query, $date) {
//     // return $query->whereDate('date', $date)->get();
//     switch ($date) {
//         case null:
//             return $query->get();
//         case 'today':
//             return $query->whereDate('date', date('Y-m-d'))->get();
//         case 'upcoming':
//             return $query->whereDate('date', '>=', date('Y-m-d'))->get();
//         default:
//             return $query->whereDate('date', $date)->get();
//     }
// }



// get the date from the $show parameter
// if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $show)) {
//     $date = $show;
//     $show = 'date';
// }
// case 'date':
//     return $query->whereDate('date', $date)->get();