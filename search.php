<?php
	require_once("common.php");

	function showPlace($place)
	{
		$row = getPlace($place);
		if($row == false)
		{
			return "There was a problem looking up '$place'";
			exit;
		}

		return "<a href='map.php?lat=".$row['lat']."&lng=".$row['lng']."'>".$row['address'].", ".$row['council']."</a>";
	}
