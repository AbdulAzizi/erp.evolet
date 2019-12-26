<?php

namespace App\Http\Controllers;

use App\Country;
use App\Factory;
use App\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Image;

class FactoryController extends Controller
{
    public function index()
    {
        $factories = Factory::with('country')->get();
        return view('factories.index', compact('factories'));
    }

    public function create()
    {
        $countries = Country::all();

        return view('factories.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $newFileName = '';
        
        if ($request->file('logo')) {
            $originalImage = $request->file('logo');
            $newFileName = Str::random(10) . '.' . $originalImage->getClientOriginalExtension();
            $thumbnailImage = Image::make($originalImage);
            $thumbnailImage->save(public_path('img/' . $newFileName));
        }

        Factory::create([
            'name' => $request->name,
            'about' => $request->about,
            'country_id' => $request->country_id,
            'stability_zone' => $request->stability_zone,
            'website' => $request->website,
            'product_class' => $request->product_class,
            'logo' => $newFileName,
        ]);

        return redirect()->route('factories.index');
    }

    public function show($id)
    {
        $factory = Factory::with('country','products.fields')->find($id);
        
        $listFields = $this->getListFieldsFromProducts($factory->products);

        $this->loadListFieldValues($listFields);
        
        return view('factories.show', compact('factory'));
    }
}
