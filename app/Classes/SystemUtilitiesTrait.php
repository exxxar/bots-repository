<?php

namespace App\Classes;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait SystemUtilitiesTrait
{

    public function file(Request $request, $slug, $param = 'file')
    {
        if ($request->hasFile($param)) {
            $file = $request->file($param);
            $ext = $file->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            $file->storeAs("/public/companies/$slug/$imageName");
            return $imageName;
        }

        return null;
    }
}
