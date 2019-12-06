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
    }
}
