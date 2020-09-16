<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Facility;
use App\User;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index($id) {

        $facilities = User::find($id)->facilities;

        $facilities->load('attributes');

        $userId = $id;

        return view('users.facilities', compact('facilities', 'userId'));
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

    public function edit(Request $request, $id)
    {
        $facility = Facility::find($id);

        $facility->name = $request->type;
        
        $attributes = $request->facilityAttributes;
        
        foreach($facility->attributes as $attribute) {
            $attribute->delete();
        }

        foreach($attributes as $attribute) {
            $attribute = Attribute::create([
                'facility_id' => $id,
                'name' => $attribute['name'],
                'value' => $attribute['value'] 
            ]);
        }
            
            $facility->save();

            $facility->load('attributes');

            return $facility;

    }

    public function delete($id) 
    {
        $facility = Facility::find($id);

        foreach($facility->attributes as $attribute) {
            Attribute::find($attribute->id)->delete();
        }

        $facility->delete();
    }
}
