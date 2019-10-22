<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function create(Request $request)
    {
        return Country::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation
        ]);
    }
}
