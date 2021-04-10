<?php
	require_once('../common.php');

        if(!in_array($_GET['serverKey'], $serverKeys, true))
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid server key";
                echo json_encode($arr);
                exit;
        }

	header("Content-Type: text/plain");

	$id = intval($_GET['id']);
	if($id <= 0)
	{
		$arr['status'] = "FAIL";
		$arr['errmsg'] = "Invalid ID number.";
		echo json_encode($arr);
		exit;
	}

        $query = "SELECT `latitude`, `longitude`, `address`, `council`, `defect`, `summary`, `extra`, `lastupdate`, `created`, `name`, `icon_colour` FROM `problem`, `defect_type`, `state` ";
        $query .= "WHERE `problem`.`id`=$id and `problem`.`defect_id`=`defect_type`.`id` and `problem`.`state`=`state`.`id`";

	$res = mysqli_query($link, $query);
	$row = mysqli_fetch_assoc($res);
	$row['lastupdate'] = date("F j, Y, g:i a", strtotime($row['lastupdate']));
	$row['created'] = date("F j, Y, g:i a", strtotime($row['created']));

	$row['photos'] = array();

	$query = "SELECT * FROM `photos` WHERE `problem_id`=$id";
	$res = mysqli_query($link, $query);
	while($prow = mysqli_fetch_assoc($res))
		$row['photos'][] = $prow;

	$row['status'] = "OK";
	echo json_encode($row);
	exit;
