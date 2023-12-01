<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AccessControlTrait;
use DateTimeInterface;

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
    public function scopeShowBookings($query, $show, $date) {
        // dd($show);
        // $date = request()->date ?? null;
        // $show = request()->show ?? null;
        // dd($date, $show);

        switch ($show) {
            case 'all':
                return $query->withTrashed()->showBookingsByDate($date);
            case 'deleted':
                return $query->onlyTrashed()->showBookingsByDate($date);
            default:
                return $query->showBookingsByDate($date);
        }
    }

    public function scopeShowBookingsByDate($query, $date) {
        // return $query->whereDate('date', $date)->get();
        switch ($date) {
            case null:
                return $query->get();
            case 'today':
                return $query->whereDate('date', date('Y-m-d'))->get();
            case 'upcoming':
                return $query->whereDate('date', '>=', date('Y-m-d'))->get();
            default:
                return $query->whereDate('date', $date)->get();
        }
    }

    // protected $dateFormat = 'd.m.Y H:i:s';

    // protected function serializeDate(DateTimeInterface $date): string {
    //     return $date->format('d.m.Y H:i:s');
    // }

}

// get the date from the $show parameter
// if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $show)) {
//     $date = $show;
//     $show = 'date';
// }
// case 'date':
//     return $query->whereDate('date', $date)->get();