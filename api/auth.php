<?php
	require_once('../common.php');

	$email = mysqli_real_escape_string($link, strip_tags(trim($_GET['email'])));
	$password = getPasswordHash($_GET['password']);
	$password = mysqli_real_escape_string($link, $password);

	$query = "select `id` from `users` where `email`='$email' and `password`='$password'";
	$res = mysqli_query($link, $query);
	if(mysqli_num_rows($res) == 1)
	{
		$arr['status'] = 'OK';
		echo json_encode($arr);
		exit;
	}

	$arr['status'] = 'Fail';
	echo json_encode($arr);
	exit;
