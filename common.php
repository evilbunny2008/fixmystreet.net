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
