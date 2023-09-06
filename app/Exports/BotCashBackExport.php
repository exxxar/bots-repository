<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BotCashBackExport implements FromView
{
    private $history;

    public function __construct($data)
    {

        $this->history = $data;
    }

    public function view(): View
    {
        return view('exports.cashback', [
            "history" => $this->history
        ]);
    }
}
