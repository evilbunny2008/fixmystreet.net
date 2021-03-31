<?php
	require_once('../common.php');

        if(!in_array($_GET['serverKey'], $serverKeys, true))
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid server key";
                echo json_encode($arr);
                exit;
        }

        $email = strip_tags(trim($_GET['email']));
        $password = strip_tags(trim($_GET['password']));

        if(!comparePasswordHash($email, $password))
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Invalid username and/or password.";
                echo json_encode($arr);
                exit;
        }

	echo getDefects();
