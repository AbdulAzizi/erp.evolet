<?php

namespace App\Http\Controllers;

use App\File;
use App\Field;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with('fields')->get();

        return view('admin.files.index', compact('files'));
    }

    public function create(Request $request)
    {
        $file = File::create(['name' => $request->name]);

        $fields = Field::whereIn('id', $request->fields)->get();

        $file->fields()->attach($fields);

        $files = File::with('fields')->get();

        return $files;
    }

    public function update(Request $request)
    {
        $file = File::find($request->id);

        $file->name = $request->name;

        $file->save();

        return 'success';
    }

    public function delete(Request $request)
    {
        $file = File::find($request->id);
        
        $file->delete();
        
        $files = File::with('fields')->get();

        return $files; 
    }

    public function deleteFieldFromFile(Request $request)
    {
        $file = File::find($request->fileId);

        $field = $file->fields->where('id', $request->id)->first();

        $file->fields()->detach($field);

        return File::find($request->fieldId);

    }

    public function attachFields(Request $request)
    {
        $file = File::find($request->id);

        $fields = Field::whereIn('id', $request->fields)->get();

        $file->fields()->attach($fields);

        $files = File::with('fields')->get();

        return $fields;

    }
}
