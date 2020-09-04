<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Imports\EntriesImport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EntryController extends Controller
{
    public function index()
    {
        $users = User::with('entries')->get();
        return view('hr.entries', compact('users'));
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
