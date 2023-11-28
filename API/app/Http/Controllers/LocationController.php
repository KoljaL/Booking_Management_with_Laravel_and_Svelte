<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Staff;
use App\Models\User;

class LocationController extends Controller {

    public function list(Request $request) {
        isAdmin($request->role_isAdmin);
        try {
            $locations = Location::select('id', 'city')->get();
            return response()->json(['message' => 'All Locations', 'data' => $locations], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Locations not found', 'error' => $th->getMessage()], 404);
        }
    }

    /**
     * Location INDEX
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get all location.
     * @path GET api/location
     */
    public function index(Request $request) {
        // dd($request->all());
        // if (!$request->role_isAdmin) { return response()->json(['message' => 'Only Admin can see Location.'], 404); }
        isAdmin($request->role_isAdmin);
        try {
            $location = Location::all();
            return response()->json(['message' => 'All ' . $request->show . ' Location', 'data' => $location], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Locations not found', 'error' => $th->getMessage()], 404);
        }
    }



    /**
     * Location SHOW
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Get a location by access level.
     * @path GET api/location/{id}
     */
    public function show(Request $request, $id) {
        isAdmin($request->role_isAdmin);

        try {
            $location = Location::withTrashed()->findOrFail($id);

            return response()->json(['message' => 'Location data', 'show' => $request->show, 'data' => $location], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Location with ID ' . $id . ' not found', 'exception' => $e], 404);
        }
    }


    public function store(Request $request) {
        isAdmin($request->role_isAdmin);

        // validate the request, email must be unique for users
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {



            // then create a new location
            $location = Location::create([
                'city' => $request->city,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            // associate the location with the user
            DB::commit();

            // get the new location with the
            $newLocation = Location::find($location->id);
            // return the location
            return response()->json(['message' => 'Location created', 'data' => $newLocation], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Error creating location', 'error' => $th], 500);
        }

    }
    public function update(Request $request, $id) {
        isAdmin($request->role_isAdmin);

        $location = Location::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            // update Location  
            $location->update([
                'city' => $request->city,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            // try to update the location
            DB::commit();

            // return the location
            return response()->json(['message' => 'Location updated', 'data' => $location], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error update location', 'error' => $e], 500);
        }

    }


    /**
     * Location DESTROY
     * 
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     * @description Delete a location by access level.
     * @path DELETE api/location/{id}
     */
    public function destroy(Request $request, $id) {
        isAdmin($request->role_isAdmin);
        // get Location
        $location = Location::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $oldLocation = $location->toArray();

            // delete the location
            try {
                $location->delete();
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Error deleting user', 'error' => $th], 500);
            }

            DB::commit();

            return response()->json(['message' => 'Location deleted', 'data' => $oldLocation], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Location not found', 'error' => $th], 404);
        }
    }


}

function isAdmin($isAdmin) {
    if (!$isAdmin) {
        return response()->json(['message' => 'Only Admin can see Location.'], 404);
    }
}