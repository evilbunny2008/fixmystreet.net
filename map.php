<?php
  require_once('common.php');

  $uploads_dir = "photos";

  if(isset($_POST['submit']) && $_POST['submit'] === "Submit")
  {
    $lat = is_null($_POST['lat']) ? NULL : floatval($_POST['lat']);
    $lng = is_null($_POST['lng']) ? NULL : floatval($_POST['lng']);
    $address = is_null($_POST['address']) ? NULL : cleanup($_POST['address']);
    $council = is_null($_POST['council']) ? NULL : cleanup($_POST['council']);
    $defect = is_null($_POST['problem-type']) ? NULL : cleanup($_POST['problem-type']);
    $summary = is_null($_POST['summary']) ? NULL : ucfirst(cleanup($_POST['summary']));
    $extra = is_null($_POST['extra']) ? NULL : ucfirst(cleanup($_POST['extra']));

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
    $query .= "`site_id`=1,`extra`='$extra', `defect_id`=$defect";
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
	    $uuid = getUUID();
	    $filename = cleanup(urldecode(basename($_FILES["photos"]["name"][$key])));
	    resizeAndStrip($_FILES["photos"]["tmp_name"][$key], "${uploads_dir}/${uuid}.jpg", "${uploads_dir}/${uuid}_thumb.jpg");
	    $file_path = basename($uploads_dir)."/${uuid}.jpg";
	    $file_thumb = basename($uploads_dir)."/${uuid}_thumb.jpg";
	    $query = "INSERT INTO `photos` SET `problem_id`=$problem_id, `user_id`='$userid', `comment`='$filename', `file_path`='$file_path', `thumb`='$file_thumb'";
	    mysqli_query($link, $query);
	}
    }

    //PROBLEM HAS BEEN ADDED
    header("Location: map.php?lat=$lat&lng=$lng");
    exit;
  }

  if(isset($_POST['submit']) && $_POST['submit'] === "submit")
  {
	$problem_id = is_null($_POST['problemID']) ? NULL : cleanup($_POST['problemID']);
	$extra = is_null($_POST['update-extra']) ? NULL : cleanup($_POST['update-extra']);
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
	//DON'T FORGET PHOTOS CODE
	if(is_null($extra))
	{
		$msg = "Update field cannot be empty";
		exit;
	}
	$query = "INSERT INTO `comment` SET `problem_id`=$problem_id, `user_id`=$userid, `text`='$extra', `anonymous`=0";
	mysqli_query($link, $query);
	// $filename 

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
	<link rel="stylesheet" href="./css/splide.min.css" />
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon" />
    <script src="./js/ui.js"></script>
	<script src="./js/splide.min.js"></script>
    <script src="./js/index.php"></script>
    <script>
      let map;
      let marker;
      let markers = [];

	function initMap()
	{
        	map = new google.maps.Map(document.getElementById("map"), { center: { lat: <?=$lat?>, lng: <?=$lng?> }, zoom: 16, minZoom: 8, });
		google.maps.event.addListener(map, 'dragend', function()
		{
			loadProblems();
		});

		google.maps.event.addListener(map, 'tilesloaded', loadProblems);
	}

	async function uploadFile(lastPreview)
	{
		let formData = new FormData();
		formData.append("file", lastPreview, "dummy.jpg");
		let response = await fetch('/upload.php', {
			method: "POST",
			body: formData
		});
		let result = await response.json();
		alert(result.message);
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
				if(http1.responseText.trim() == "")
					return;

				removeAllMarkers();

				let ret = http1.responseText.trim().split("\n");
				for(let i = 0; i < ret.length; i++)
				{
					let bits = ret[i].split("|");
					let loc = { lat: parseFloat(bits[1].trim()), lng: parseFloat(bits[2].trim()) };
					let icon = "markers/red_marker.png";
					if(bits[6] == "orange")
						icon = "markers/orange_marker.png";
					else if(bits[6] == "yellow")
						icon = "markers/yellow_marker.png";
					else if(bits[6] == "grey")
						icon = "markers/grey_marker.png";
					else if(bits[6] == "green")
						icon = "markers/green_marker.png";
					let mark = new google.maps.Marker({ position: loc, map: map, title: bits[4] + ": " + bits[3], icon: icon, });
					google.maps.event.addListener(mark, 'click', function()
					{
						// alert("You clicked on " + bits[0]);
						//ONLY NEED id, DON'T NEED ANYTHING ELSE
						getExtra(bits[0]);
						if(reportProblem.style.display == 'none')
							hideShowReport();
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

	function hideShowReport()
	{
		const reportProblem = document.getElementById("reportProblem");
		const reportForm = document.getElementById("reportForm");

		if(reportForm.style.display == 'none')
		{
			reportForm.style.display = 'block';
			reportProblem.style.display = 'none';

			let reportInfo = document.querySelector(".reportInfo");
			if(reportInfo != undefined)
				reportInfo.setAttribute("hidden","");

			const lat = document.getElementById("lat");
			const lng = document.getElementById("lng");

			lat.value = map.getCenter().lat().toFixed(6);
			lng.value = map.getCenter().lng().toFixed(6);
			const markerloc = { lat: map.getCenter().lat(), lng: map.getCenter().lng() };
			marker = new google.maps.Marker({ position: markerloc, map: map, draggable:true });
			google.maps.event.addListener(marker, 'dragend', function() { dragEnd(marker.getPosition()); });

			const contentString = '<div id="content"><div id="bodyContent">Drag this marker to the location of the problem.</div></div>';
			const infowindow = new google.maps.InfoWindow({ content: contentString, });
			infowindow.open(map, marker);
			marker.addListener("click", () => { infowindow.open(map, marker); });
		} else {
			reportForm.style.display = 'none';
			reportProblem.style.display = 'block';
			let reportInfo = document.querySelector(".reportInfo");
			if(reportInfo != undefined)
				reportInfo.removeAttribute("hidden");
			marker.setMap(null);
		}
	}

	function showPic(c, pic)
	{
		document.getElementById('preview' + pic).src = window.URL.createObjectURL(c.files[0]);
		document.getElementById('preview' + pic).style.display = '';
	}

	function removeAllMarkers()
	{
		for(let i = 0; i < markers.length; i++)
			markers[i].setMap(null);
		markers = [];
	}

	function rm(img)
	{
		img.remove();
	}

	function createCarousel(photosList)
	{
		let len = photosList.length;
		let container = document.createElement("div");
		let cur = 0;
		container.className = "container";
		console.log(photosList);
		container.innerHTML += `<a class="next" >&#10095;</a>`;
		let img = document.createElement("img");
		img.className = "slide";
		img.src = switchImage();
		container.innerHTML += `<a class="prev" >&#10094;</a>`;
		container.appendChild(img);
		document.querySelector(".reportInfo").appendChild(container);
	}

	function switchImage()
	{
		let problemID = document.getElementById("problemID").value;
		http.open('GET', '/extra.php?id=' + problemID, true);
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				if(http.responseText.trim() == "")
					return;
				let row = JSON.parse(http.responseText.trim());
				photoList = row['photos'];
				let i = 0;
				const length = photoList.length;
				let img = document.querySelector(".slide");
				img.src = photoList[0]['thumb'];
				let next = document.querySelector(".next");
				next.addEventListener("click", function() {
					if(i+1 < length)
						i++;
					else
						i=0;

					let img = document.querySelector(".slide");
					img.src = photoList[i]['thumb'];
				});
				let prev = document.querySelector(".prev");
				prev.addEventListener("click", function() {
					if(i-1 >= 0)
						i--;

					else
						i=length-1;

					let img = document.querySelector(".slide");
					img.src = photoList[i]['thumb'];
				});

			}
		}
		http.send();
	}

	function previewFile(file, type)
	{
		let images = document.querySelector(".images");
		let img = document.createElement("img");
		img.setAttribute("onclick","rm(this)");
		let grid = document.querySelector(".pure-g");
		if(grid == null)
		{
			grid = document.createElement("div");
			grid.className = "pure-g";
		}
		img.className = "preview pure-u-1-4 is-center";
		img.setAttribute("name", "file")
		// img.style.width = "200px";
		img.style.marginRight = "5%";
		let exists = document.querySelectorAll(".pure-u-1-4");
		// console.log(exists.length);
		if(exists.length == 0 || exists.length % 3 === 0 && exists.length != 1)
			img.style.marginLeft = "5%";
		if(exists.length > 2)
			img.style.marginTop = "5%";
		images.appendChild(grid);
		grid.appendChild(img);

		switch (type)
		{
			//IF FILES ARE DRAGGED
			case 1:
				// console.log(file);
				let reader = new FileReader();
				reader.readAsDataURL(file);
				// console.log(file);
				uploadFile(file);
				reader.onloadend = function() {
					img.src = reader.result;
				}
				break;
			//IF FILES ARE CHOSEN THROUGH INPUT
			case 2:
				// console.log(file);
				img.src = URL.createObjectURL(event.target.files[0]);
				uploadFile(event.target.files[0]);
				img.onload = function() {
					URL.revokeObjectURL(img.src);
				}
				break;
		}
		img.removeAttribute("hidden");
		const submit = document.getElementById("submit");
		if(exists.length > 2 && submit != null)
		{
			submit.removeAttribute("disabled");
		}
	}

	function getExtra(id)
	{
		http.open('GET', '/extra.php?id=' + id, true);
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				if(http.responseText.trim() == "")
					return;

				let row = JSON.parse(http.responseText.trim());
				// do something with row...
				let parent = document.getElementById('reportProblem');
				if(parent.nextElementSibling.tagName != "DIV")
				{
					const reportInfo = document.createElement('div');
					reportInfo.className = "reportInfo";
					parent.after(reportInfo);
				}
				const reportInfo = document.querySelector(".reportInfo");
				reportInfo.innerHTML = '';
				reportInfo.innerHTML += `<form method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']?>" >`
				reportInfo.innerHTML += `<input type="hidden" id="problemID" name="problemID" value="${id}">`
				reportInfo.innerHTML += `<p class="title">${row['summary']}</p>`;
				reportInfo.innerHTML += `<p class="created">Created on ${row['created']}</p>`;
				reportInfo.innerHTML += `<p class="updated">Last updated on ${row['lastupdate']}</p>`;
				reportInfo.innerHTML += `<p class="summary">${row['extra']}</p> `;
				// reportInfo.innerHTML += `<img class="img1" height="200px" width="200px" src="${row['photos'][0]['file_path']}">`;
				createCarousel(row['photos']);
				reportInfo.innerHTML += `<h3>Have an update?</h3>`;
				reportInfo.innerHTML += `<label>Photos (if any)</label>`;
				reportInfo.innerHTML += `<div class="file-drop" ondrop=""> Drag or click here to choose files <input type="file" accept="image/jpeg" id="myFiles" multiple style="display:none;" onchange="previewFile(event,2)"></div>`;
				reportInfo.innerHTML += `<br /><br/><br/>`;
				reportInfo.innerHTML += `<p>HINT: Click on images to remove them!</p>`;
				reportInfo.innerHTML += `<div class="images">`;
				reportInfo.innerHTML += `</div>`;
				reportInfo.innerHTML += `<br /><br/><br/>`;
				<?php if(isset($msg)){?>
				reportInfo.innerHTML += `<p><?=$msg?></p>`;
				<?php } ?>
				reportInfo.innerHTML += `<label for="update-text">Update</label>`;
				reportInfo.innerHTML += `<br /><br/>`;
				reportInfo.innerHTML += `<textarea name="update" id="update-text" cols="40"rows="10" style="border-radius: 8px; resize:none;"></textarea>`;
				reportInfo.innerHTML += `<br /><br/>`;
				<?php
					if(isset($_SESSION['loggedin']))
					{
				?>
						reportInfo.innerHTML += `<button href="#" name="submit" type="submit" value="submit" class="pure-button" id="submit" disabled>Submit</buttons>`;
				<?php
					} else {
				?>
						reportInfo.innerHTML += `<p>You <a href='https://fixmystreet.net/signup.php'>need an account</a> and to be <a href='https://fixmystreet.net/login.php'>logged in</a> to make reports</p>`;
				<?php
					}
				?>
				reportInfo.innerHTML += `</form>`;
				// const menu = document.getElementById("menu");
				// menu.scrollTop = menu.scrollHeight;
				const fileDrag = document.querySelector(".file-drop");
				const fileChoose = document.getElementById("myFiles");

				fileDrag.addEventListener("click", function() {
					fileChoose.click();
				});

				fileDrag.addEventListener("dragover", function() {
					event.preventDefault();
				});
				fileDrag.addEventListener("drop", function() {
					//GET THE FILE DATA;
					event.preventDefault();
					if(event.dataTransfer.items)
					{
						event.preventDefault();
						for (let i = 0; i < event.dataTransfer.items.length; i++)
						{
							// If dropped items aren't files, reject them
							if (event.dataTransfer.items[i].kind === 'file' && event.dataTransfer.items[i].type == "image/jpeg")
							{
								let file = event.dataTransfer.items[i].getAsFile();
								//DO THINGS WITH FILE HERE

								previewFile(file,1);
							}
							else
							{
								// console.log(event.dataTransfer.items[i].type);
								//REPLACE WITH MODAL
								alert("Only jpegs/jpgs are allowed");
								break;
							}
    					}
					}
					// document.querySelector(".img1").src = files[0]
				});
				// reportInfo.innerHTML += ``;
				title = document.querySelector(".title");
				title.style.fontWeight = "bold";
				reportInfo.style.textAlign = "center";
			}
		}

		http.send();
	}

	function init()
	{
		const fileDrag = document.querySelector(".file-drop");
		const fileChoose = document.getElementById("myFiles");

		fileDrag.addEventListener("click", function() {
			fileChoose.click();
		});

		fileDrag.addEventListener("dragover", function() {
			event.preventDefault();
		});

		fileDrag.addEventListener("drop", function() {
			//GET THE FILE DATA;
			event.preventDefault();
			if(event.dataTransfer.items)
			{
				event.preventDefault();
				for (let i = 0; i < event.dataTransfer.items.length; i++)
				{
					// If dropped items aren't files, reject them
					if (event.dataTransfer.items[i].kind === 'file' && event.dataTransfer.items[i].type == "image/jpeg")
					{
						let file = event.dataTransfer.items[i].getAsFile();
						//DO THINGS WITH FILE HERE

						previewFile(file,1);
					}
					else
					{
						// console.log(event.dataTransfer.items[i].type);
						//REPLACE WITH MODAL
						alert("Only jpegs/jpgs are allowed");
						break;
					}
				}
			}
			// document.querySelector(".img1").src = files[0]
		});
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

	  <p id="reportProblem" onClick="hideShowReport()">Click here to report a problem</p>
          <form action="<?= $_SERVER['PHP_SELF']?>" id="reportForm" method="post" enctype="multipart/form-data" style="display:none">
	  <p style="margin: 0;padding: 16px;background: #00bd08;" onClick="hideShowReport()"> &#10096; Go back to the list of problems</p>
          <ul class="pure-menu-list">
            <li class="pure-menu-item">
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
              <a onclick="init()" href="#" class="pure-menu-link">Step 3</a>
              <div id="step-three" hidden>
                <p class="is-center">
                  Add photos that clearly show the problem
                </p>
                  <!-- <img id="preview1" alt="Image 1 Preview" width="100" height="100" style="display:none" />
                  <input onchange="showPic(this, '1');" type="file" id="myFile1" name="photos[]" /><br/>
                  <img id="preview2" alt="Image 2 Preview" width="100" height="100" style="display:none" />
                  <input onchange="showPic(this, '2');" type="file" id="myFile2" name="photos[]" /> -->
				  <!-- <div class="file-drop" ondrop=""> Drag or click here to choose files</div>
				  <input type="file" accept="image/jpeg" id="myFiles" multiple onchange="previewFile(event,2)">
				  <br /><br/>
				  <div class="images">
				  </div>
                  <br>
                  <br> -->
				<div class="file-drop" ondrop=""> Drag or click here to choose files
					<input type="file" accept="image/jpeg" id="myFiles" name="file" multiple style="display:none;" onchange="previewFile(event,2)">
				</div>
				<br /><br/><br/>
				<div class="images">
				</div>
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
