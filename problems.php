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

	$query = "SELECT `problem`.`id` as `id`, `latitude`, `longitude`, `summary`, `defect`, `name`, `icon_colour` FROM `problem`, `defect_type`, `state` ";
	$query .= "WHERE `latitude` >= $south and `latitude` <= $north and `longitude` >= $west and `longitude` <= $east and ";
	$query .= "`problem`.`defect_id`=`defect_type`.`id` and `problem`.`state`=`state`.`id`";
	$res = mysqli_query($link, $query);
	while($row = mysqli_fetch_assoc($res))
	{
		echo "${row['id']}|${row['latitude']}|${row['longitude']}|${row['summary']}|${row['defect']}|${row['name']}|${row['icon_colour']}\n";
	}
