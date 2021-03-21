<?php
	require_once('mysql.php');
	$hash = mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['hash'])));
	$uid = intval(mysqli_real_escape_string($conn, trim(strip_tags($_REQUEST['uid']))));
	if ($hash == "" || is_null($uid))
	{
		echo "Unknown error";
	}
	else
	{
		$sql = "SELECT * FROM `token` WHERE `user_id`=$uid AND `token`='$hash'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res) === 1)
		{
			$sql = "UPDATE `token` SET `token` = NULL WHERE uid = $uid";
			header('location: verified.html');
		}
		else
		{
			echo "An unexpected error occurred.";
		}
	}