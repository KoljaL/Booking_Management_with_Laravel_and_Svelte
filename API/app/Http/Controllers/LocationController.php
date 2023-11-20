<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;


class LocationController extends Controller {
    public function index() {
        try {
            $locations = Location::all();
            return response()->json(['message' => 'All Locations', 'data' => $locations], 200);
        } catch (\Exception $th) {
            return response()->json(['message' => 'Locations not found.', 'error' => $th->getMessage()], 404);

        }
    }
    public function show(Location $location) {
        return $location;
    }
    public function store(Request $request) {
        return Location::create($request->all());
    }
    public function update(Request $request, Location $location) {
        $location->update($request->all());
        return $location;
    }
    public function destroy(Location $location) {
        $location->delete();
        return response()->json(null, 204);
    }
}