<?php
	session_start();
	$database = "database";
	$username = "username";
	$password = "password";
	$hostname = "localhost";
	$gapikey  = "go to https://developers.google.com/maps/documentation/places/web-service/autocomplete#place_types to get an api key";

	function genHash()
	{
		$rnd = fopen("/dev/urandom", "r");
		$hash = md5(fgets($rnd, 64));
		return $hash;
	}


	$link = new mysqli($hostname, $username, $password, $database);
