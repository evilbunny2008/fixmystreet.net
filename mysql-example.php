<?php
	session_start();
	$database = "database";
	$username = "username";
	$password = "password";
	$hostname = "localhost";
	$gapikey  = "go to https://developers.google.com/maps/documentation/places/web-service/autocomplete#place_types to get an api key";
	$serverKey= "make something up here.";

	$link = new mysqli($hostname, $username, $password, $database);
