<?php

namespace App\Http\Controllers;

use App\Models\SharedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|file|max:51200', // Max file size: 10MB
            'expires_in' => 'required|integer|min:1',
            'download_limit' => 'nullable|integer|min:1',
        ]);

        $uploadedFile = $request->file('file');
        $uuid = (string) Str::uuid();

        $filePath = $uploadedFile->store('uplads', 'local');
        $expiresAt = now()->addMinutes((int) $request->expires_in);
        $sharedFile = SharedFile::create([
            'uuid' => $uuid,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType(),
            'expires_at' => $expiresAt,
            'download_limit' => $request->download_limit,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'uuid' => $sharedFile->uuid,
            'share_url' => url('/f/{$sharedFile->uuid}'),
        ], 201);
    }
}
