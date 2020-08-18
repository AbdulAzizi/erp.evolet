<?php

namespace App\Imports;

use App\Entry;
use App\User;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class EntriesImport implements OnEachRow
{
    private $users;

    public function __construct()
    {
        $this->users = User::alone()->get();
    }
    

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        $result = (explode(' ', $row[2]));

        if (count($result) == 2) {

            $name = $result[0];
            $surname = $result[1];

            $user = $this->users->where('name', $name)
                ->where('surname', $surname)
                ->first();

            if ($user) {

                Entry::firstOrCreate([
                    'user_id' => $user->id,
                    'date' => $row[3],
                    'sign_in' => $row[5] == 'Not Sign in' ? null : $row[5],
                    'sign_out' => $row[6] == 'Not Sign out' ? null : $row[6],
                ]);
            }
        }
    }
}
