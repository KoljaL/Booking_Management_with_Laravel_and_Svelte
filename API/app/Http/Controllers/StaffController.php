<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StaffController extends Controller {

    /**
     * Staff INDEX
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get all staff.
     * @path GET api/staff
     */
    public function index(Request $request) {
        // dd($request->all());
        // if (!$request->role_isAdmin) { return response()->json(['message' => 'Only Admin can see Staff.'], 404); }
        isAdmin($request->role_isAdmin);
        try {
            $staff = Staff::all();
            return response()->json(['message' => 'All ' . $request->show . ' Staff', 'data' => $staff], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Staffs not found', 'error' => $th->getMessage()], 404);
        }
    }



    /**
     * Staff SHOW
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get a staff by access level.
     * @path GET api/staff/{id}
     */
    public function show(Request $request, $id) {
        isAdmin($request->role_isAdmin);

        try {
            $staff = Staff::withTrashed()->findOrFail($id);

            return response()->json(['message' => 'Staff data', 'show' => $request->show, 'data' => $staff], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Staff with ID ' . $id . ' not found', 'exception' => $e], 404);
        }
    }


    public function store(Request $request) {
        isAdmin($request->role_isAdmin);

        // validate the request, email must be unique for users
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'location_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {

            // first create a new user
            $user = User::create([
                'email' => $request->email,
                'role' => 'staff',
            ]);
            // dd($user->id);

            // then create a new staff
            $staff = Staff::create([
                'user_id' => $user->id,
                'location_id' => $request->location_id,
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            // associate the staff with the user
            $user->staff()->save($staff);
            DB::commit();

            // get the new staff with the
            $newStaff = Staff::find($staff->id);
            // return the staff
            return response()->json(['message' => 'Staff created', 'data' => $newStaff], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error creating staff', 'error' => $th], 500);
        }

    }
    public function update(Request $request, $id) {
        isAdmin($request->role_isAdmin);

        $staff = Staff::withTrashed()->byAccessLevel()->findOrFail($id);
        $updateEmail = false;

        // the email is a User attribute, so we need to check if it has changed
        if ($request->has('email') && $request->email != $staff->user->email) {
            $updateEmail = true;
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ]);
            $userData = $request->only(['email']);
        }

        $staffData = $request->toArray();

        DB::beginTransaction();
        try {
            if ($updateEmail) {
                // update User
                $user = $staff->user;
                $user->update($userData);
            }
            // update Staff  
            $staff->update($staffData);

            // try to update the staff
            DB::commit();

            // return the staff
            return response()->json(['message' => 'Staff updated', 'data' => $staff], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error update staff', 'error' => $e], 500);
        }

    }


    /**
     * Staff DESTROY
     * 
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Delete a staff by access level.
     * @path DELETE api/staff/{id}
     */
    public function destroy(Request $request, $id) {
        isAdmin($request->role_isAdmin);
        // get Staff
        $staff = Staff::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $staff->active = false;
            $staff->save();
            $oldStaff = $staff->toArray();

            // first delete the staff
            try {
                $staff->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting staff', 'error' => $th], 500);
            }

            // and finally delete the user
            try {
                $staff->user()->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting user', 'error' => $th], 500);
            }

            DB::commit();

            return response()->json(['message' => 'Staff deleted', 'data' => $oldStaff], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Staff not found', 'error' => $th], 404);
        }
    }


}

function isAdmin($isAdmin) {
    if (!$isAdmin) {
        return response()->json(['message' => 'Only Admin can see Staff.'], 404);
    }
}