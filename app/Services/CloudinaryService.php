<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    /**
     * Upload file to Cloudinary
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        try {
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'logbook-pkl/' . $folder,
                'resource_type' => 'auto',
            ]);
            
            return $result->getSecurePath();
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload base64 image to Cloudinary
     */
    public function uploadBase64(string $base64Image, string $folder = 'uploads', ?string $publicId = null): ?string
    {
        try {
            // Remove data URI prefix if present
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            
            $options = [
                'folder' => 'logbook-pkl/' . $folder,
                'resource_type' => 'image',
            ];
            
            if ($publicId) {
                $options['public_id'] = $publicId;
            }
            
            $result = Cloudinary::upload('data:image/jpeg;base64,' . $imageData, $options);
            
            return $result->getSecurePath();
        } catch (\Exception $e) {
            \Log::error('Cloudinary base64 upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file from Cloudinary by URL
     */
    public function delete(?string $url): bool
    {
        if (!$url || !str_contains($url, 'cloudinary')) {
            return false;
        }

        try {
            // Extract public_id from URL
            preg_match('/\/v\d+\/(.+)\.\w+$/', $url, $matches);
            
            if (isset($matches[1])) {
                Cloudinary::destroy($matches[1]);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
