<?php

namespace App\Http\BusinessLogic\Methods\Utilites;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait LogicUtilities
{
    protected function uploadFiles($path, array $uploadedFiles = null)
    {
        if (is_null($uploadedFiles))
            return [];

        $files = [];
        foreach ($uploadedFiles as $key => $file) {
            $ext = $file->getClientOriginalExtension();

            $fileName = Str::uuid() . "." . $ext;

            $file->storeAs("$path/$fileName");
            $files[] = $fileName;
        }

        return $files;
    }

    /**
     * @throws ValidationException
     */
    protected function uploadPhotos($path, array $uploadedFiles = null)
    {
        return $this->uploadFiles($path, $uploadedFiles);
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
