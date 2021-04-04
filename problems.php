<?php
	require_once('common.php');

	$north = floatval($_GET['north']);
	$east = floatval($_GET['east']);
	$south = floatval($_GET['south']);
	$west = floatval($_GET['west']);

	if($east == 0 || $east < -180 || $east > 180 || $north == 0 || $north < -90 || $north > 90 ||
	   $west == 0 || $west < -180 || $west > 180 || $south == 0 || $south < -90 || $south > 90 ||
	   $west >= $east || $south >= $north)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid latitude or longitude.";
		echo json_encode($arr);
		exit;
	}

	$query = "SELECT * FROM `problem` WHERE `lat` >= $south and `lat` <= $north and `lng` >= $west and `lng` <= $east"
	$res = mysqli_query($link, $query);
	while($row = mysqli_fetch_assoc($res))
	{
		echo "$row['id']|$row['lat']|$row['lng']|$row['summary']\n";
	}
