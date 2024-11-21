<?php
	include 'koneksi.php';
	
	// Initialize variables
	$phoneNumber = '';
	$message = '';
	$whatsappLink = '';

	// Check if form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$type = $_POST['type'];
		
		$koneksi->query("
			INSERT INTO list_url (
				template_id, lu_name, lu_phone
			)
			VALUES (
				'".$type."', '".$name."', '".$phone."'
			)
		");
		
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
		$input = json_decode(file_get_contents('php://input'), true);
		$id = $input['id'];
		
		$koneksi->query("
			DELETE FROM list_url WHERE lu_id='".$id."'
		");
		
		echo json_encode(['success' => true]);
		exit;
	}
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
            <form method="POST" action="">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 lg:text-lg" placeholder="Masukkan Nama">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 lg:text-lg">
                            <option value="1">Anak</option>
                            <option value="2">Keluarga</option>
                        </select>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomer HP</label>
                        <input type="tel" id="phone" name="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 lg:text-lg" placeholder="Masukkan nomer HP">
                    </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomer HP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
					<?php
						$q = $koneksi->query("
							SELECT *, 
							(SELECT template_type FROM tempalte WHERE template_id=a.template_id) as tipe,
							(SELECT template_text FROM tempalte WHERE template_id=a.template_id) as text
							FROM list_url a
						");
						
						while($d = $q->fetch_array()){
							$name = $d['lu_name'];
							$name_url = str_replace(" ","+",$name);
							$phone = $d['lu_phone'];
							$text = $d['text'];
							
							$search = array("##NAME##", "##NAME_URL##");
							$replace = array($name, $name_url);
							
							$text = str_replace($search, $replace, $text);
							
							$encoded_text = urlencode($text);
							
							$wa_url = "https://wa.me/".$phone."?text=".$encoded_text;
						
							echo "
								<tr>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900\">
										<a href=\"".$wa_url."\" target=\"_blank\" class=\"inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2\">
											<span>Kirim WA</span>
										</a>
										<button id=\"copyButton\" data-clipboard-text=\"".$text."\" class=\"inline-flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2\">
											<span>Copy Text</span>
										</button>
										<button onclick=\"deleteItem('".$d['lu_id']."')\" class=\"inline-flex items-center px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2\">
											<span>Delete</span>
										</button>
									</td>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">".$d["tipe"]."</td>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">".$d["lu_name"]."</td>
									<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-500\">".$d["lu_phone"]."</td>
								</tr>
							";
						}
					?>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
	<script>
        document.getElementById('copyButton').addEventListener('click', function() {
            // Get the text to copy from the data attribute
            const textToCopy = this.getAttribute('data-clipboard-text');

            // Use the Clipboard API to write text to the clipboard
            navigator.clipboard.writeText(textToCopy).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
		
		function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting item.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>

</body>
</html>
