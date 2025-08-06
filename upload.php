<?php
// Pastikan skrip hanya diakses via metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Metode tidak diizinkan.']);
    exit;
}

// 1. SERTAKAN KONEKSI DATABASE
include 'koneksi.php';

// 2. AMBIL DAN VALIDASI DATA NAMA
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if (empty($name)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Nama wajib diisi.']);
    exit;
}
if (empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
    exit;
}

// 3. BUAT DIREKTORI UNIK UNTUK UPLOAD
$uploadDir = 'uploads/' . preg_replace("/[^a-zA-Z0-9]/", "_", $name) . "_" . time() . "/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

// GUNAKAN TRANSAKSI DATABASE UNTUK KEAMANAN DATA
$koneksi->begin_transaction();

try {
    // 4. SIMPAN DATA UTAMA (NAMA) KE TABEL 'undangan_list'
    $stmt_undangan = $koneksi->prepare("INSERT INTO undangan_list (ul_name, ul_date) VALUES (?, NOW())");
    $stmt_undangan->bind_param("s", $name);
    $stmt_undangan->execute();
    
    // Ambil ID dari undangan yang baru saja dibuat
    $undangan_id = $koneksi->insert_id;
    $stmt_undangan->close();

    // 5. PROSES SETIAP FILE YANG DIUNGGAH
    $files = $_FILES['file'];
    $total_files = count($files['name']);

    for ($i = 0; $i < $total_files; $i++) {
        // Buat nama file yang lebih aman dan unik
        $originalFileName = basename($files['name'][$i]);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $safeFileName = uniqid('file_', true) . '.' . $fileExtension;
        
        $targetPath = $uploadDir . $safeFileName;

        // Pindahkan file
        if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
            // 6. SIMPAN PATH FILE KE TABEL 'undangan_files'
            // (Lihat catatan tentang tabel ini di bawah)
            $stmt_file = $koneksi->prepare("INSERT INTO undangan_files (ul_id, file_path) VALUES (?, ?)");
            $stmt_file->bind_param("is", $undangan_id, $targetPath);
            $stmt_file->execute();
            $stmt_file->close();
        } else {
            // Jika satu file saja gagal, batalkan semuanya
            throw new Exception("Gagal mengunggah file: " . $originalFileName);
        }
    }

    // Jika semua proses di atas berhasil, simpan permanen ke database
    $koneksi->commit();
    
    // 7. KIRIM RESPONS SUKSES
    echo json_encode(['success' => true, 'message' => 'Data dan file berhasil diunggah!']);

} catch (Exception $e) {
    // Jika ada error di tengah jalan, batalkan semua perubahan di database
    $koneksi->rollback();

    // Hapus juga folder yang sudah terlanjur dibuat
    if (is_dir($uploadDir)) {
        // Fungsi untuk menghapus folder dan isinya
        function deleteDir($dirPath) {
            if (!is_dir($dirPath)) { return; }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) { deleteDir($file); } else { unlink($file); }
            }
            rmdir($dirPath);
        }
        deleteDir($uploadDir);
    }
    
    // 8. KIRIM RESPONS GAGAL
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}

$koneksi->close();
?>