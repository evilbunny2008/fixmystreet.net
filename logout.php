<?php
	require_once('common.php');
	session_unset();
	// destroy the session
	session_destroy(); 
	header("Location: /");
	exit;