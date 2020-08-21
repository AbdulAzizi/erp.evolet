<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Imports\EntriesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EntryController extends Controller
{
    public function index()
    {
        $entries = Entry::with('user')->get();
        return view('hr.entries', compact('entries'));
    }

    public function store(Request $request)
    {
        Excel::import(new EntriesImport, request()->file('file'));

        return redirect()->route('entries.index');
    }

    public function update(Request $request, $id)
    {
        $entry = Entry::find($id);
        $entry->comment = $request->comment;
        $entry->save();

        return $entry;
    }

}
