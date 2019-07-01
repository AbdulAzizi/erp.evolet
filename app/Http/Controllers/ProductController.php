<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use App\Product;

class ProductController extends Controller
{
    public function index()
    {
        $responsibilities = \Auth::user()->responsibilities;
        $form = null;

        foreach ($responsibilities as $responsibility) {
            if($responsibility->name == 'Портфолио Менеджер'){
                $form = Form::where('name','Форма ГО')->first();
                $form->load('fields');
            }
        }

        $products = Product::all();

        // return $form->fields;

        return view('products.index')->with(compact('form','products'));
    }
}
