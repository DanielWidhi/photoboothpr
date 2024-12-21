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
        }
        .dropzone:hover {
            border-color: #2563eb;
            background: #f1f5f9;
        }
        .dropzone .dz-message {
            color: #3b82f6;
            font-weight: 500;
        }
        .dropzone .dz-preview {
            margin: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
	<nav class="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 w-full z-50">
		<div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
			<a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
				<img src="uploads/GDPB.png" class="h-8" alt="Logo" />
				<span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
			</a>
			<button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
				<span class="sr-only">Open main menu</span>
				<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
					<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
				</svg>
			</button>
			<div class="hidden w-full md:block md:w-auto" id="navbar-default">
				<ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
					<li>
					<a href="https://qrbooth.gdpartstudio.my.id/photoboothpr/photoboothpr/showqr" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">ShowQR</a>
					</li>
					<li>
					<a href="https://qrbooth.gdpartstudio.my.id/photoboothpr/photoboothpr/Form" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Form</a>
					</li>
					<li>
						<a href="https://wa.me/6281339172556" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<?php ?>

    <div class="container mx-auto p-4 mt-20">

        <!-- Form Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 lg:text-lg" placeholder="Masukkan Nama">
                    </div>
					<div class="dropzone" id="fileUpload"></div>
                    <div>
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">Submit</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <table id="example" class="display min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
					<?php
$q = $koneksi->query("
							SELECT *
							FROM undangan_list a
							ORDER BY ul_date DESC
						");

while ($d = $q->fetch_array()) {
    echo "
								<tr>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">" . $d["ul_name"] . "</td>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">" . date("d/m/Y H-i:s", strtotime($d["ul_date"])) . "</td>
								</tr>
							";
}
?>
                </tbody>
            </table>
        </div>
    </div>

	<footer class="bg-white dark:bg-gray-900 mt-auto">
        <div class="container px-6 py-8 mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <a href="https://www.instagram.com/danielwidhi_198" target="_blank" class="mb-4 md:mb-0">
                    <img class="w-auto h-7" src="uploads/Logo1.png" alt="GDPARTSTUDIO">
                </a>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
	<!-- DROP ZONE JS -->
	<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
	<script>
		Dropzone.autoDiscover = false;
		var uploadedFiles = [];

		var myDropzone = new Dropzone("#fileUpload", {
			url: "upload",
			dictDefaultMessage: "Drop files here or<br>click to upload...",
			params: {'name': function() { return $('#name').val(); }},
			uploadMultiple: true,
			autoProcessQueue: false,
			parallelUploads: 10,
			acceptedFiles: ".jpg, .jpeg, .png, .mp4, .avi",
			addRemoveLinks: true,
			clickable: true,
			maxFilesize: 100,
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
