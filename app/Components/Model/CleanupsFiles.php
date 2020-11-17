<?php

namespace App\Components\Model;

use Illuminate\Support\Facades\Storage;

trait CleanupsFiles
{
    protected function cleanupDetachedFiles()
    {
        foreach (self::getStoredFilesConfig() as $disc => $attributes) {
            foreach (array_keys($attributes) as $attribute) {
                // Delete original uploads if they were replaced
                if($this->wasChanged($attribute) && $this->getOriginal($attribute)) {
                    Storage::disk($disc)->delete($this->getOriginal($attribute));
                }

                if (!$this->exists) {
                    // If the model was not saved or was deleted

                    if ($this->photo) {
                        Storage::disk($disc)->delete($this->photo);
                    }
                }
            }
        }
    }
}
