<?php
    include 'koneksi.php';
    session_start();

    // Check if the user is authenticated
    if (! isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header("Location: login"); // Redirect to the login page if not authenticated
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Photo</title>
    <link rel="icon" type="image/png" href="uploads/Logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.0/dist/flowbite.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styling for the vertical divider */
        .divider {
            border: 0;
            border-left: 1px solid #e0e0e0;
            height: auto;
            margin: 0 1rem;
            padding: 0;
        }

        @media (max-width: 768px) {
            .divider {
                display: none;
            }
        }

        /* Custom style to prevent DataTables from overriding Tailwind styles */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            color: #4b5563;
            background-color: #ffffff;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #e5e7eb;
        }

        @media (max-width: 640px) {
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen m-0 flex flex-col">
<nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 fixed w-full top-0 z-50">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="uploads/GDPB.png" class="h-10" alt="Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GDPBooth</span>
        </a>
        <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
            <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700">
                <li>
                    <a href="https://gdpbooth.gdpartstudio.my.id/photoboothpr/showqr" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                        <i class="fas fa-qrcode mr-2"></i>Show QR
                    </a>
                </li>
                <li>
                    <a href="https://wa.me/6281339172556" target="_blank" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                        <i class="fas fa-images mr-2"></i>Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Add spacing after navbar -->
<div class="h-20"></div>

<div class="flex flex-col md:flex-row w-full flex-grow p-4 md:p-8 bg-white overflow-hidden">
    <!-- DataTable -->
    <div class="w-full md:w-1/2 flex flex-col p-4 overflow-auto">
        <!-- Reload Button -->
        <button id="reloadButton" class="mb-6 w-full mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-lg" onclick="reloadData();">Reload Data</button>
        <h2 class="text-xl font-semibold mb-4">Select Your Name</h2>
        <div class="flex-grow overflow-auto">
            <table id="dataTable" class="display w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q = $koneksi->query("SELECT * FROM undangan_list a ORDER BY ul_date DESC");
                        while ($d = $q->fetch_array()) {
                            echo "<tr><td class='px-6 py-4 whitespace-nowrap text-xl text-gray-500' onclick='getQR(" . $d["ul_id"] . ");'>" . $d["ul_name"] . "</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Divider -->
    <hr class="divider">

    <!-- QR Code -->
    <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-4 overflow-auto mt-8 md:mt-0">
        <div id="qr-container" class="text-center">
            <img src="uploads/Logo1b.png" alt="PR Wedding" class="mx-auto mb-4 w-32 md:w-48 h-32 md:h-48">
            <h1 class="text-xl md:text-2xl font-semibold mb-4">Select your name to get your photo</h1>
        </div>
    </div>
</div>

<!-- Add spacing before footer -->
<div class="h-20"></div>

<footer class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 shadow-inner mt-auto py-6">
    <div class="container mx-auto px-4 text-center">
        <div class="flex justify-center space-x-6 mb-4">
            <a href="https://instagram.com/gdpartstudio" target="_blank" class="text-2xl text-pink-600 hover:text-pink-700 transition-colors">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://wa.me/6281339172556" target="_blank" class="text-2xl text-green-600 hover:text-green-700 transition-colors">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
        <p class="text-slate-50 mb-2">Made with <span class="text-red-500 heart">❤</span> by GDPARTSTUDIO</p>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 6,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: 'fetchData',
                type: 'GET'
            },
            columns: [
                { data: "name" }
            ],
            createdRow: function(row, data, dataIndex) {
                $('td', row).eq(0)
                    .addClass('px-6 py-4 whitespace-nowrap text-base md:text-xl text-gray-500')
                    .attr('onclick', 'getQR("'+data.id+'")');
            }
        });

        $('#reloadButton').on('click', function() {
            table.ajax.reload();
            $('#qr-container').html('<img src="uploads/Logo1b.png" alt="GDPARTSTUDIO" class="mx-auto mb-4 w-32 md:w-48 h-32 md:h-48"><h1 class="text-xl md:text-2xl font-semibold mb-4">Select your name to get your photo</h1>');
        });
    });

    function getQR(id) {
        fetch('getQR?id='+id)
            .then(response => response.json())
            .then(data => {
                $('#qr-container').html('<h1 class="text-xl md:text-2xl font-semibold mb-4 text-center">Hello <span class="text-2xl md:text-4xl">'+data.qr_name+'</span>,<br>scan this QR Code to get your photo<br><span class="text-base md:text-lg">(<i>scan QR Code di bawah untuk mendapatkan foto anda</i>)</span></h1><img src="'+data.qr_path+'" alt="QR Code" class="mx-auto mb-4 w-48 md:w-60 h-48 md:h-60">');
            })
            .catch(err => console.error('Error fetching QR data:', err));
    }
</script>
</body>
</html>
