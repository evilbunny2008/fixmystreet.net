<?php
	require_once('../mysql.php');

	$email = mysqli_real_escape_string($link, strip_tags(trim($_GET['email'])));
	$password = password_hash(strip_tags(trim($_GET['password'])) + $secret, PASSWORD_ARGON2ID);
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
