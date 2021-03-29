<?php
	require_once('../common.php');

	if(!in_array($_GET['severKey'], $serverKeys, true))
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
		$arr['status'] = 'Fail';
		echo json_encode($arr);
		exit;
	}

	$arr['status'] = 'OK';
	echo json_encode($arr);
	exit;
