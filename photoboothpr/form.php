<?php
	include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Table with DataTables</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
	<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
	
	<?php  ?>

    <div class="container mx-auto p-4">
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
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <table id="example" class="display min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
						<!--
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
						-->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
						<!--
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Status</th>
						-->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
					<?php
						$q = $koneksi->query("
							SELECT * 
							FROM undangan_list a 
							ORDER BY ul_date DESC
						");
						
						while($d = $q->fetch_array()){
							
							$label_qr = "<span class=\"bg-red-500 text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-500 dark:text-white\">Hide</span>";
							if($d["ul_showqr"]=="y"){
								$label_qr = "<span class=\"bg-green-500 text-white text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-500 dark:text-white\">Show</span>";
							}
							
							echo "
								<tr>
									<!--
									<td class=\"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900\">
										<button onclick=\"showQR('".$d['ul_id']."')\" class=\"inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2\">
											<span>Show QR</span>
										</button>
										<button onclick=\"hideQR('".$d['ul_id']."')\" class=\"inline-flex items-center px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2\">
											<span>Hide QR</span>
										</button>
									</td>
									-->
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">".$d["ul_name"]."</td>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">".date("d/m/Y H-i:s", strtotime($d["ul_date"]))."</td>
									<!--
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">
										".$label_qr."
									</td>
									-->
								</tr>
							";
						}
					?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
	<!-- DROP ZONE JS -->
	<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
	<script>
		Dropzone.autoDiscover = false;
		var uploadedFiles = [];
		
		var myDropzone = new Dropzone("#fileUpload", {
			url: "upload",  // The file upload URL
			dictDefaultMessage: "Drop files here or<br>click to upload...",
			params: {'name': function() { return $('#name').val(); }},
			uploadMultiple: true,
			autoProcessQueue: false,  // Prevent automatic upload
			parallelUploads: 10,  // Allow uploading multiple files at once
			acceptedFiles: "image/*",  // Only accept image files
			addRemoveLinks: true,  // Option to remove files before upload
			clickable: true,
			init: function () {
				var dz = this;

				// Capture the form submission event
				document.getElementById("uploadForm").addEventListener("submit", function (e) {
					e.preventDefault();  // Prevent the default form submission

					// Validate if the name field is empty
					if ($('#name').val().trim() === '') {
						alert("Name cannot be empty. Please enter a name.");
						return;  // Stop here and don't proceed with file upload
					}

					// If the name is valid, trigger the file upload
					dz.processQueue();
				});
				
				this.on("sending", function(file, xhr, formData) {
					// Append additional data to the formData (such as name)
					formData.append("name", $('#name').val());
				});
				
				this.on("successmultiple", function (files, response) {
					response = JSON.parse(response);
					uploadedFiles = response.filePaths;
					
					submitForm();
				});

				// Handle any errors during file upload
				this.on("errormultiple", function (files, response) {
					alert("File upload error: " + response);
				});
			}
		});
		
		function submitForm() {
			var formData = new FormData(document.getElementById("uploadForm"));
			
			// Append the uploaded file paths to form data
			uploadedFiles.forEach(function (filePath) {
				formData.append("uploadedFiles[]", filePath);
			});

			// Send the form data via AJAX
			fetch('submit_form', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// document.getElementById('uploadedFilePaths').innerHTML = "Form submitted successfully!";
					alert("Form submitted successfully!");
					clearForm();
					location.reload();
				} else {
					alert("Error submitting form: " + data.message);
				}
			})
			.catch(error => {
				console.error("Error:", error);
			});
		}
		
		function clearForm() {
			// Reset the form fields
			document.getElementById("uploadForm").reset();

			// Clear Dropzone uploaded files
			myDropzone.removeAllFiles();

			// Clear the uploadedFiles array
			uploadedFiles = [];
		}
    </script>

</body>
</html>
