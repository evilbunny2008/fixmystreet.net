<?php
	require_once('common.php');
	$hash = mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['hash'])));
	$uid = intval(mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['uid']))));
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
			$sql = "UPDATE `token` SET `token` = NULL WHERE `uid` = $uid";
			mysqli_query($link, $sql);
			$sql = "UPDATE `users` SET `email_verified` = 1 WHERE `uid`= $uid";
			mysqli_query($link, $sql);
			header('Location: verified.html');
		}
		else
		{
			echo "An unexpected error occurred.";
		}
	}