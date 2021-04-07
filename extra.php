<?php
	require_once('common.php');

	if(!isset($_SERVER['HTTP_REFERER']) || strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen($refererurl))) != $refererurl)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid latitude or longitude";
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

	$query = "SELECT * FROM `problem` WHERE `id`=$id";
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
