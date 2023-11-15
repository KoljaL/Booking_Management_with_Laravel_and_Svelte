<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait AccessControlTrait {

  /**
   * Summary of scopeByAccessLevel
   * 
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @description Get records by access level
   */
  public function scopeByAccessLevel(Builder $query) {
    $user = Auth::user();

    // Admins get all records
    if (!empty($user->staff) && $user->staff->is_admin) {
      return $query;
    }

    // Staff get records for their location only
    elseif (!empty($user->staff)) {
      return $query->where('location_id', $user->staff->location_id);
    }

    // Members get records for their own member_id
    elseif ($user->member) {
      return $query->where('member_id', $user->member->id);
    }
  }
}