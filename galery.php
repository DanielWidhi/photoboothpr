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

            header("Location: galery?message=deleted");
            exit();

        } catch (Exception $e) {
            // Rollback on error
            $koneksi->rollback();
            header("Location: galery?message=error");
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

        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            z-index: 50;
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }
            .mobile-nav {
                display: block;
            }
            .nav-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            .gallery-grid {
                grid-template-columns: repeat(1, 1fr);
            }
            .btn-text {
                display: none;
            }
            .mobile-nav-items {
                display: flex;
                justify-content: space-around;
                padding: 1rem;
            }
            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: #666;
                font-size: 0.8rem;
            }
            .mobile-nav-item i {
                font-size: 1.5rem;
                margin-bottom: 0.25rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body class="min-h-screen pb-16 md:pb-0">
    <!-- Desktop Navigation -->
    <nav class="desktop-nav bg-white/80 backdrop-blur-md shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4 h-20">
                <div class="flex items-center space-x-8">
                    <img src="uploads/ukm.png" alt="UKM Logo" class="h-14 logo-animation">
                    <img src="uploads/Logo1b.png" alt="Secondary Logo" class="h-14 logo-animation">
                </div>
                <div class="flex items-center space-x-4">
                    <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/form" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Add Photos
                    </a>
                    <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/showqr" class="bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-qrcode mr-2"></i>Show QR
                    </a>
                    <a href="https://gdpbooth.gdpartstudio.my.id" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-6 py-2 rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav shadow-lg">
        <div class="mobile-nav-items">
            <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/form" class="mobile-nav-item">
                <i class="fas fa-plus"></i>
                <span>Add</span>
            </a>
            <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/showqr" class="mobile-nav-item">
                <i class="fas fa-qrcode"></i>
                <span>QR</span>
            </a>
            <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/galery.php" class="mobile-nav-item">
                <i class="fas fa-images"></i>
                <span>Gallery</span>
            </a>
            <a href="https://gdpbooth.gdpartstudio.my.id" class="mobile-nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <!-- Rest of the content remains the same -->
    <!-- Spacer for fixed header -->
    <div class="h-32"></div>

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
                    const url = new URL(window.location.href);
                    url.searchParams.delete('message');
                    window.history.replaceState({}, '', url);
                });
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>

    <div class="container mx-auto px-4 mt-8">
        <?php
            $query = $koneksi->query("SELECT ul.ul_id, ul.ul_name, GROUP_CONCAT(pl.pl_path SEPARATOR ',') AS photo_paths
                          FROM photo_list pl
                          LEFT JOIN undangan_list ul ON pl.ul_id = ul.ul_id
                          GROUP BY ul.ul_id, ul.ul_name
                          ORDER BY ul.ul_name ASC");

            if ($query->num_rows == 0) {
            ?>
            <div class="flex flex-col items-center justify-center min-h-[60vh] bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-6 md:p-12 transform hover:scale-105 transition-all duration-500">
                <img src="uploads/Logo1b.png" alt="PR Wedding" class="w-32 md:w-56 h-32 md:h-56 mb-8 animate-bounce">
                <h1 class="text-3xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 text-center">No photos available</h1>
                <p class="mt-4 md:mt-6 text-xl md:text-2xl text-gray-600 text-center">Start adding your amazing photos!</p>
            </div>
            <?php
                } else {
                    while ($row = $query->fetch_assoc()) {
                        $photos = explode(',', $row['photo_paths']);
                    ?>
                <div class="mb-8 md:mb-12 gallery-container rounded-3xl p-4 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h2 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500 text-center md:text-left">
                            <?php echo htmlspecialchars($row['ul_name']); ?>
                        </h2>
                        <form method="POST" onsubmit="return confirmDelete(event)" class="w-full md:w-auto">
                            <input type="hidden" name="delete_ul_id" value="<?php echo htmlspecialchars($row['ul_id']); ?>">
                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-3 rounded-full hover:from-red-600 hover:to-pink-600 transition duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-trash-alt mr-2"></i> Delete Group
                            </button>
                        </form>
                    </div>
                    <div class="grid gap-4 md:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
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
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 md:p-6">
                                    <div class="flex justify-center md:justify-end">
                                        <a href="<?php echo htmlspecialchars($photo_path); ?>" download
                                           class="w-full md:w-auto bg-gradient-to-r from-blue-400 to-purple-500 text-white px-6 py-3 rounded-full hover:from-blue-500 hover:to-purple-600 transition duration-300 transform hover:scale-105 shadow-lg text-center">
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
    <div class="h-16"></div>

    <!-- Footer with Glass Effect -->
    <footer class="mt-12 py-6 md:py-8 bg-white/80 backdrop-blur-md shadow-inner">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 md:space-x-8 mb-4 md:mb-6">
                <a href="https://instagram.com/gdpartstudio" target="_blank"
                   class="text-2xl md:text-3xl text-pink-500 hover:text-pink-600 transition-all duration-300 transform hover:scale-125">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/6281339172556" target="_blank"
                   class="text-2xl md:text-3xl text-green-500 hover:text-green-600 transition-all duration-300 transform hover:scale-125">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            <p class="text-base md:text-lg text-gray-700 mb-2">Made with <span class="text-red-500 heart text-xl md:text-2xl">❤</span> by GDPARTSTUDIO</p>
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
