<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileStorageHelper
{
    /**
     * @param type $name attribute name
     * @param array $data input data (ex. $request->all())
     * @param type $path the path in the storage where the file should be saved
     * @param type $previous previous attribute value
     * @param type $disc storage disc name
     * @throws \DomainException
     */
    public static function filterFileInput($name, array &$data, $path, $previous = null, $disc = 'public')
    {
        if (isset($data[$name.'_file'])) {
            $data[$name] = $data[$name.'_file']->storeAs(
                self::resolvePath($data[$name.'_file'], $path),
                self::resolveName($data[$name.'_file']),
                $disc
            );
        } elseif (isset($data[$name]) && ($data[$name] !== $previous) && Storage::disk($disc)->exists($data[$name])) {
            throw new \DomainException('Data access violation!');
        }
    }

    public static function resolvePath(UploadedFile $file, string $path): string
    {
        $time = time();
        $map = [
            '{d}' => date('d', $time),
            '{m}' => date('m', $time),
            '{Y}' => date('Y', $time),
        ];

        $md5 = md5($file->getClientOriginalName());
        for ($i = 0; $i < 6; $i++) {
            $map['{md5'.$i.'}'] = $md5[$i];
        }

        return strtr($path, $map);
    }

    public static function resolveName(UploadedFile $file): string
    {
        return time().'-'.\transliterator_transliterate('Latin-ASCII', transliterator_transliterate('Latin', $file->getClientOriginalName()));
    }
}

