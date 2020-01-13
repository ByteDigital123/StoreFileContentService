<?php

namespace ByteDigital123\StoreFileContentService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StoreFileContentService
{
    protected $sanitized_file_name;

    public function __construct()
    {
        //
    }

    public function handle($file, $fileSystem)
    {
        $this->sanitized_file_name = (new SanitizeFileName())->handle($this->getFileName($file)) . '_' . time() . '.' . $this->getFileExtension($file);
        $this->storeFile($file, $fileSystem);

        return [
            'bytes' => $file->getClientSize(),
            'format' => $file->getMimeType(),
            'original_filename' => $this->sanitized_file_name,
            'url' => Storage::disk($fileSystem)->url($this->sanitized_file_name),
            'secure_url' => Storage::disk($fileSystem)->url($this->sanitized_file_name),
            'created_at' => $this->getCurrentTimestamp(),
            'updated_at' => $this->getCurrentTimestamp()
        ];
    }

    public function storeFile($file, $fileSystem)
    {
        try {
            Storage::disk($fileSystem)->put($this->sanitized_file_name, file_get_contents($file));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function getFileExtension($file)
    {
        return pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
    }

    public function getFileName($file)
    {
        return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function getCurrentTimestamp()
    {
        return Carbon::now();
    }
}

