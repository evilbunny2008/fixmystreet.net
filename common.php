<?php
	require_once("mysql.php");

	function getAddress($lat, $lng)
	{
		global $gapikey, $link;

		$lat = floatval($lat);
		$lng = floatval($lng);
		if($lat != 0 && $lat >= -90 && $lat <= 90 && $lng != 0 && $lng >= -180 && $lng <= 180)
		{
			$row = cacheSearch("$lat,$lng");
			if($row != false)
				return $row;

			$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&output=json&sensor=true&key=$gapikey";
			$address = file_get_contents($url);
			$json_data = json_decode($address, true);

			if($json_data['status'] == "OK")
			{
				$row = cacheInsert("$lat,$lng", $json_data);
				return $row;
			}
			return false;
		}

		return false;
	}

	function getPlace($str)
	{
		global $gapikey, $link;

		if(isset($str) && $str != "")
		{
			$str = trim(strip_tags($str));
			$search = mysqli_real_escape_string($link, $str);
			$row = cacheSearch($search);
			if($row != false)
				return $row;

			$str = urlencode($str);
			$url = "https://maps.googleapis.com/maps/api/geocode/json?key=$gapikey&components=country:AU&address=$str";
			$places = file_get_contents($url);
			$json_data = json_decode($places, true);
			$row = cacheInsert($search, $json_data);
			return $row;
		}
	}

	function cacheSearch($search)
	{
		global $link;

		if($search == "")
			return false;

		$query = "select * from `poi` where `search`='$search'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) < 1)
			return false;

		$row = mysqli_fetch_assoc($res);
		$row['status'] = 'OK';
		return $row;
	}

	function cacheInsert($search, $json)
	{
		global $link;

		if($json == false)
			return false;

		$poi = mysqli_real_escape_string($link, $json['results']['0']['place_id']);
		if($poi == "")
			return false;

		$query = "select * from `poi` where `poi`='$poi'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) >= 1)
			return mysqli_fetch_assoc($res);

		$lat = floatval($json['results']['0']['geometry']['location']['lat']);
		$lng = floatval($json['results']['0']['geometry']['location']['lng']);

		if($lat == 0 || $lat < -90 || $lat > 90 || $lng == 0 || $lng < -180 || $lng > 180)
			return false;

		$address = mysqli_real_escape_string($link, $json['results']['0']['formatted_address']);
		if($address == "")
			return false;

		$areas = $json['results']['0']['address_components'];
		$council = "";
		foreach($areas as $key => $value)
		{
			if($areas[$key]['types']['0'] == "administrative_area_level_2")
			{
				$council = trim($areas[$key]['long_name']);
				break;
			}
		}

		if($council == "")
			return false;


		$query = "insert into `poi` set `poi`='$poi', `address`='$address', `council`='$council', `lat`='$lat', `lng`='$lng', `search`='$search'";
		mysqli_query($link, $query);

		$row = array();
		$row['search'] = $search;
		$row['poi'] = $poi;
		$row['address'] = $address;
		$row['council'] = $council;
		$row['lat'] = $lat;
		$row['lng'] = $lng;
		$row['status'] = 'OK';

		return $row;
	}

        function showPlace($place)
        {
                $row = getPlace($place);
                if($row == false)
                {
                        return "There was a problem looking up '$place'";
                        exit;
                }

                return "<a href='map.php?lat=".$row['lat']."&lng=".$row['lng']."'>".$row['address'].", ".$row['council']."</a>";
        }

	function getPasswordHash($password)
	{
		global $link;

		$password = password_hash(trim(strip_tags($password)), PASSWORD_ARGON2ID);
		return mysqli_real_escape_string($link, $password);
	}

	function comparePasswordHash($email, $password)
	{
		global $link;

		$email = mysqli_real_escape_string($link, $email);
		$password = trim(strip_tags($password));

		$query = "select `password` from `users` where `email`='$email'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) <= 0)
			return false;

		$row = mysqli_fetch_assoc($res);
		return(password_verify($password, $row['password']));
	}

	function isEmailInDB($email)
	{
		global $link;

		$email = mysqli_real_escape_string($link, trim(strip_tags($email)));
		$query = "SELECT 1 FROM `users` WHERE `email`='$email'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) >= 1)
			return true;
		return false;
	}

	function isNumberInDB($phoneNo)
	{
		global $link;
		$number = mysqli_real_escape_string($link, trim(strip_tags($phoneNo)));
		$query = "SELECT 1 FROM `users` WHERE `phone` = '$phoneNo'";
		$res = mysqli_query($link, $query);
		if(mysqli_num_rows($res) >= 1)
			return true;
		return false;
	}

	function registerUser($email, $password, $phoneNo, $name)
	{
		global $link;
		$email = mysqli_real_escape_string($link, trim(strip_tags($email)));
		$password = mysqli_real_escape_string($link, trim(strip_tags($password)));
		$phoneNo = empty($phoneNo) ? NULL : mysqli_real_escape_string($link, trim(strip_tags($phoneNo)));
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

	function active($current_page)
	{
		$url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
		$url = end($url_array);
		if(trim($current_page) == $url){
			return 'pure-menu-selected '; //class name in css
		}
		return '';
	}

	function getDefects()
	{
		global $link;

		$query = "select * from `defect_types` order by `id`";
		$res = mysqli_query($link, $query);

		$arr = array();

		while($row = mysqli_fetch_assoc($res))
			$arr[] = $row;

		$arr['status'] = "OK";
		echo json_encode($arr);
	}

	function cleanup($str)
	{
		global $link;

		return mysqli_real_escape_string($link, trim(strip_tags($str)));
	}

	function resizeAndStrip($filename, $output, $outputThumb)
	{
		if(!file_exists($filename))
			return false;

		$image = new Imagick($filename);
		$profiles = $image->getImageProfiles("icc", true);
		$orientation = $image->getImageOrientation();

		switch($orientation)
		{
			case imagick::ORIENTATION_BOTTOMRIGHT:
				$image->rotateimage("#000", 180);
				break;

			case imagick::ORIENTATION_RIGHTTOP:
				$image->rotateimage("#000", 90);
				break;

			case imagick::ORIENTATION_LEFTBOTTOM:
				$image->rotateimage("#000", -90);
				break;
		}

		$image->stripImage();

		$image->resizeImage(1200, 1200, imagick::FILTER_CATROM, 1, TRUE);

		if(!empty($profiles))
			$image->profileImage("icc", $profiles['icc']);

		$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

		$image->writeImage($output);

		$image->resizeImage(240, 240, imagick::FILTER_CATROM, 1, TRUE);

		if(!empty($profiles))
			$image->profileImage("icc", $profiles['icc']);

		$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

		$image->writeImage($outputThumb);
	}

	function getUUID()
	{
		$data = random_bytes(16);
		assert(strlen($data) == 16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	function createProblem($lat, $lng, $address, $council, $defect, $summary, $extra)
	{
		global $link;

		if(is_null($lat) || is_null($lng) || $lat == 0 || $lat < -90 || $lat > 90 || $lng == 0 || $lng < -180 || $lng > 180)
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid latitude or longitude.";
			echo json_encode($arr);
			exit;
		}

		if(is_null($address) || $address == "")
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid Address.";
			echo json_encode($arr);
			exit;
		}

		if(is_null($council) || $council == "")
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid Council.";
			echo json_encode($arr);
			exit;
		}

		if(is_null($defect) || $defect == "")
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid Defect.";
			echo json_encode($arr);
			exit;
		}

		if(is_null($summary) || $summary == "")
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid Summary.";
			echo json_encode($arr);
			exit;
		}

		if(is_null($extra) || $extra == "")
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid Description.";
			echo json_encode($arr);
			exit;
		}

		$email = $_SESSION['email'];
		$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT `id` FROM `users` WHERE `email`='$email'"));
		$userid = $row['id'];

		if($userid <= 0)
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "Invalid user.";
			echo json_encode($arr);
			exit;
		}
	
		if(count($_POST["uuid"]) < 2)
		{
			$arr['status'] = "FAIL";
			$arr['errmsg'] = "You failed to upload enough photos of the problem, or the photos were low quality.";
			echo json_encode($arr);
			exit;
		}
	}

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
		  <li class="'. active('reports.php') .'pure-menu-item">
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
		$header .= 	'<li class="'. active('login.php') .'pure-menu-item">
		<a href="./login.php" class="pure-menu-link">Sign in</a>
			</li>
			<li class="'. active('signup.php') .'pure-menu-item">
				<a href="./signup.php" class="pure-menu-link">Sign up</a>
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
