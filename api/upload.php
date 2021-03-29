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
		$arr['status'] = "Fail";
		$arr['errmsg'] = "Invalid username and/or password.";
		echo json_encode($arr);
		exit;
	}

	foreach($_FILES["photos"]["error"] as $key => $error)
	{
		if($error == UPLOAD_ERR_OK)
		{
			if(!is_uploaded_file($_FILES["photos"]["tmp_name"][$key]))
			{
				$arr['status'] = "Fail";
				$arr['errmsg'] = "Invalid uploaded file";
				echo json_encode($arr);
				exit;
			}
		}
	}

	

	$arr['status'] = "OK";
	echo json_encode($arr);
	exit;
