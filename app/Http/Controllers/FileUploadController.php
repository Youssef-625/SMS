<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
            'folder' => 'nullable|string|max:255',
        ]);

        $folder = $request->input('folder', 'uploads');
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($folder, $fileName, 'public');

        return $this->success([
            'file_path' => $filePath,
            'file_url' => Storage::url($filePath),
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function uploadMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|max:10240', // max 10MB per file
            'folder' => 'nullable|string|max:255',
        ]);

        $folder = $request->input('folder', 'uploads');
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs($folder, $fileName, 'public');

            $uploadedFiles[] = [
                'file_path' => $filePath,
                'file_url' => Storage::url($filePath),
                'file_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ];
        }

        return $this->success([
            'files' => $uploadedFiles,
            'count' => count($uploadedFiles),
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string',
        ]);

        $filePath = $request->input('file_path');

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return $this->success(['message' => 'File deleted successfully']);
        }

        return $this->error('File not found', 404);
    }
}
