<?php
function fileUpload($file, $prefix = '') {
    $uploadDir = __DIR__ . '/../pictures/';

    // Check Folder
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Clean FileName
    $fileName = basename($file['name']);
    $fileName = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $fileName);

    $targetPath = $uploadDir . $fileName;

    // Upload Image
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [$fileName, "Upload successful"];
    } else {
        return [null, "Upload failed"];
    }
}

// Delete old Pictures
function deleteOldHeroImage($oldImage) {
    $defaultImages = ['hero_1.jpg', 'hero_2.jpg'];
    if (empty($oldImage) || in_array($oldImage, $defaultImages)) return;
    $oldFile = __DIR__ . '/../pictures/' . $oldImage;
    if (file_exists($oldFile)) unlink($oldFile);
}
?>
