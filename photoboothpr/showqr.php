<?php
	include 'koneksi.php';
	
	session_start();

	// Check if the user is authenticated
	if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
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
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <style>
        /* Custom styling for the vertical divider */
        .divider {
            border: 0;
            border-left: 1px solid #e0e0e0; /* Light gray border */
            height: auto;
            margin: 0 1rem;
            padding: 0;
        }
    </style>
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
<body class="bg-gray-100 h-screen m-0 flex items-center justify-center">

    <div class="flex w-full h-screen max-h-screen p-8 bg-white overflow-hidden">
        <!-- DataTable -->
        <div class="flex-1 flex flex-col p-4 overflow-auto">
			<!-- Reload Button -->
            <button id="reloadButton" class="mb-6 w-full mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-lg" onclick="reloadData();">Reload Data</button>
            <h2 class="text-xl font-semibold mb-4">Select Your Name</h2>
            <div class="flex-grow overflow-auto">
                <table id="dataTable" class="display min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $q = $koneksi->query("
                                SELECT * 
                                FROM undangan_list a 
                                ORDER BY ul_date DESC
                            ");
                            
                            while($d = $q->fetch_array()){
                                echo "
                                    <tr>
                                        <td class=\"px-6 py-4 whitespace-nowrap text-xl text-gray-500\" onclick=\"getQR('".$d["ul_id"]."');\">".$d["ul_name"]."</td>
                                    </tr>
                                ";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            
        </div>

        <!-- Divider -->
        <hr class="divider">

        <!-- QR Code -->
        <div class="flex-1 flex flex-col items-center justify-center p-4 overflow-auto">
            <div id="qr-container" clas="justify-center">
                <img src="uploads/PR_Wfix.png" alt="PR Wedding" class="mx-auto mb-4 w-48 h-48">
                <h1 class="text-2xl font-semibold mb-4">Select your name to get your photo</h1>
            </div>
        </div>
    </div>

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
                ajax: {
                    url: 'fetchData', // Update this URL to your server-side script
                    type: 'GET'
                },
                columns: [
					{ data: "name" } // Ensure this matches the field in your JSON response
				],
				createdRow: function(row, data, dataIndex) {
					// Add class to the 'name' column and set onclick with data.id
					$('td', row).eq(0)
						.addClass('px-6 py-4 whitespace-nowrap text-xl text-gray-500')
						.attr('onclick', 'getQR("'+data.id+'")'); // Attach onclick event with id
				}
            });

            $('#reloadButton').on('click', function() {
                table.ajax.reload(); // Reload the DataTable
				$('#qr-container').html('<img src="uploads/PR_Wfix.png" alt="PR Wedding" class="mx-auto mb-4 w-48 h-48"><h1 class="text-2xl font-semibold mb-4">Select your name to get your photo</h1>');
            });
        });
        
        function getQR(id){
            fetch('getQR?id='+id)
			.then(response => response.json())
			.then(data => {
				$('#qr-container').html('<h1 class="text-2xl font-semibold mb-4 text-center">Hello <span class="text-4xl">'+data.qr_name+'</span>,<br>scan this QR Code to get your photo<br><span class="text-lg">(<i>scan QR Code di bawah untuk mendapatkan foto anda</i>)</span></h1><img src="'+data.qr_path+'" alt="QR Code" class="mx-auto mb-4 w-60 h-60">');
			});
        }
    </script>
</body>
</html>
