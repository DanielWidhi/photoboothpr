<?php
include 'koneksi.php';
include 'auth.php';

// Require authentication
requireAdminAuth();

// Handle delete request
if (isset($_POST['delete_ul_id'])) {
    $ul_id = intval($_POST['delete_ul_id']); // Sanitize input

    try {
        // Start transaction
        $koneksi->begin_transaction();

        // Get photos to delete
        $photoQuery = $koneksi->prepare("SELECT pl_path FROM photo_list WHERE ul_id = ?");
        $photoQuery->bind_param("i", $ul_id);
        $photoQuery->execute();
        $result = $photoQuery->get_result();

        // Delete physical files
        while ($photo = $result->fetch_assoc()) {
            $filePath = $photo['pl_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete from photo_list
        $deletePhotosQuery = $koneksi->prepare("DELETE FROM photo_list WHERE ul_id = ?");
        $deletePhotosQuery->bind_param("i", $ul_id);
        $deletePhotosQuery->execute();

        // Delete from undangan_list
        $deleteUndanganQuery = $koneksi->prepare("DELETE FROM undangan_list WHERE ul_id = ?");
        $deleteUndanganQuery->bind_param("i", $ul_id);
        $deleteUndanganQuery->execute();

        // Commit transaction
        $koneksi->commit();

        header("Location: galery.php?message=deleted");
        exit();

    } catch (Exception $e) {
        // Rollback on error
        $koneksi->rollback();
        header("Location: galery.php?message=error");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDPARTSTUDIO - Admin Gallery</title>
    <link rel="icon" type="image/png" href="uploads/Logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Add SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
        }
        .gallery-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .logo-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .heart {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 30px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Responsive Header with Glass Effect -->
    <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <div class="flex items-center space-x-8">
                    <img src="uploads/ukm.png" alt="UKM Logo" class="h-10 md:h-14 logo-animation">
                    <img src="uploads/Logo1b.png" alt="Secondary Logo" class="h-10 md:h-14 logo-animation">
                </div>
                <div class="flex items-center space-x-4">
                    <a href="https://qrbooth.gdpartstudio.my.id/photoboothpr/photoboothpr/form" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Add Photos
                    </a>
                    <a href="https://qrbooth.gdpartstudio.my.id/photoboothpr/photoboothpr/showqr" class="bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-qrcode mr-2"></i>Show QR
                    </a>
                    <a href="https://qrbooth.gdpartstudio.my.id" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed header -->
    <div class="h-24 md:h-32"></div>

    <!-- SweetAlert Notifications -->
    <?php if (isset($_GET['message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($_GET['message'] == 'deleted'): ?>
                Swal.fire({
                    title: 'Success!',
                    text: 'Record deleted successfully!',
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    // Remove message parameter from URL after showing alert
                    const url = new URL(window.location.href);
                    url.searchParams.delete('message');
                    window.history.replaceState({}, '', url);
                });
            <?php elseif ($_GET['message'] == 'error'): ?>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete record. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    // Remove message parameter from URL after showing alert
                    const url = new URL(window.location.href);
                    url.searchParams.delete('message');
                    window.history.replaceState({}, '', url);
                });
            <?php endif;?>
        });
    </script>
    <?php endif;?>

    <div class="container mx-auto px-4 mt-8">
        <?php
$query = $koneksi->query("SELECT ul.ul_id, ul.ul_name, GROUP_CONCAT(pl.pl_path SEPARATOR ',') AS photo_paths
                          FROM photo_list pl
                          LEFT JOIN undangan_list ul ON pl.ul_id = ul.ul_id
                          GROUP BY ul.ul_id, ul.ul_name
                          ORDER BY ul.ul_name ASC");

if ($query->num_rows == 0) {
    ?>
            <div class="flex flex-col items-center justify-center min-h-[60vh] bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-12 transform hover:scale-105 transition-all duration-500">
                <img src="uploads/Logo1b.png" alt="PR Wedding" class="w-56 h-56 mb-8 animate-bounce">
                <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 text-center">No photos available</h1>
                <p class="mt-6 text-2xl text-gray-600 text-center">Start adding your amazing photos!</p>
            </div>
            <?php
} else {
    while ($row = $query->fetch_assoc()) {
        $photos = explode(',', $row['photo_paths']);
        ?>
                <div class="mb-12 gallery-container rounded-3xl p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">
                            <?php echo htmlspecialchars($row['ul_name']); ?>
                        </h2>
                        <form method="POST" onsubmit="return confirmDelete(event)">
                            <input type="hidden" name="delete_ul_id" value="<?php echo htmlspecialchars($row['ul_id']); ?>">
                            <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-3 rounded-full hover:from-red-600 hover:to-pink-600 transition duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-trash-alt mr-2"></i> Delete Group
                            </button>
                        </form>
                    </div>
                    <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($photos as $photo_path) {
            $extension = strtolower(pathinfo($photo_path, PATHINFO_EXTENSION));
            ?>
                            <div class="card-hover bg-white/90 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden">
                                <div class="aspect-square overflow-hidden">
                                    <?php if ($extension == 'mp4' || $extension == 'avi'): ?>
                                        <video class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" controls>
                                            <source src="<?php echo htmlspecialchars($photo_path); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Photo"
                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                    <?php endif;?>
                                </div>
                                <div class="p-6">
                                    <div class="flex justify-end">
                                        <a href="<?php echo htmlspecialchars($photo_path); ?>" download
                                           class="bg-gradient-to-r from-blue-400 to-purple-500 text-white px-6 py-3 rounded-full hover:from-blue-500 hover:to-purple-600 transition duration-300 transform hover:scale-105 shadow-lg">
                                            <i class="fas fa-download mr-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
}?>
                    </div>
                </div>
                <?php
}
}
?>
    </div>
    <div class="h-16 md:h-12"></div>

    <!-- Footer with Glass Effect -->
    <footer class="mt-12 py-8 bg-white/80 backdrop-blur-md shadow-inner">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-8 mb-6">
                <a href="https://instagram.com/gdpartstudio" target="_blank"
                   class="text-3xl text-pink-500 hover:text-pink-600 transition-all duration-300 transform hover:scale-125">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/6281339172556" target="_blank"
                   class="text-3xl text-green-500 hover:text-green-600 transition-all duration-300 transform hover:scale-125">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            <p class="text-gray-700 text-lg mb-2">Made with <span class="text-red-500 heart text-2xl">❤</span> by GDPARTSTUDIO</p>
        </div>
    </footer>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! All photos in this group will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
            return false;
        }
    </script>
</body>
</html>
