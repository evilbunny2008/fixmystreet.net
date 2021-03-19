<?php
	session_destroy();
	$url = $_SERVER['REQUEST_SCHEME'].$_SERVER['HTTP_HOST'];
	header("Location: $url");