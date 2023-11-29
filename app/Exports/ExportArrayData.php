<?php

namespace App\Exports;

use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportArrayData implements FromArray
{

    public $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array():array
    {
        return $this->collection;

    }
}
