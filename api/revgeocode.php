<?php
	require_once('../common.php');

        if($_GET['severKey'] != $serverKey)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid server key";
                echo json_encode($arr);
                exit;
        }

	$row = getAddress($_GET['lat'], $_GET['lng']);
	$row = json_encode($row);
	echo $row;
