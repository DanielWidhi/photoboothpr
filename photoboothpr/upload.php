<?php
$uploadDir = 'uploads/'.date("U")."/";

$name = $_POST["name"];
if($name!=""){
	$uploadDir = 'uploads/'.$name."_".date("U")."/";
}

if(!is_dir($uploadDir)){
	mkdir($uploadDir, 0775, true);
}
$filePaths = [];  // Array to store uploaded file paths

// Check if files are uploaded and are an array
if (isset($_FILES['file']) && is_array($_FILES['file']['name'])) {
    // Multiple file uploads
    $files = $_FILES['file'];
    
    for ($i = 0; $i < count($files['name']); $i++) {
        $fileName = basename($files['name'][$i]);
        $uploadFile = $uploadDir . $fileName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($files['tmp_name'][$i], $uploadFile)) {
            $filePaths[] = $uploadFile;
        }
    }

    // Return the file paths as a JSON response
    echo json_encode(['filePaths' => $filePaths]);

} else if (isset($_FILES['file']['name']) && !is_array($_FILES['file']['name'])) {
    // Single file upload
    $fileName = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $filePaths[] = $uploadFile;
    }

    // Return the file paths as a JSON response
    echo json_encode(['filePaths' => $filePaths]);

} else {
    // No files uploaded
    echo json_encode(['error' => 'No files were uploaded.']);
}
?>
