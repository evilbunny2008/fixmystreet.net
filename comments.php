<?php
	require_once('common.php');

        // if(!isset($_SERVER['HTTP_REFERER']) || strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen($refererurl))) != $refererurl)
        // {
        //         $arr['status'] = "FAIL";
        //         $arr['errmsg'] = "Invalid latitude or longitude";
        //         echo json_encode($arr);
        //         exit;
        // }

	header("Content-Type: text/plain");

        $pid = intval($_REQUEST['pid']);

        // $query = "SELECT * FROM `comment` WHERE `problem_id`=$pid";
        $query = "SELECT `comment`.*,`users`.`name` FROM `comment`, `users` WHERE `comment`.`problem_id`=$pid AND `comment`.`user_id` = `users`.`id`";
        $res = mysqli_query($link, $query);
        if(mysqli_num_rows($res) <= 0)
        {
                $arr['status'] = "FAIL";
                $arr['errmsg'] = "Can't retrieve comments!";  
                echo json_encode($arr);
                exit;      
        }
        $rows = array();
        while($row = mysqli_fetch_assoc($res)) {
                $images = array();
                $cid = $row['id'];
                $query = "SELECT * FROM `comment_photos` WHERE `comment_id`= $cid";
                $re = mysqli_query($link, $query);
                while($img = mysqli_fetch_assoc($re)) {
                        // var_dump($img);
                        if($cid == $row['id']) {
                                array_push($images, $img['thumb']);
                        }
                }
                $row['images'] = $images;
                array_push($rows, $row);
        }
        echo json_encode($rows);