<?php
	require_once("mysql.php");

	function getAddress($lat, $lng)
	{
		global $gapikey;
		$lat = floatval($lat);
		$lng = floatval($lng);
		if($lat != 0 && $lat >= -90 && $lat <= 90 && $lng != 0 && $lng >= -180 && $lng <= 180)
		{
//			$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&output=json&sensor=true&key=$gapikey";
//			$address = file_get_contents($url);
			$address = file_get_contents("data.txt");
			$json_data = json_decode($address, true);

			if($json_data['status'] == "OK")
				return $json_data;
			return false;
		}

		echo "Invalid latitude or longitude";
		return false;
	}

	function getPasswordHash($password)
	{
		global $link;

		$password = password_hash(strip_tags(trim($password)), PASSWORD_ARGON2ID);
		return mysqli_real_escape_string($link, $password);
	}

	function comparePasswordHash($email, $password)
	{
		global $link;

		$email = mysqli_real_escape_string($link, $email);
		$password = strip_tags(trim($password));

		$query = "select `password` from `users` where `email`='$email'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) <= 0)
			return false;

		$row = mysqli_fetch_assoc($res);
		if(password_verify($password, $row['password']))
			return true;
		return false;
	}

	function isEmailInDB($email)
	{
		global $link;

		$email = mysqli_real_escape_string($link, strip_tags(trim($email)));
		$query = "SELECT 1 FROM `users` WHERE `email`='$email'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) >= 1)
			return true;
		return false;
	}

	function isNumberInDB($phoneNo)
	{
		global $link;
		$number = mysqli_real_escape_string($link, strip_tags(trim($phoneNo)));
		$query = "SELECT 1 FROM `users` WHERE `phone` = '$phoneNo'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) >= 1)
			return true;
		return false;
	}

	function registerUser($email, $password, $phoneNo, $name)
	{
		global $link;
		$hash = genHash();
		$email = mysqli_real_escape_string($link, strip_tags(trim($email)));
		$password = mysqli_real_escape_string($link, strip_tags(trim($password)));
		$phoneNo = is_null($phoneNo) ? NULL : mysqli_real_escape_string($link, strip_tags(trim($phoneNo)));
		$query = "INSERT INTO `users` SET `email`='$email', `password`='$password', `created`=NOW(), `last_active`=NOW(), `phone`='$phoneNo', `name`='$name'";
	}
