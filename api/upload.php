<?php
	require_once('../common.php');

	$uploads_dir = '../photos';

//	foreach(getallheaders() as $key => $val)
//		echo "$key => $val\n";

	if(!isset($_POST['serverKey']) || !in_array($_POST['serverKey'], $serverKeys, true))
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid server key";
		echo json_encode($arr);
		exit;
	}

	$email = cleanup(urldecode($_POST['email']));
	$password = cleanup(urldecode($_POST['password']));

	if(!comparePasswordHash($email, $password))
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid username and/or password.";
		echo json_encode($arr);
		exit;
	}

	$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT `id` FROM `users` WHERE `email`='$email'"));
	$userid = $row['id'];

	$file_count = 0;
	foreach($_FILES["photos"]["error"] as $key => $error)
	{
		if($error == UPLOAD_ERR_OK)
		{
			if(is_uploaded_file($_FILES["photos"]["tmp_name"][$key]) &&
				filesize($_FILES["photos"]["tmp_name"][$key]) > 50000)
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

	$lat = floatval($_POST['lat']);
	$lng = floatval($_POST['lng']);
	if($lat == 0 || $lat < -90 || $lat > 90 || $lng == 0 || $lng < -180 || $lng > 180)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid latitude or longitude.";
                echo json_encode($arr);
                exit;
	}

	$address = cleanup(urldecode($_POST['address']));
	$council = cleanup(urldecode($_POST['council']));
	$summary = cleanup(urldecode($_POST['summary']));
	$extra = cleanup(urldecode($_POST['extra']));
	$defect = cleanup(urldecode($_POST['defect']));

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

	$query  = "INSERT INTO `problem` SET `latitude`=$lat, `longitude`=$lng, `address`='$address', `council`='$council', `summary`='$summary', `user_id`=$userid, ";
	$query .= "`anonymous`=0, `extra`='$extra', `non_public`=0, `defect_id`=$defect_id";

	mysqli_query($link, $query);
	$problem_id = mysqli_insert_id($link);

	if($problem_id <= 0)
	{
		$arr = array();
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Error inserting into database...";
		echo json_encode($arr);
		exit;
	}

	foreach($_FILES["photos"]["error"] as $key => $error)
        {
            $filename = cleanup(urldecode(basename($_FILES["photos"]["name"][$key])));
            resizeAndStrip($_FILES["photos"]["tmp_name"][$key], "${uploads_dir}/${problem_id}-${key}.jpg", "${uploads_dir}/${problem_id}-${key}-thumb.jpg");
            $file_path = basename($uploads_dir)."/${problem_id}-${key}.jpg";
            $file_thumb = basename($uploads_dir)."/${problem_id}-${key}-thumb.jpg";
            $query = "INSERT INTO `photos` SET `problem_id`=$problem_id, `comment`='$filename', `file_path`='$file_path', `thumb`='$file_thumb'";
            mysqli_query($link, $query);
	}

	$arr = array();
	$arr['status'] = "OK";
	echo json_encode($arr);
	exit;
