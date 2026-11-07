<?php

class ImageUploader {

    private $allowedTypes = ["image/jpeg" => "jpg","image/png"  => "png","image/webp" => "webp"];
    private $maxSize = 2 * 1024 * 1024; // 2 MB
    public function upload($file, $folder) {

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return "Image upload failed";
        }

        if ($file['size'] > $this->maxSize) {
            return "Image size must be less than 2MB";
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!isset($this->allowedTypes[$mimeType])) {
            return "Invalid image type";
        }

        $extension = $this->allowedTypes[$mimeType];

        $fileName = bin2hex(random_bytes(16)) . "." . $extension;

        $uploadPath = __DIR__ . "/../uploads/" . $folder . "/";

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $destination = $uploadPath . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "Failed to save image";
        }

        return "uploads/" . $folder . "/" . $fileName;
    }
}