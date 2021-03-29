<?php
	require_once('../common.php');

	$row = getAddress($_GET['lat'], $_GET['lng']);
	$row = json_encode($row);
	echo $row;
