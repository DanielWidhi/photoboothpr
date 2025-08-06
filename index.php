<!-- auth.php -->
<?php
session_start();

// Define a static password (for demo purposes only)
// $static_password = "GDPARTSTUDIO";
$static_password = "SERTIJAB25";
// $static_password = "g";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST['password'];

    if ($entered_password === $static_password) {
        // Password matches, set the authenticated session variable
        $_SESSION['authenticated'] = true;
        header("Location:showqr"); // Redirect to the authenticated page
        exit;
    } else {
        $error = "Invalid password. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="uploads/Logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xs">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="uploads/Logo1b.png" alt="Logo" class="w-24 h-24">
        </div>

        <!-- Authentication Form -->
        <form method="POST" action="">
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Log In
            </button>
        </form>
    </div>
</body>
</html>
