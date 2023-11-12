<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait AccessControlTrait {
  public function scopeByAccessLevel(Builder $query) {
    $user = Auth::user();

    // Admins get all records
    if ($user->staff && $user->staff->is_admin) {
      return $query;
    }

    // Staff get records for their location only
    elseif ($user->staff) {
      return $query->where('location_id', $user->staff->location_id);
    }

    // Members get records for their own member_id
    elseif ($user->member) {
      return $query->where('member_id', $user->member_id);
    }
  }
}