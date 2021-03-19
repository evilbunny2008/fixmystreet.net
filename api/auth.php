<?php
	require_once('../common.php');

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
