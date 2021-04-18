<?php
	require_once("common.php");

	if(!isset($_SERVER['HTTP_REFERER']) || strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen($refererurl))) != $refererurl)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid latitude or longitude";
		echo json_encode($arr);
		exit;
	}
	header("Content-Type: text/plain");

	/* Get the name of the uploaded file */
	$filename = $_FILES['file']['name'];

	/* Choose where to save the uploaded file */
	$location = "upload/".$filename;

	/* Save the uploaded file to the local filesystem */
	if ( move_uploaded_file($_FILES['file']['tmp_name'], $location) ) { 
	echo 'Success'; 
	} else { 
	echo 'Failure'; 
	}

?>