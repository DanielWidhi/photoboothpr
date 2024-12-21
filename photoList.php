<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo and Video Gallery with Download Buttons</title>
	<link rel="icon" type="image/png" href="uploads/Logo1.png">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 p-8">
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
    <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-center max-w-7xl mx-auto">
      <?php if (empty($photos)) {?>
        <div class="text-center col-span-full">
          <img src="uploads/Logo1b.png" alt="PR Wedding" class="mx-auto mb-6 w-64 h-64 animate-pulse">
          <h1 class="text-2xl font-bold text-gray-800">Tidak ada foto atau video yang tersedia</h1>
        </div>
      <?php } else {?>
        <?php foreach ($photos as $photo): ?>
          <div class="flex flex-col items-center p-6 bg-white shadow-xl rounded-xl transform transition duration-300 hover:scale-105">
            <?php
$extension = strtolower(pathinfo($photo['path'], PATHINFO_EXTENSION));
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'): ?>
              <div class="w-full h-80 mb-6 overflow-hidden rounded-lg">
                <img src="<?php echo htmlspecialchars($photo['path']); ?>" alt="Photo"
                     class="w-full h-full object-cover hover:opacity-90 transition duration-300">
              </div>
              <a href="<?php echo htmlspecialchars($photo['path']); ?>"
                 download="<?php echo htmlspecialchars($photo['name']); ?>"
                 class="px-6 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transform transition duration-300 hover:-translate-y-1 shadow-lg">
                <span class="mr-2">⬇️</span>Download
              </a>
            <?php elseif ($extension == 'mp4' || $extension == 'avi'): ?>
              <div class="w-full h-80 mb-6 overflow-hidden rounded-lg">
                <video autoplay loop muted playsinline controls class="w-full h-full object-cover">
                  <source src="<?php echo htmlspecialchars($photo['path']); ?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <a href="<?php echo htmlspecialchars($photo['path']); ?>"
                 download="<?php echo htmlspecialchars($photo['name']); ?>"
                 class="px-6 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transform transition duration-300 hover:-translate-y-1 shadow-lg">
                <span class="mr-2">⬇️</span>Download
              </a>
            <?php endif;?>
          </div>
        <?php endforeach;?>
      <?php }?>
    </div>
</body>
</html>