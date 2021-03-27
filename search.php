<?php
	require_once("common.php");

	function showPlace($place)
	{
		$json = getPlace($place);
		if($json == false)
		{
			return "There was a problem looking up '$place'";
			exit;
		}
	
	
		$staddress = $json['results']['0']['formatted_address'];
	
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
		if($staddress == "" || $council == "")
		{
			return "There was a problem looking up '$place', either 'street address' is blank or 'council' is blank.";
			exit;
		}
		return $staddress.$council;
	}