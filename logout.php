<?php
	session_destroy();
	$url = $_SERVER['SERVER_PROTOCOL'].$_SERVER['HTTP_HOST'];
	header("Location: $url");