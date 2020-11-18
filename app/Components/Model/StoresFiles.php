<?php

namespace App\Components\Model;

use App\Helpers\FileStorageHelper;

trait StoresFiles
{
    public static function storeFiles(&$data, $model = null)
    {
        foreach (self::getStoredFilesConfig() as $disc => $attributes) {
            foreach ($attributes as $attribute => $path) {
                FileStorageHelper::filterFileInput($attribute, $data, $path, $model ? $model->$attribute : null, $disc);
            }
        }
    }
}
