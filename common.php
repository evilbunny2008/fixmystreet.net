<?php
	require_once("mysql.php");

	$header = '    <div class="flex-wrapper">
	<div class="header">
	  <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
		<a class="pure-menu-heading" href="./index.php">FixMyStreet.net</a>
		<ul class="pure-menu-list">
		  <li class="pure-menu-item">
			<a href="./index.php" class="pure-menu-link">Report a problem</a>
		  </li>
		  <li class="pure-menu-item">
			<a href="#" class="pure-menu-link">Help</a>
		  </li>
		  <li class="pure-menu-item">
			<a href="./reports.php" class="pure-menu-link">All reports</a>
		  </li>
		  <li class="pure-menu-item">
			<a href="#" class="pure-menu-link">Local alerts</a>
		  </li>';
	if(isset( $_SESSION['loggedin']) && $_SESSION['loggedin'] === 1)
	{
		$header .= '<li class="pure-menu-item">
		<a href="./logout.php" class="pure-menu-link">Log out</a>
	  </li>
	  </ul>
	  </div>
	  </div>';
	}
	else
	{
		$header .= 	'<li class="pure-menu-item">
		<a href="./login.php" class="pure-menu-link">Sign in</a>
			</li>
			<li class="pure-menu-item">
				<a href="" class="pure-menu-link">Sign up</a>
			</li>
			</ul>
		</div>
		</div>';
	}


	$footer = '    <div class="content">
	<hr />

	<h2 class="content-head is-center">FixMyStreet.net</h2>
	<p class="is-center">
	  This version of FixMyStreet is written in PHP and runs on a MySQL
	  database!
	  <br />
	  It is inspired by
	  <a target="_blank" href="https://github.com/mysociety/fixmystreet">MySociety\'s FixMyStreet.com</a>
	  <br />
	  Would you like to contribute to FixMyStreet.net? Our code is open
	  source and available on
	  <a target="_blank" href="https://github.com/evilbunny2008/fixmystreet.net">github</a>
	</p>
  </div>';
	
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
		$email = mysqli_real_escape_string($link, strip_tags(trim($email)));
		$password = mysqli_real_escape_string($link, strip_tags(trim($password)));
		$phoneNo = empty($phoneNo) ? NULL : mysqli_real_escape_string($link, strip_tags(trim($phoneNo)));
		$name = mysqli_real_escape_string($link, strip_tags($name));

		$query = "INSERT INTO `users` SET `email`='$email', `password`='$password', `created`=NOW(), `last_active`=NOW(), `phone`='$phoneNo', `name`='$name'";
		mysqli_query($link, $query);

		$id = mysqli_insert_id($link);
		$hash = genHash();

		$query = "INSERT INTO `token` SET `token`='$hash', `user_id`=$id, `type`='signup'";
		mysqli_query($link, $query);
		sendMail(1, $hash, $id, $email);
	}

	function sendMail($type, $hash, $id, $email)
	{
		switch($type)
		{
			case 1:
				$body = ("Hello").",\n\n"._("You, or someone that knows your email address,")."\n"._("just signed up with FixMyStreet.net")."\n\n";
				$body .= _("Please click on the following URL to confirm your email address:")."\n\n";
				$body .= "https:/"."/fixmystreet.net/verify.php?hash=$hash&uid=$id\n\n";
				mail($email, "[FixMyStreet.net]: "._("Email Verification Check"), $body, "From: noreply@fixmystreet.net\nReturn-Path: noreply@fixmystreet.net","-f noreply@fixmystreet.net");
				break;
			case 2:
				$body = ("Hello").",\n\n"._("You, or someone that knows your email address,")."\n"._("requested to reset your password on FixMyStreet.net")."\n\n";
				$body .= _("Please click on the following URL to reset your password:")."\n\n";
				$body .= "https:/"."/fixmystreet.net/reset.php?hash=$hash&uid=$id\n\n";
				mail($email, "[FixMyStreet.net]: "._("Password Reset"), $body, "From: noreply@fixmystreet.net\nReturn-Path: noreply@fixmystreet.net","-f noreply@fixmystreet.net");
		}
	}

	function genHash()
	{
		$rnd = fopen("/dev/urandom", "r");
		$hash = md5(fgets($rnd, 64));
		return $hash;
	}
