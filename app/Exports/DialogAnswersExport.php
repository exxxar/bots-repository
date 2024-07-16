<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DialogAnswersExport implements FromView
{
    private $answers;
    private $user;

    public function __construct($data)
    {

        $this->answers = $data["answers"];
        $this->user = $data["user"];
    }

    public function view(): View
    {
        return view('exports.answers', [
            "answers" => $this->answers,
            "user" => $this->user
        ]);
    }
}
