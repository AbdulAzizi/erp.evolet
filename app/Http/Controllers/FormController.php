<?php

namespace App\Http\Controllers;

use App\Field;
use App\Form;
use App\ProcessTask;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();

        return view('bp.index', compact('forms'));
    }

    public function show(Request $request)
    {
        $form = Form::with('fields')->find($request->id);

        return view('bp.show', compact('form'));
    }

    public function create(Request $request)
    {

        $form = Form::create([
            'name' => $request->name,
            'label' => $request->label
        ]);

        return $form;
    }

    public function delete(Request $request)
    {
        $form = Form::find($request->id);

        $form->delete();
    }

    public function getForms()
    {
        $forms = Form::all();

        return $forms;
    }

    public function deleteField(Request $request)
    {
        $form = Form::find($request->id);

        $form->fields()->detach($request->field);
        
    }
}
