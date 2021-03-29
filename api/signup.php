<?php
	require_once('../common.php');

        if($_GET['severKey'] != $serverKey)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid server key";
                echo json_encode($arr);
                exit;
        }

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

	if($email == "" || strlen($email) < 8 || !filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "Invalid email.";
		echo json_encode($arr);
		exit;
	}

	if($password == "" || strlen($password) < 8)
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "Invalid password.";
		echo json_encode($arr);
		exit;
	}

	if($name == "" || strlen($name) < 4)
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "Invalid name.";
		echo json_encode($arr);
		exit;
	}

	if($mobile == "" || strlen($mobile) < 10)
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "Invalid mobile.";
		echo json_encode($arr);
		exit;
	}

	if(isNumberInDB($mobile))
	{
		$arr['status'] = 'Fail';
		$arr['errmsg'] = "Number already in the database.";
		echo json_encode($arr);
		exit;
	}

	$password = getPasswordHash($cpassword);

	registerUser($email, $password, $mobile, $name);
	$arr['status'] = 'OK';
	echo json_encode($arr);
	exit;
