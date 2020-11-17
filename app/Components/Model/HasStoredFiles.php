<?php

namespace App\Components\Model;

trait HasStoredFiles
{
    use StoresFiles, CleanupsFiles;

    /**
     * The attributes that stores file paths in corresponding storages
     *
     * Example:
     * [
     *      'public' => ['image' => 'images/avatars'],
     *      'protected' => ['private_document' => 'users-private-data'],
     * ]
     *
     * @return array
     */
    public static function getStoredFilesConfig(): array
    {
        return self::$stored_files;
    }

    protected static function booted()
    {
        static::saved(function($model) {
            $model->cleanupDetachedFiles();
        });

        static::deleted(function($model) {
            $model->cleanupDetachedFiles();
        });
    }
}
