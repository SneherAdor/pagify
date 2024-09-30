<?php

namespace Millat\Pagify\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadService
{
    /**
     * Validate and upload files.
     *
     * @param Request $request
     * @return array
     */
    public function uploadFiles(Request $request)
    {
        $data = $request->all();
        $extensions = $data['extensions'] ?? '.*';

        // Validate files if specific extensions are provided
        if ($extensions !== '*') {
            $validator = Validator::make($data, [
                'files.*' => 'mimes:' . $extensions,
            ]);

            if ($validator->fails()) {
                return [
                    'status'  => 'error',
                    'message' => $validator->errors()->all(),
                ];
            }
        }

        if (!empty($data['files'])) {
            return [
                'status' => 'success',
                'files'  => $this->processFiles($data['files']),
            ];
        }

        return [
            'status'  => 'error',
            'message' => __('No files uploaded'),
        ];
    }

    /**
     * Process and upload each file.
     *
     * @param array $files
     * @return array
     */
    private function processFiles(array $files)
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if (is_file($file)) {
                $uploadedFiles[] = $this->handleFileUpload($file);
            }
        }

        return $uploadedFiles;
    }

    /**
     * Handle individual file upload.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return array
     */
    private function handleFileUpload($file)
    {
        // Generate file information
        $extension  = $file->guessExtension();
        $mimeType   = $file->getMimeType();
        $orgName    = $file->getClientOriginalName();
        $fileName   = $this->generateFileName($orgName);
        $filePath   = Storage::disk('local')->putFileAs('public/uploads/pagify', $file, $fileName);
        $fileSize   = $file->getSize();

        // Set default type and thumbnail
        $type       = substr($mimeType, 0, 5) === 'image' ? 'image' : 'file';
        $thumbnail  = $type === 'image' ? asset($filePath) : asset(config('pagify.assets_path') . '/images/file-preview.png');

        return [
            'type'      => $type,
            'name'      => $orgName,
            'path'      => $filePath,
            'mime'      => $extension,
            'size'      => $fileSize,
            'thumbnail' => $thumbnail,
        ];
    }

    /**
     * Generate a unique file name based on the original name.
     *
     * @param  string  $originalName
     * @return string
     */
    private function generateFileName($originalName)
    {
        return rand(1, 9999) . now()->format('m-d-Y_hia_') . $originalName;
    }
}
