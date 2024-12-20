<?php
	// Database connection
	include 'koneksi.php'; // Ensure this file contains the correct connection setup
	
	$draw = $_GET['draw'];
	$start = $_GET['start'];
    $length = $_GET['length'];
    $searchValue = $_GET['search']['value'];
	
	$q = $koneksi->query("
		SELECT * 
		FROM undangan_list a 
		WHERE ul_name LIKE '%".$searchValue."%' 
		ORDER BY ul_date DESC 
		LIMIT ".$start.", ".$length."
	");

	$data = [];
	while ($d = $q->fetch_array()) {
		$data[] = [
			'id' => $d['ul_id'],
			'name' => $d['ul_name']
		];
	}
	
	$qtotal = $koneksi->query("
		SELECT COUNT(*) as jumlah 
		FROM undangan_list a 
		WHERE ul_name LIKE '%".$searchValue."%' 
	")->fetch_array();

	$response = [
		'draw' => intval($draw),
        'recordsTotal' => $qtotal["jumlah"],
        'recordsFiltered' => $qtotal["jumlah"],
		'data' => $data
	];

	echo json_encode($response);

?>
