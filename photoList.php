<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDPARTSTUDIO - Gallery</title>
    <link rel="icon" type="image/png" href="uploads/Logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f7f9 0%, #e9ecef 100%);
        }
        .gallery-container {
            background: linear-gradient(to bottom right, #ffffff, #f3f4f6);
        }
        .logo-animation {
            animation: float 3s ease-in-out infinite;
        }
        .logo-spin {
            animation: spin 10s linear infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .heart {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @media (max-width: 640px) {
            .header-logos img {
                height: 2rem;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Responsive Header with Logo -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center h-16 md:h-20">
                <!-- Centered logos with animations -->
                <div class="flex items-center space-x-8 header-logos">
                    <img src="uploads/ukm.png" alt="UKM Logo" class="h-8 md:h-12 logo-animation transition-all duration-300">
                    <img src="uploads/Logo1b.png" alt="Secondary Logo" class="h-8 md:h-12 logo-animation transition-all duration-300">
                </div>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed header -->
    <div class="h-24 md:h-32"></div>

    <?php
$photos = [];
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $q = $koneksi->query("
            SELECT * FROM photo_list WHERE ul_id='" . $id . "'
        ");
    while ($d = $q->fetch_array()) {
        $data = array();
        $data["path"] = $d["pl_path"];
        $data["name"] = basename($d["pl_path"]);
        array_push($photos, $data);
    }
}
?>

    <div class="container mx-auto px-4 mt-8">
        <?php if (empty($photos)) {?>
            <div class="flex flex-col items-center justify-center min-h-[60vh] bg-white rounded-3xl shadow-2xl p-12 transform hover:scale-105 transition-transform duration-300">
                <img src="uploads/Logo1b.png" alt="PR Wedding" class="w-56 h-56 mb-8 animate-bounce">
                <h1 class="text-4xl font-bold text-gray-800 text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-500">Tidak ada foto atau video yang tersedia</h1>
                <p class="mt-6 text-xl text-gray-600 text-center">Silakan kembali lagi nanti</p>
            </div>
        <?php } else {?>
            <div class="gallery-container grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 p-8 rounded-3xl shadow-2xl bg-white">
                <?php foreach ($photos as $photo): ?>
                    <div class="group relative overflow-hidden rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <?php
$extension = strtolower(pathinfo($photo['path'], PATHINFO_EXTENSION));
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'): ?>
                            <div class="aspect-square overflow-hidden">
                                <img src="<?php echo htmlspecialchars($photo['path']); ?>" alt="Photo"
                                     class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="<?php echo htmlspecialchars($photo['path']); ?>"
                                   download="<?php echo htmlspecialchars($photo['name']); ?>"
                                   class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full hover:from-blue-600 hover:to-purple-600 transition duration-300 transform hover:-translate-y-1">
                                    <span class="mr-2">⬇️</span>Download
                                </a>
                            </div>
                        <?php elseif ($extension == 'mp4' || $extension == 'avi'): ?>
                            <div class="aspect-square">
                                <video autoplay loop muted playsinline controls class="w-full h-full object-cover">
                                    <source src="<?php echo htmlspecialchars($photo['path']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                <a href="<?php echo htmlspecialchars($photo['path']); ?>"
                                   download="<?php echo htmlspecialchars($photo['name']); ?>"
                                   class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full hover:from-blue-600 hover:to-purple-600 transition duration-300 transform hover:-translate-y-1">
                                    <span class="mr-2">⬇️</span>Download
                                </a>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endforeach;?>
            </div>
        <?php }?>
    </div>

	<div class="h-16 md:h-12"></div>
    <!-- Footer -->
    <footer class="mt-12 py-8 bg-white shadow-inner">
        <div class="container mx-auto px-4 text-center">
            <!-- <img src="uploads/Logo1b.png" alt="Footer Logo" class="h-12 mx-auto mb-4"> -->
            <div class="flex justify-center space-x-6 mb-4">
                <a href="https://instagram.com/gdpartstudio" target="_blank" class="text-2xl text-pink-600 hover:text-pink-700 transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/6281339172556" target="_blank" class="text-2xl text-green-600 hover:text-green-700 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            <p class="text-gray-600 mb-2">Made with <span class="text-red-500 heart">❤</span> by GDPARTSTUDIO</p>
            <!-- <p class="text-gray-600">&copy; <?php echo date('Y'); ?> GDPARTSTUDIO. All rights reserved.</p> -->
        </div>
    </footer>
</body>
</html>