<?php

namespace App\Http\BusinessLogic\Methods\Utilites;

use Illuminate\Support\Str;

trait LogicUtilities
{
    protected function uploadPhotos($path, array $uploadedFiles = null): array
    {
        if (is_null($uploadedFiles))
            return [];

        $photos = [];
        foreach ($uploadedFiles as $key => $file) {
            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $file->storeAs("$path/$imageName");
            $photos[] = $imageName;
        }

        return $photos;
    }

    protected function uploadPhoto($path, $uploadedFile = null): ?string
    {
        if (is_null($uploadedFile))
            return null;

        $ext = $uploadedFile->getClientOriginalExtension();

        $imageName = Str::uuid() . "." . $ext;

        $uploadedFile->storeAs("$path/$imageName");

        return $imageName;
    }

    protected function recursiveMenuFix($menu): array
    {
        $menu = (array)$menu;
        if (isset($menu["menu"])) {
            return $this->recursiveMenuFix($menu["menu"]);
        }

        return $menu;
    }

}
