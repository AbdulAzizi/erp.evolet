<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index() {

        $facilities = Facility::where('user_id', auth()->user()->id)->with('attributes')->get();

        return view('users.facilities', compact('facilities'));
    }

    public function create(Request $request)
    {
        $facility = Facility::create([
            'user_id' => auth()->user()->id,
            'name' => $request->type
        ]);

        $attributes = $request->facilityAttributes;

        foreach($attributes as $attribute)
        {

            $attribute = Attribute::create([
                'facility_id' => $facility->id,
                'name' => $attribute['name'],
                'value' => $attribute['value']
            ]);
        }
        
        $facility->load('attributes');
        
        return $facility;
    }
}
