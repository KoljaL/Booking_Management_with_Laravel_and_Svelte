<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller {
    public function index(Request $request) {
        // dd($request->all());
        if (!$request->role_isAdmin) {
            return response()->json(['message' => 'Only Admin can see Staff.'], 404);
        }
        $staff = Staff::all();
        return response()->json(['message' => 'All Staff', 'data' => $staff], 200);
    }
    public function show(Staff $staff) {
        return $staff;
    }
    public function store(Request $request) {
        return Staff::create($request->all());
    }
    public function update(Request $request, Staff $staff) {
        $staff->update($request->all());
        return $staff;
    }
    public function destroy(Staff $staff) {
        $staff->delete();
        return response()->json(null, 204);
    }


}