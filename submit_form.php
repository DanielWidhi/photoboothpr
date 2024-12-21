<?php
include 'koneksi.php';
include 'plugins/phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $uploadedFiles = $_POST['uploadedFiles']; // Array of uploaded file paths

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name cannot be empty!']);
        exit; // Stop further execution
    }

    // Handle the form data (e.g., saving to database, etc.)
    // For demonstration, just returning a success response

    $koneksi->query("
		INSERT INTO undangan_list (
			ul_name, ul_date
		)
		VALUES (
			'" . $name . "', '" . date("Y-m-d H:i:s") . "'
		)
	");

    $last_id = $koneksi->insert_id;

    $directory_path = "";
    foreach ($uploadedFiles as $path) {
        $koneksi->query("
			INSERT INTO photo_list (
				ul_id, pl_path
			)
			VALUES (
				'" . $last_id . "', '" . $path . "'
			)
		");

        $directory_path = dirname($path);
    }

    if ($last_id != "") {
        $qr_path = "qrcodes/" . $name . "_qrcode_" . date("U") . ".png";

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];

        $qr_content = 'https://qrbooth.gdpartstudio.my.id/photoboothpr/photoboothpr/photoList?id=' . $last_id;
        // $qr_content = $protocol.$host.'/photoList?id='.$last_id;

        if (!file_exists($qr_path)) {
            QRcode::png($qr_content, $qr_path);

            $koneksi->query("
				UPDATE undangan_list SET
				ul_showqr='n'
			");

            $koneksi->query("
				UPDATE undangan_list SET
				ul_qr='" . $qr_path . "',
				ul_showqr='y'
				WHERE ul_id='" . $last_id . "'
			");
        }
    }

    echo json_encode(['success' => true, 'message' => 'Form submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
