<?php
	require_once("common.php");

	if(!isset($_SERVER['HTTP_REFERER']) || strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen($refererurl))) != $refererurl)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid latitude or longitude";
		echo json_encode($arr);
		exit;
	}
	// header("Content-Type: text/plain");

	$file_count = 0;
	if($_FILES["photo"]["error"] == UPLOAD_ERR_OK)
	{
		if(is_uploaded_file($_FILES["photo"]["tmp_name"]) &&
			filesize($_FILES["photo"]["tmp_name"]) > 50000)
		{
			$file_count++;
		}
	}

	if($file_count != 1)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "No files received";
		echo json_encode($arr);
		// echo 'Failed|Invalid file, or file too small.';
	}

	$uuid = getUUID();
	$filename = cleanup(urldecode(basename($_FILES["photo"]["name"])));
	resizeAndStrip($_FILES["photo"]["tmp_name"], "/tmp/${uuid}.jpg", "/tmp/${uuid}_thumb.jpg");
	$file_path = "/tmp/${uuid}.jpg";
	$file_thumb = "/tmp/${uuid}_thumb.jpg";
	// echo 'Success|'.$uuid;
	$arr['status'] = "SUCCESS";
	$arr['filename'] = $filename;
	$arr['uuid'] = $uuid;
	echo json_encode($arr);
