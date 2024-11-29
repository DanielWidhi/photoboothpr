<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery with Download Buttons</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
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
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 justify-center">
		<?php if (empty($photos)) {?>
			<div class="">
				<img src="uploads/Logo1b.png" alt="PR Wedding" class="mx-auto mb-4 w-48 h-48">
				<h1 class="justify-center">Tidak ada foto yang tersedia</h1>
			</div>
		<?php } else {?>
			<?php foreach ($photos as $photo): ?>
				<div class="flex flex-col items-center p-4 bg-white shadow-lg rounded-lg">
					<img src="<?php echo htmlspecialchars($photo['path']); ?>" alt="Photo Thumbnail" class="w-32 h-32 object-cover rounded-lg mb-4">
					<a href="<?php echo htmlspecialchars($photo['path']); ?>" download="<?php echo htmlspecialchars($photo['name']); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
						Download
					</a>
				</div>
			<?php endforeach;?>
		<?php }?>
    </div>
</body>
</html>
