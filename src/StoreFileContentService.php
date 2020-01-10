<?php

namespace ByteDigital123\StoreFileContentService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StoreFileContentService
{
    protected $file;
    protected $sanitized_file_name;
    private $fileSystem;

    public function __construct($file, $fileSystem)
    {
        $this->file = $file;
        $this->fileSystem = $fileSystem;
    }

    public function handle()
    {
        $this->sanitized_file_name = (new SanitizeFileName())->handle($this->getFileName()) . '_' . time() . '.' . $this->getFileExtension();
        $this->storeFile();

        return [
            'bytes' => $this->file->getClientSize(),
            'format' => $this->file->getMimeType(),
            'original_filename' => $this->sanitized_file_name,
            'url' => Storage::disk($this->fileSystem)->url($this->sanitized_file_name),
            'secure_url' => Storage::disk($this->fileSystem)->url($this->sanitized_file_name),
            'created_at' => $this->getCurrentTimestamp(),
            'updated_at' => $this->getCurrentTimestamp()
        ];
    }

    public function storeFile()
    {
        try {
            Storage::disk($this->fileSystem)->put($this->sanitized_file_name, file_get_contents($this->file));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function getFileExtension()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
    }

    public function getFileName()
    {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function getCurrentTimestamp()
    {
        return Carbon::now();
    }
}

