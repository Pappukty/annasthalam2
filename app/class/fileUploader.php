<?php
class ImageUploader {
    private $pdo;
    private $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']; // Allowed MIME types
    private $maxsize = 5097152; // 5MB limit
    private $uploadPath = './uploads/'; // Default upload directory
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function upload($file, $uploadDir) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return "File upload error!";
        }
        
        // Validate file size
        if ($file['size'] > $this->maxsize || $file['size'] == 0) {
            return "Image size too large or empty file.";
        }
        
        // Validate file type
        if (!in_array($file['type'], $this->allowed)) {
            return "Invalid file type. Only JPEG, JPG, GIF, and PNG are allowed.";
        }
        
        // Generate a unique file name
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filenameNew = time() . '.' . $ext;
        
        // Ensure upload directory exists
        $targetDir = rtrim($this->uploadPath, '/') . '/' . trim($uploadDir, '/') . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetDir . $filenameNew)) {
            return $filenameNew; // Return the new filename on success
        }
        
        return "Failed to move uploaded file.";
    }
}
