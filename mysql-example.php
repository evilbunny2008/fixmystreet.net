<?php
	$host = strtolower($_SERVER['HTTP_HOST']);
	if($host != strtolower("fixmystreet.net"))
	{
		header("Location: https://fixmystreet.net", TRUE, 301);
		exit;
	}

	session_start();
	$database = "database";
	$username = "username";
	$password = "password";
	$hostname = "localhost";
	$mapkey   = "Key for displaying the map";
	$gapikey  = "go to https://developers.google.com/maps/documentation/places/web-service/autocomplete#place_types to get an api key";
	$serverKeys = array("App key one", // Developer one's api key
			    "app key two"); // Developer twos' api key

	$link = new mysqli($hostname, $username, $password, $database);

	$refererurl = "https://fixmystreet.net/";
	$plainurl = "fixmystreet.net";