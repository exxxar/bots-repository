<?php

namespace App\Exports;

use App\Models\Table;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BookingsExport implements  FromView
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = Carbon::parse($start);
        $this->end   = Carbon::parse($end);
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $bookings = Table::query()
            ->whereBetween('booked_date_at', [$this->start, $this->end])
            ->where('booked_date_at', '>=', now()->toDateString())
            ->orderBy('booked_date_at')
            ->get();

        return view('exports.bookings', [
            'bookings' => $bookings
        ]);
    }
}
