<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class MemberScope implements Scope {
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    // NOT IN USE 
    public function apply(Builder $builder, Model $model): void {
        $authUser = Auth::user();

        if (Auth::check()) {
            // If the user is a staff member and is an admin, show all members
            if ($authUser->staff && $authUser->staff->is_admin) {
                $builder;
            } elseif ($authUser->staff) {
                // Otherwise, show only members from the same location as the staff member
                $builder->where('location_id', $authUser->staff->location_id);
            } elseif ($authUser->member) {
                // If the user is a member, show only their own records
                $builder->where('id', $authUser->member->id);
            }
        }
    }
}

// public function apply(Builder $builder, Model $model): void {
//     $authUser = Auth::user();
//     // witthout this check, the database seeders will fail
//     if (Auth::check() && $authUser->staff) {
//         // If the staff member is an admin, show all members
//         if ($authUser->staff->is_admin) {
//             $builder;
//         } else {
//             // Otherwise, show only members from the same location as the staff member
//             $builder->where('location_id', $authUser->staff->location_id);
//         }
//     }
// }