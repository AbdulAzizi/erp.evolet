<?php

namespace App\Http\Controllers;

use App\Division;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function create(Request $request)
    {
        $division = Division::find($request->divisionId);

        $tag = Tag::create([
            'name' => $request->name
        ]);

        $division->tags()->attach($tag);

        return $tag;
    }

    public function delete(Request $request)
    {
        $tag = Tag::find($request->id);

        $tag->delete();
    }
}
