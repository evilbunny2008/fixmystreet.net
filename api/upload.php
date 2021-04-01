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

	$row = mysqli_fetch_assoc(mysqli_query($link, "select `id` from `users` where `email`='$email'));
	$userid = $row['id'];

	$file_count = 0;
	foreach($_FILES["photos"]["error"] as $key => $error)
	{
		if($error == UPLOAD_ERR_OK)
		{
			if(is_uploaded_file($_FILES["photos"]["tmp_name"][$key]) &&
				file_size($_FILES["photos"]["tmp_name"][$key]) > 50000)
			{
				$file_count++;
			}
		}
	}

	if($file_count < 2)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "You failed to upload enough photos of the problem, or the photos were low quality.";
		echo json_encode($arr);
		exit;
	}

	$lat = floatval($_GET['lat']);
	$lng = floatval($_GET['lng']);
	if($lat == 0 || $lat < -90 || $lat > 90 || $lng == 0 || $lng < -180 || $lng > 180)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid username and/or password.";
                echo json_encode($arr);
                exit;
	}

	$address = cleanup($_GET['address']);
	$council = cleanup($_GET['council']);
	$title = cleanup($_GET['title']);
	$extra = cleanup($_GET['extra']);
	$defect = cleanup($_GET['defect']);

	$query = "SELECT `id` FROM `defect_type` WHERE `defect`='$defect'";
	$res = mysqli_query($link, $query);
	if(mysqli_num_rows($res) <= 0)
	{
		$arr = array();
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid username and/or password.";
		echo json_encode($arr);
		exit;
	}

	$row = mysqli_fetch_assoc($res);
	$defect_id = $row['id'];

	$query  = "INSERT INTO `problems` SET `latitude`=$lat, `longitude`=$lng, `address`='$address', `council`='$council', `title`='$title', `user_id`=$userid, ";
	$query .= "`anonymous`=0, `extra`='$extra', `non_public`=0, `defect_id`=$defect_id";
	mysqli_query($link, $query);
	$problem_id = mysqli_insert_id($link);

	foreach($_FILES["photos"]["error"] as $key => $error)
        {
		$filename = cleanup(basename($_FILES["photos"]["name"][$key]));
		move_uploaded_file($_FILES["photos"]["tmp_name"][$key], "${uploads_dir}/${problem_id}-${key}.jpg");
		$file_path = basename($uploads_dir)."/${problem_id}-${key}.jpg";
		$query = "INSERT INTO `photos` SET `problem_id`=$problem_id, `comment`='$filename', `file_path`='$file_path'";
		mysqli_query($link, $query);
	}

	$arr = array();
	$arr['status'] = "OK";
	echo json_encode($arr);
	exit;
