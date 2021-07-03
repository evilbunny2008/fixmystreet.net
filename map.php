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

	$problem_id = createProblem($lat, $lng, $address, $council, $defect, $summary, $extra);

	$filenames = array();
	foreach($_POST["images"] as $str)
	{
		$filename = explode("|", $str)[0];
		$filenames[$filename] = explode("|", $str)[1];
	}

	foreach($filenames as $uuid=>$filename)
	{
		$file = basename($uuid);

		if(file_exists("/tmp/$file.jpg"))
		{
			if(rename("/tmp/$file.jpg", "$uploads_dir/$file.jpg") && rename("/tmp/$file"."_thumb.jpg", "$uploads_dir/$file"."_thumb.jpg"))
			{

				if($problem_id <= 0)
				{
					$arr['status'] = "FAIL";
					$arr['errmsg'] = "Error inserting into database...";
					echo json_encode($arr);
					exit;
				}

				$file_path = "/$uploads_dir/$file".".jpg";
				$file_thumb = "/$uploads_dir/$file"."_thumb.jpg";

				$query = "INSERT INTO `photos` SET `problem_id`=$problem_id, `user_id`='$userid', `comment`='$filename', `file_path`='$file_path', `thumb`='$file_thumb'";
				mysqli_query($link, $query);
			}
			else
			{
				//UPDATE FAILED
				echo "An error occurred. If this issue persists, please contact us at ";
				exit;
			}
		}
	}

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

	if(is_null($extra))
	{
		$msg = "Update field cannot be empty";
		exit;
	}

	//UPDATE WORKED; REDIRECT TO UPDATE PAGE
	$query = "INSERT INTO `comment` SET `problem_id`=$problem_id, `user_id`=$userid, `text`='$extra', `anonymous`=0";
	mysqli_query($link, $query);
	$update_id = mysqli_insert_id($link);
	if(isset($_POST["images"]))
	{
		foreach($_POST["images"] as $str)
		{
			$filename = explode("|", $str)[0];
			$filenames[$filename] = explode("|", $str)[1];
		}
		foreach($filenames as $uuid=>$filename)
		{
			$file = basename($uuid);
			if(file_exists("/tmp/$file.jpg"))
			{
				if(rename("/tmp/$file.jpg", "$uploads_dir/$file.jpg") && rename("/tmp/$file"."_thumb.jpg", "$uploads_dir/$file"."_thumb.jpg"))
				{
					if($update_id <= 0)
					{
						$arr['status'] = "FAIL";
						$arr['errmsg'] = "Error inserting into database...";
						echo json_encode($arr);
						exit;
					}


					$file_path = "/$uploads_dir/$file".".jpg";
					$file_thumb = "/$uploads_dir/$file"."_thumb.jpg";

					$query = "INSERT INTO `comment_photos` SET `comment_id`=$update_id, `user_id`='$userid', `comment`='$filename', `file_path`='$file_path', `thumb`='$file_thumb'";
					mysqli_query($link, $query);


				}
				else
				{
					//UPDATE FAILED
					echo "An error occurred. If this issue persists, please contact us at ";
					exit;
				}
			}
		}
	}

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
    <link rel="stylesheet" href="/css/pure/pure-min.css" />
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/sidebar.css" />
	<link rel="stylesheet" href="/css/splide.min.css" />
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon" />
    <script async src="/js/ui.js"></script>
	<script async src="/js/splide.min.js"></script>
    <script async src="/js/index.php"></script>
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

	function updateProgress(evt)
	{
		if(evt.lengthComputable) {
			loading();
		}
	}
	
	async function uploadFile(lastPreview)
	{
		let formData = new FormData();
		let images = document.querySelector(".images");
		formData.append("photo", lastPreview);
		let req = getHTTPObject();
		if(req.upload)
			req.upload.onprogress = updateProgress;
		req.open("POST","/upload.php", true);
		req.onload = async function (e) {
		if (req.readyState === 4) {
			if (req.status === 200) {
				let result = JSON.parse(req.responseText);
				let uuid = result['uuid'];
				let filename = result['filename'];
				showModal(result['status']);
				if(result['status'] == "SUCCESS") {
					validate();
					let modalImg = document.querySelector(".modal-img");
					modalImg.src = "";
					let uuidField = document.createElement("input");
					uuidField.setAttribute("hidden","");
					uuidField.value = uuid + "|" + filename;
					uuidField.setAttribute("name", "images[]");
					uuidField.setAttribute("value", uuid + "|" + filename);
					images.appendChild(uuidField);
				}
			} else {
				showModal("An unexpected error occurred");
				let modalImg = document.querySelector(".modal-img");
				modalImg.src = "";
				document.querySelector(".preview:last-child").remove();
			}
		}
		};
		req.send(formData);
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
					let icon = "/markers/red_marker.png";
					if(bits[6] == "orange")
						icon = "/markers/orange_marker.png";
					else if(bits[6] == "yellow")
						icon = "/markers/yellow_marker.png";
					else if(bits[6] == "grey")
						icon = "/markers/grey_marker.png";
					else if(bits[6] == "green")
						icon = "/markers/green_marker.png";
					let mark = new google.maps.Marker({ position: loc, map: map, title: bits[4] + ": " + bits[3], icon: icon, });
					google.maps.event.addListener(mark, 'click', function()
					{
						// alert("You clicked on " + bits[0]);
						//ONLY NEED id, DON'T NEED ANYTHING ELSE
						getExtra(bits[0]);
						getComments(bits[0]);
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

	function getComments(id)
	{
		let http3 = getHTTPObject();
		http3.open('GET', '/comments.php?pid=' + id, true);
		http3.onreadystatechange = function()
		{
			if(http3.readyState == 4 && http3.status == 200)
			{
				let comments = JSON.parse(http3.responseText.split("\n"));
				console.log(comments);
				if(comments['status'] == "FAIL")
					return;
				const btn = document.getElementById("submit");
				const commentDiv = document.createElement("div");
				commentDiv.classList.add("commentDiv");

				comments.forEach(comment => {
					//CREATE ELEMENTS HERE TO POPULATE DIV
					let commentTag = document.createElement("p");
					commentTag.classList.add("comment");
					commentTag.innerHTML = comment["text"];
					commentTag.setAttribute("cid", comment.id);
					let lnbreak = document.createElement("br");
					commentTag.appendChild(lnbreak);
					commentDiv.append(commentTag);
					commentTag.append(`\t- ${comment["name"]} on ${comment["created"]}`);
				});
				console.log(commentDiv);
				setTimeout(function () {
					const container = document.querySelector(".container");
					container.insertAdjacentElement('afterend', commentDiv);
					for(const comment of comments) {
						if(comment.images.length != 0) {
							const c = document.querySelector(`[cid='${comment['id']}']`);
							const carousel = document.createElement("div");
							carousel.className = "container";
							c.insertAdjacentElement("afterend",carousel);
							const img = document.createElement("img");
							img.setAttribute("onclick","showImage(this.src)");
							img.src = comment.images[0];
							carousel.appendChild(img);
							if(comment.images.length > 1) {
								const next = document.querySelector(".next").cloneNode(true);
								const prev = document.querySelector(".prev").cloneNode(true);
								img.insertAdjacentElement("afterend", next);
								img.insertAdjacentElement("beforebegin", prev);
								next.addEventListener("click", function() {
									if (img.getAttribute("src") !== comment.images[comment.images.length-1]) {
										const index = comment.images.indexOf(img.getAttribute("src"));
										img.src = comment.images[index+1];
									}
									else
										img.src = comment.images[0];
								});
								prev.addEventListener("click", function() {
									if (img.getAttribute("src") !== comment.images[0]) {
										const index = comment.images.indexOf(img.getAttribute("src"));
										img.src = comment.images[index-1];
									}
									else
										img.src = comment.images[comment.images.length-1];								
									});
							}
						}
					}
				}, 2000)
			}
		}
		http3.send();
	}


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
			{
				reportInfo.setAttribute("hidden","");
				reportInfo.innerHTML = "";
			}
			const lat = document.getElementById("lat");
			const lng = document.getElementById("lng");
			history.replaceState({}, '', '/map.php');
			<?php if(isset($_SESSION['loggedin'])) { ?>
				init();
			<?php
			}
			?>
			document.title = "Report a problem";
			if(lat != null && lng != null) {
				lat.value = map.getCenter().lat().toFixed(6);
				lng.value = map.getCenter().lng().toFixed(6);
			}
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
			if (marker != undefined)
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
		const submit = document.getElementById("submit");
		let exists = document.querySelectorAll(".pure-u-1-4");
		if(exists.length <= 2)
			if(submit!=undefined)
				submit.setAttribute("disabled","");
		let uuid = img.getAttribute("uuid");
		let uuids = document.getElementsByName("uuid[]");
		for(let i = 0; i<uuids.length; i++) {
			if(uuids[i].value.split("|")[0] == uuid) {
				uuids[i].remove();
			}
		}
		img.remove();
	}

	function positionArrows(img)
	{
		// let img = document.querySelector(".slide");
		const next = document.querySelector(".next");
		img.insertAdjacentElement("afterend",next);
		const prev = document.querySelector(".prev");
		next.style.left = "20px";
		prev.style.right = "20px";
	}

	function createCarousel(photosList)
	{
		let len = photosList.length;
		let container = document.createElement("div");
		let cur = 0;
		container.className = "container";
		container.innerHTML += `<a class="next" >&#10095;</a>`;
		let img = document.createElement("img");
		img.className = "slide";
		img.setAttribute("onload","positionArrows(this)");
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
				img.addEventListener("click", function() {
					showImage(img.src);
				});
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

          <form action="<?= $_SERVER['PHP_SELF']?>" oninput="validate()" id="reportForm" method="post" enctype="multipart/form-data" style="display:none">
	  <p style="margin: 0;padding: 16px;background: #00bd08;" onClick="hideShowReport()"> &#10096; Go back to the list of problems</p>
          <ul class="pure-menu-list">
		  <?php
	if(isset($_SESSION['loggedin']))
	{
?>
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
	        <input name="summary" type="text" id="summary" size="86" value="<?=cleanup($_POST['summary'])?>" />
                <p class="is-center">Add a description for the problem</p>
                <textarea
                  name="extra"
                  id="extra"
                  cols="89"
                  rows="10"
                  placeholder="Enter a problem description"
                ><?=cleanup($_POST['extra'])?></textarea>
                <br>
              </div>
            </li>

            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Step 3</a>
              <div id="step-three" hidden>
                <p class="is-center">
                  Add photos that clearly show the problem
                </p>
				<div class="file-drop" ondrop=""> Drag or click here to choose files
					<input type="file" accept="image/jpeg" id="myFiles" name="photo" multiple style="display:none;" onchange="previewFile(event,2)">
				</div>
				<br /><br/><br/>
				<div class="images">
				</div>
              </div>
            </li>

            <li class="pure-menu-item">
              <button href="#" name="submit" type="submit" value="Submit" class="pure-button" id="submit" disabled>Submit</buttons>
<?php
	} else {
?>
		<p>You <a href='<?= $refererurl ?>signup.php'>need an account</a> and to be <a href='<?= $refererurl ?>login.php'>logged in</a> to make reports</p>
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
			<div id="myModal" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close">&times;</span>
					<p class="modal-text"></p>
					<img class="modal-img"></img>
				</div>
			</div>
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
