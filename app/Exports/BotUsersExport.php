<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BotUsersExport implements FromView
{
    private $users;

    public function __construct($data)
    {

        $this->users = $data;
    }

    public function view(): View
    {
        return view('exports.users', [
            "users" => $this->users
        ]);
    }
}
