<?php
	require_once('../common.php');

	$email = strip_tags(trim($_GET['email']));
	if(isEmailInDB($email))
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "This email already exists in the database.";
		echo json_encode($arr);
		exit;
	}

	$email = mysqli_real_escape_string($link, $email);
	$password = mysqli_real_escape_string($link, getPasswordHash($_GET['password']));
	$name = mysqli_real_escape_string($link, strip_tags(trim($_GET['name'])));
	$mobile = mysqli_real_escape_string($link, strip_tags(trim($_GET['mobile'])));

	$query = "INSERT INTO `users` SET `email`='$email', `password`='$password', `created`=NOW(), `last_active`=NOW(), `phone`='$mobile', `name`='$name'";
	mysqli_query($link, $query);

	$arr['status'] = 'OK';
	echo json_encode($arr);
	exit;
