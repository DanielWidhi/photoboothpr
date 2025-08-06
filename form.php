<?php
    include 'koneksi.php';
    require 'auth.php';
    requireAdminAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input</title>
    <link rel="icon" type="image/png" href="uploads/Logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.0/dist/flowbite.min.js"></script>

    <style>
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

        /* Custom Dropzone Styling */
        .dropzone {
            border: 2px dashed #3b82f6;
            border-radius: 0.5rem;
            background: #f8fafc;
            transition: all 0.3s ease;
            min-height: 150px;
            padding: 20px;
        }
        .dropzone:hover {
            border-color: #2563eb;
            background: #f1f5f9;
        }
        .dropzone .dz-message {
            color: #3b82f6;
            font-weight: 500;
            font-size: 1.1rem;
            margin: 1em 0;
        }
        .dropzone .dz-preview {
            margin: 1rem;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Footer Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .social-icon:hover {
            animation: pulse 1s infinite;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
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
                        <a href="showqr.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            <i class="fas fa-qrcode mr-2"></i>Show QR
                        </a>
                    </li>
                    <li>
                        <a href="galery.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            <i class="fas fa-images mr-2"></i>Gallery
                        </a>
                    </li>
                    <li>
                        <a href="index.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 mt-20">
        <!-- Form Section -->
        <div class="bg-white shadow-xl rounded-2xl p-6 mb-6 fade-in">
            <form method="POST" enctype="multipart/form-data" id="uploadForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Your Name</label>
                        <input type="text" id="name" name="name"
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                               placeholder="Enter your name">
                    </div>
                    <div class="dropzone" id="fileUpload"></div>
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                        <i class="fas fa-upload mr-2"></i>Submit
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-xl rounded-2xl p-6 mb-6 overflow-x-auto fade-in">
            <table id="example" class="w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q = $koneksi->query("
                        SELECT *
                        FROM undangan_list a
                        ORDER BY ul_date DESC
                    ");

                        while ($d = $q->fetch_array()) {
                            echo "
                            <tr class='hover:bg-gray-50 transition-colors'>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . $d["ul_name"] . "</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . date("d/m/Y H:i:s", strtotime($d["ul_date"])) . "</td>
                            </tr>
                        ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- New Attractive Footer -->
    <footer class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 																																																																		shadow-inner mt-auto py-6">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="https://instagram.com/gdpartstudio" target="_blank" class="text-2xl text-pink-600 hover:text-pink-700 transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/6281339172556" target="_blank" class="text-2xl text-green-600 hover:text-green-700 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            <p class="text-slate-50 mb-2">Made with <span class="text-red-500 heart">❤</span> by PARTSTUDIO</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                }
            });
        });
    </script>
    <script>
        Dropzone.autoDiscover = false;
        var uploadedFiles = [];

        var myDropzone = new Dropzone("#fileUpload", {
            url: "upload",
            dictDefaultMessage: "<i class='fas fa-cloud-upload-alt text-3xl mb-2'></i><br>Drop files here or click to upload",
            params: {'name': function() { return $('#name').val(); }},
            uploadMultiple: true,
            autoProcessQueue: false,
            parallelUploads: 100,
            acceptedFiles: ".jpg, .jpeg, .png, .mp4, .avi",
            addRemoveLinks: true,
            clickable: true,
            maxFilesize: 250,
            init: function () {
                var dz = this;

                document.getElementById("uploadForm").addEventListener("submit", function (e) {
                    e.preventDefault();

                    if ($('#name').val().trim() === '') {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please enter your name',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    if (dz.files.length === 0) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please upload at least one file',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    dz.processQueue();
                });

                this.on("sending", function(file, xhr, formData) {
                    formData.append("name", $('#name').val());
                    Swal.fire({
                        title: 'Uploading...',
                        text: 'Please wait while we upload your files',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });

                this.on("successmultiple", function (files, response) {
                    response = JSON.parse(response);
                    uploadedFiles = response.filePaths;
                    submitForm();
                });

                this.on("errormultiple", function (files, response) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'File upload error: ' + response,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6'
                    });
                });

                this.on("addedfile", function(file) {
                    if (!file.type.match(/^(image\/|video\/)/)) {
                        this.removeFile(file);
                        Swal.fire({
                            title: 'Invalid File!',
                            text: 'Please upload only image or video files',
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                });
            }
        });

        function submitForm() {
            var formData = new FormData(document.getElementById("uploadForm"));

            uploadedFiles.forEach(function (filePath) {
                formData.append("uploadedFiles[]", filePath);
            });

            fetch('submit_form', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Form submitted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clearForm();
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error submitting form: ' + data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
                console.error("Error:", error);
            });
        }

        function clearForm() {
            document.getElementById("uploadForm").reset();
            myDropzone.removeAllFiles();
            uploadedFiles = [];
        }
    </script>
</body>
</html>
