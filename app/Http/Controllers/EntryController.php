<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Imports\EntriesImport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EntryController extends Controller
{
    public function index()
    {
        $users = $this->getEntries(new Request());

        return view('hr.entries', compact('users'));
    }

    public function getEntries(Request $request)
    {
        if( $request->month ){
            $date = Carbon::createFromFormat('Y-m', $request->month);
        }
        else
            $date = Carbon::now();
        
        return User::with(['entries' => function ($query) use ($date) {
            $query->whereMonth('date', $date->month)
                  ->whereYear('date', $date->year);
        }])->get();
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
