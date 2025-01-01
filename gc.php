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
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            max-width: 90%;
            max-height: 90vh;
        }
        .modal-content img, .modal-content video {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(1, 1fr);
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
<body class="min-h-screen">
    <!-- Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close">&times;</span>
        <div class="modal-content" onclick="event.stopPropagation()">
        </div>
    </div>

    <!-- Header -->
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
    <div class="h-32"></div>

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
                <p class="mt-4 md:mt-6 text-xl md:text-2xl text-gray-600 text-center">Check back later for amazing photos!</p>
            </div>
            <?php
} else {
    while ($row = $query->fetch_assoc()) {
        $photos = explode(',', $row['photo_paths']);
        ?>
                <div class="mb-8 md:mb-12 gallery-container rounded-3xl p-4 md:p-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500 text-center md:text-left mb-6">
                        <?php echo htmlspecialchars($row['ul_name']); ?>
                    </h2>
                    <div class="grid gap-4 md:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($photos as $photo_path) {
            $extension = strtolower(pathinfo($photo_path, PATHINFO_EXTENSION));
            ?>
                            <div class="card-hover bg-white/90 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden">
                                <div class="aspect-square overflow-hidden cursor-pointer" onclick="openModal('<?php echo htmlspecialchars($photo_path); ?>', '<?php echo $extension; ?>')">
                                    <?php if ($extension == 'mp4' || $extension == 'avi'): ?>
                                        <video class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                            <source src="<?php echo htmlspecialchars($photo_path); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Photo"
                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                    <?php endif;?>
                                </div>
                                <div class="p-4 md:p-6">
                                    <div class="flex justify-center">
                                        <a href="<?php echo htmlspecialchars($photo_path); ?>" download
                                           class="w-full bg-gradient-to-r from-blue-400 to-purple-500 text-white px-6 py-3 rounded-full hover:from-blue-500 hover:to-purple-600 transition duration-300 transform hover:scale-105 shadow-lg text-center">
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
        function openModal(path, extension) {
            const modal = document.getElementById('imageModal');
            const modalContent = modal.querySelector('.modal-content');
            modalContent.innerHTML = '';

            if (extension === 'mp4' || extension === 'avi') {
                const video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                const source = document.createElement('source');
                source.src = path;
                source.type = 'video/mp4';
                video.appendChild(source);
                modalContent.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src = path;
                modalContent.appendChild(img);
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            const modalContent = modal.querySelector('.modal-content');
            const video = modalContent.querySelector('video');
            if (video) {
                video.pause();
            }
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
