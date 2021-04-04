<?php
  require_once('common.php');

  if(isset($_POST['submit']) && $_POST['submit'] === "Submit")
  {
    $lat = is_null($_POST['lat']) ? NULL : floatval($_POST['lat']);
    $lng = is_null($_POST['lng']) ? NULL : floatval($_POST['lng']);
    $address = is_null($_POST['address']) ? NULL : cleanup($_POST['address']);
    $council = is_null($_POST['council']) ? NULL : cleanup($_POST['council']);
    $defect = is_null($_POST['problem-type']) ? NULL : cleanup($_POST['problem-type']);
    $summary = is_null($_POST['summary']) ? NULL : cleanup($_POST['summary']);
    $extra = is_null($_POST['extra']) ? NULL : cleanup($_POST['extra']);

    if(is_null($lat) || is_null($lng) || $lat == 0 || $lat < -90 || $lat > 90 || $lng == 0 || $lng < -180 || $lng > 180)
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid latitude or longitude.";
	echo json_encode($arr);
	exit;
    }

    if(is_null($address) || $address == "")
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid Address.";
	echo json_encode($arr);
	exit;
    }

    if(is_null($council) || $council == "")
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid Council.";
	echo json_encode($arr);
	exit;
    }

    if(is_null($defect) || $defect == "")
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid Defect.";
	echo json_encode($arr);
	exit;
    }

    if(is_null($summary) || $summary == "")
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid Summary.";
	echo json_encode($arr);
	exit;
    }

    if(is_null($extra) || $extra == "")
    {
	$arr['status'] = "FAIL";
	$arr['errmsg'] = "Invalid Description.";
	echo json_encode($arr);
	exit;
    }

    $email = $_SESSION['email'];
    $row = mysqli_fetch_assoc(mysqli_query($link, "SELECT `id` FROM `users` WHERE `email`='$email'"));
    $userid = $row['id'];

    if($userid <= 0)
    {
	$arr['status'] = "FAIL";
        $arr['errmsg'] = "Invalid user.";
        echo json_encode($arr);
        exit;
    }

    $file_count = 0;
    foreach($_FILES["photos"]["error"] as $key => $error)
    {
        if($error == UPLOAD_ERR_OK)
        {
            if(is_uploaded_file($_FILES["photos"]["tmp_name"][$key]) &&
                       filesize($_FILES["photos"]["tmp_name"][$key]) > 50000)
            {
                $file_count++;
            }
        }
    }

    if($file_count < 2)
    {
        $arr['status'] = "FAIL";
        $arr['errmsg'] = "You failed to upload enough photos of the problem, or the photos were low quality.";
        echo json_encode($arr);
        exit;
    }

    $query  = "INSERT INTO `problem` SET `latitude`=$lat, `longitude`=$lng, `address`='$address', `council`='$council', `summary`='$summary', `user_id`=$userid, ";
    $query .= "`extra`='$extra', `defect_id`=$defect";
    mysqli_query($link, $query);
    $problem_id = mysqli_insert_id($link);

    if($problem_id <= 0)
    {
        $arr['status'] = "FAIL";
        $arr['errmsg'] = "Error inserting into database...";
        echo json_encode($arr);
        exit;
    }

    foreach($_FILES["photos"]["error"] as $key => $error)
    {
	if($error == UPLOAD_ERR_OK)
	{
	    $filename = cleanup(urldecode(basename($_FILES["photos"]["name"][$key])));
	    resizeAndStrip($_FILES["photos"]["tmp_name"][$key], "photos/${problem_id}-${key}.jpg", "photos/${problem_id}-${key}-thumb.jpg");
	    $file_path = basename($uploads_dir)."/${problem_id}-${key}.jpg";
	    $file_thumb = basename($uploads_dir)."/${problem_id}-${key}-thumb.jpg";
	    $query = "INSERT INTO `photos` SET `problem_id`=$problem_id, `comment`='$filename', `file_path`='$file_path', `thumb`='$file_thumb'";
	    mysqli_query($link, $query);
	}
    }

    //PROBLEM HAS BEEN ADDED
    header("Location: map.php?lat=$lat&lng=$lng");
    exit;
  }

	$lat = -34.397;
	if(isset($_REQUEST['lat']) && $_REQUEST['lat'] != "" && floatval($_REQUEST['lat']) != 0 && floatval($_REQUEST['lat']) >= -90 && floatval($_REQUEST['lat']) <= 90)
		$lat = floatval($_REQUEST['lat']);

	$lng = 150.644;
	if(isset($_REQUEST['lng']) && $_REQUEST['lng'] != "" && floatval($_REQUEST['lng']) != 0 && floatval($_REQUEST['lng']) >= -180 && floatval($_REQUEST['lng']) <= 180)
		$lng = floatval($_REQUEST['lng']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report your problem</title>
    <link rel="stylesheet" href="./css/pure/pure-min.css" />
    <link rel="stylesheet" href="./css/styles.css" />
    <link rel="stylesheet" href="./css/sidebar.css" />
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon" />
    <script src="./js/ui.js"></script>
    <script src="./js/index.php"></script>
    <script>
      let map;
      let marker;
      let markers = [];

	function initMap()
	{
        	map = new google.maps.Map(document.getElementById("map"), { center: { lat: <?=$lat?>, lng: <?=$lng?> }, zoom: 16, });
		google.maps.event.addListener(map, 'dragend', function()
		{
			loadProblems();
		});
        	const markerloc = { lat: <?=$lat?>, lng: <?=$lng?> };
        	marker = new google.maps.Marker({ position: markerloc, map: map, draggable:true });

	        google.maps.event.addListener(marker, 'dragend', function()
        	{
			dragEnd(marker.getPosition());
	        });
		loadProblems();
	}

	function loadProblems()
	{
		let http1 = getHTTPObject();

		if(map.getBounds() === undefined)
			return;
		if(map.getBounds().getNorthEast() === undefined)
			return;
		if(map.getBounds().getNorthEast().lat() === undefined)
			return;

		aNorth = map.getBounds().getNorthEast().lat();
		aEast  = map.getBounds().getNorthEast().lng();
		aSouth = map.getBounds().getSouthWest().lat();
		aWest  = map.getBounds().getSouthWest().lng();

		http1.open('GET', '/problems.php?north=' + aNorth + "&east=" + aEast + "&south=" + aSouth + "&west=" + aWest, true);
		http1.onreadystatechange = function()
		{
			if(http1.readyState == 4 && http1.status == 200)
			{
				let ret = http1.responseText.trim().split("\n");
				for(let i = 0; i < ret.length; i++)
				{
					let bits = ret[i].split("|");
					let loc = { lat: parseFloat(bits[1].trim()), lng: parseFloat(bits[2].trim()) };
					let mark = new google.maps.Marker({ position: loc, map: map, title: bits[3], });
					google.maps.event.addListener(mark, 'click', function()
					{
						alert("You clicked on " + bits[0]);
					});
					markers.push(mark);
				}
			}
		}

		http1.send();
	}

	function dragEnd(pos)
	{
		const lat = document.getElementById("lat");
		const lng = document.getElementById("lng");

		lat.value = pos.lat().toFixed(6);
		lng.value = pos.lng().toFixed(6);
	}

	let http = getHTTPObject();

	function revgeocode()
	{
		const lat = document.getElementById("lat");
		const lng = document.getElementById("lng");
		const address = document.getElementById("address");
		const council = document.getElementById("council");

		http.open('GET', '/revgeocode.php?lat=' + lat.value + "&lng=" + lng.value, true);
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				let ret = http.responseText.split('|');
				address.value = ret[0];
				council.value = ret[1];
			}
		}

		http.send();
	}

	function getHTTPObject()
	{
		let request = null;
		return new XMLHttpRequest();
	}
    </script>
    <style>
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body onload="showSteps()">
    <div id="layout">
      <!-- Menu toggle -->
      <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
      </a>

      <div id="menu">
        <div class="pure-menu">
          <a class="pure-menu-heading" href="/">Go home</a>

          <ul class="pure-menu-list">
            <li class="pure-menu-item">
            <form action="<?= $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
              <a href="#" class="pure-menu-link">Step 1</a>
              <div id="step-one">
                <p class="is-center">Drag the <b>red marker</b> on the map</p>
                <label class="step" for="lat">Latitude</label>
                <input type="text" name="lat" id="lat" value="<?=$lat?>" readonly />
                <br />
                <label class="step" for="lng">Longitude</label>
                <input type="text" name="lng" id="lng" value="<?=$lng?>" readonly />
                <br>
                <br>
              </div>
            </li>
            <li class="pure-menu-item">
              <a href="#" onClick="revgeocode()" class="pure-menu-link">Step 2</a>
              <div id="step-two" hidden>
                <p class="is-center">
                  Select the type of problem and add it's details
                </p>
		<label class="step">Address:</label>
		<input type="text" id="address" name="address" value="<?=cleanup($_POST['address'])?>" readonly /><br/>
		<label class="step">Council:</label>
		<input type="text" id="council" name="council" value="<?=cleanup($_POST['council'])?>" readonly /><br/>
                <label for="p-type" class="step"> Choose a problem type</label>
                <select name="problem-type" id="p-type">
<?php
	$query = "select * from `defect_type`";
	$res = mysqli_query($link, $query);
	while($row = mysqli_fetch_assoc($res))
	{
		echo "\t\t<option value='".$row['id']."'";
		if(isset($_POST['problem-type']) && $_POST['problem-type'] > 0 && $row['id'] == $_POST['problem-type'])
			echo " selected";
		else if($row['id'] == 11)
			echo " selected";

		echo ">".$row['defect']."</option>\n";
	}
?>
                </select>
                <p class="is-center">Add a summary of the problem</p>
	        <input name="summary" onchange="validate()" type="text" id="summary" size="86" value="<?=cleanup($_POST['summary'])?>" />
                <p class="is-center">Add a description for the problem</p>
                <textarea onchange="validate()"
                  name="extra"
                  id="extra"
                  cols="89"
                  rows="10"
                  placeholder="Enter a problem description"
                ><?=cleanup($_POST['extra'])?></textarea>
                <br>
              </div>
            </li>

            <!-- <li class="pure-menu-item menu-item-divided pure-menu-selected">
              <a href="#" class="pure-menu-link">Services</a>
            </li> -->
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Step 3</a>
              <div id="step-three" hidden>
              <img id="preview1" alt="Image 1 Preview" width="100" height="100" />
              <img id="preview2" alt="Image 2 Preview" width="100" height="100" />
                <p class="is-center">
                  Add photos that clearly show the problem
                </p>
                  <input onchange="document.getElementById('preview1').src = window.URL.createObjectURL(this.files[0]);" type="file" id="myFile1" name="photos[]" /><br/>
                  <input onchange="document.getElementById('preview2').src = window.URL.createObjectURL(this.files[0]);" type="file" id="myFile2" name="photos[]" />
                  <br>
                  <br>
              </div>
            </li>

            <li class="pure-menu-item">
<?php
	if(isset($_SESSION['loggedin']))
	{
?>
              <button href="#" name="submit" type="submit" value="Submit" class="pure-button" id="submit" disabled>Submit</buttons>
<?php
	} else {
?>
		<p>You <a href='https://fixmystreet.net/signup.php'>need an account</a> and to be <a href='https://fixmystreet.net/login.php'>logged in</a> to make reports</p>
<?php
	}
?>
            </li>
          </ul>
          </form>

        </div>
      </div>

      <div id="main">
        <div class="content">
          <div class="splash-container" style="top: 0; width: 66%; left:34%">
            <div id="map"></div>
            <div class="splash"></div>
          </div>
        </div>
      </div>
      <script
        src="https://maps.googleapis.com/maps/api/js?key=<?=$mapkey?>&callback=initMap&libraries=&v=weekly"
        async
      ></script>
    </div>
  </body>
</html>
