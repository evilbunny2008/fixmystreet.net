<?php
	require_once('../common.php');

	$uploads_dir = '../photos';

	if(!in_array($_GET['serverKey'], $serverKeys, true))
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid server key";
		echo json_encode($arr);
		exit;
	}

	$email = strip_tags(trim($_GET['email']));
	$password = strip_tags(trim($_GET['password']));

	if(!comparePasswordHash($email, $password))
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid username and/or password.";
		echo json_encode($arr);
		exit;
	}

	$file_count = 0;
	foreach($_FILES["photos"]["error"] as $key => $error)
	{
		if($error == UPLOAD_ERR_OK)
		{
			if(is_uploaded_file($_FILES["photos"]["tmp_name"][$key]))
			{
				$file_count++;
			}
		}
	}

	if($file_count < 2)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid uploaded file";
		echo json_encode($arr);
		exit;
	}

	

	$arr['status'] = "OK";
	echo json_encode($arr);
	exit;
