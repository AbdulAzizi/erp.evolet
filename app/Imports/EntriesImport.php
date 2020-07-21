<?php

namespace App\Imports;

use App\Entry;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class EntriesImport implements ToModel
{
    private $users;

    public function __construct()
    {
        $this->users = User::alone()->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $result = (explode(' ', $row[2]));

        if (count($result) == 2) {

            $name = $result[0];
            $surname = $result[1];

            $user = $this->users->where('name', $name)
                ->where('surname', $surname)
                ->first();

            if ($user) {

                return new Entry([
                    'user_id' => $user->id,
                    'date' => $row[3],
                    'sign_in' => $row[5] == 'Not Sign in' ? null : $row[5],
                    'sign_out' => $row[6] == 'Not Sign out' ? null : $row[6],
                ]);
            }
        }

        return;
    }
}
