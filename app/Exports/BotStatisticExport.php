<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BotStatisticExport implements FromView
{
    private $statistic;

    public function __construct($data)
    {

        $this->statistic = $data;
    }

    public function view(): View
    {
        return view('exports.statistic', [
            "statistic" => $this->statistic
        ]);
    }

}
