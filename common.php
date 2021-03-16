<?php

function getAddress($lat, $lng)
{
	$lat = floatval($lat);
	$lng = floatval($lng);
	if($lat != 0 && $lat >= -90 && $lat <= 90 && $lng != 0 && $lng >= -180 && $lng <= 180)
	{
		$address = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true");
		$json_data = json_decode($address);
print_r($json_data);
exit;
		$full_address = $json_data->results[0]->formatted_address;
		return;
	}

	echo "Invalid latitude or longitude";
	return;
}
