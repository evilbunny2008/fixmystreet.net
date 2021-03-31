<?php
	require_once('common.php');

	$row = getAddress($_GET['lat'], $_GET['lng']);
	if($row == false)
	{
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid latitude or longitude";
                echo json_encode($arr);
                exit;
	}

	echo $row['address']."|".$row['council'];
