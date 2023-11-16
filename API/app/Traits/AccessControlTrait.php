<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait AccessControlTrait {

  /**
   * Scope a query to only include records that the user has access to.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   * @description This scope is used to filter records based on the user's role
   */
  public function scopeByAccessLevel(Builder $query) {

    // Get user role and id from AccessControlMiddleware
    $role = request()->role;
    $role_id = request()->role_id;
    $role_isAdmin = request()->role_isAdmin;
    $role_location_id = request()->role_location_id;
    // dd($role, $role_id, $role_isAdmin, $role_location_id);

    // Admins get all records
    if ($role_isAdmin) {
      return $query;
    }

    // Staff get records for their location only
    elseif ($role == 'staff') {
      return $query->where('location_id', $role_location_id);
    }

    // Members get records for their own member_id
    elseif ($role == 'member') {
      return $query->where('member_id', $role_id);
    }

    // If no user is logged in, return error
    else {
      return response()->json([
        'message' => 'AccessControlTrait: Access denied',
      ], 403);
    }
  }
}