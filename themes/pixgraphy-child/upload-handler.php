<?php
// Define the directory where uploaded files will be stored
$uploadDirectory = '/path/to/upload/directory/';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["art_image"])) {
    $artTitle = $_POST["art_title"];
    $uploadedFile = $_FILES["art_image"];

    // Validate file type and size 
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB

    if (in_array($uploadedFile['type'], $allowedTypes) && $uploadedFile['size'] <= $maxFileSize) {
        // Generate a unique filename
        $filename = uniqid() . '_' . $uploadedFile['name'];

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $filename)) {
            // File upload successful, do further processing 
            echo 'File uploaded successfully.';
        } else {
            echo 'Error uploading file.';
        }
    } else {
        echo 'Invalid file type or size.';
    }
} else {
    echo 'Invalid request.';
}
?>
