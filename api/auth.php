<?php
	require_once('../mysql.php');

	$email = mysqli_real_escape_string($link, strip_tags(trim($_GET['email'])));
	$password = mysqli_real_escape_string($link, strip_tags(trim($_GET['password'])));

	$query = "select `id` from `users` where `email`='$email' and `password`=PASSWORD('$password')";
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
