<?php

	include 'koneksi.php';
	
	$id = $_GET["id"];
	
	$qr = $koneksi->query("
		SELECT * FROM undangan_list WHERE ul_id='".$id."' LIMIT 0,1
	")->fetch_array();
	
	$status = "n";
	$path = "";
	$name = "";
	if($qr){
		if($qr["ul_qr"]!=""){
			if(file_exists($qr["ul_qr"])){
				$status = "y";
				$path = $qr["ul_qr"];
				$name = $qr["ul_name"];
			}
		}
	}
	
	echo json_encode(['qr_status' => $status, 'qr_path' => $path, 'qr_name' => $name]);
	
?>