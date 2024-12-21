<?php
include 'koneksi.php';
include 'auth.php';

// Require authentication
requireAdminAuth();

// Check if ul_id is set in POST request
if (isset($_POST['ul_id'])) {
    $ul_id = intval($_POST['ul_id']); // Sanitize input

    // Delete photos associated with this ul_id
    $photoQuery = $koneksi->query("SELECT pl_path FROM photo_list WHERE ul_id = $ul_id");
    while ($photo = $photoQuery->fetch_assoc()) {
        $filePath = $photo['pl_path'];
        if (file_exists($filePath)) {
            unlink($filePath); // Delete file from server
        }
    }

    // Delete records from photo_list
    $koneksi->query("DELETE FROM photo_list WHERE ul_id = $ul_id");

    // Delete record from undangan_list
    $koneksi->query("DELETE FROM undangan_list WHERE ul_id = $ul_id");

    // Redirect back to the gallery page with success message
    header("Location: galery.php?message=deleted");
    exit();
} else {
    // Redirect back to the gallery page with error message
    header("Location: galery.php?message=error");
    exit();
}
