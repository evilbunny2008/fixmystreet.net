<?php
	require_once('common.php');

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
    <meta
      name="description"
      content="A layout example with a side menu that hides on mobile, just like the Pure website."
    />
    <title>Report your problem</title>
    <link rel="stylesheet" href="./css/pure/pure-min.css" />
    <link rel="stylesheet" href="./css/styles.css" />
    <link rel="stylesheet" href="./css/sidebar.css" />
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon" />
    <script src="./js/ui.js"></script>
    <script src="./js/index.js"></script>
    <script>
      let map;

      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
      	center: { lat: <?=$lat?>, lng: <?=$lng?> },
      	zoom: 12,
        });
       const markerloc = { lat: <?=$lat?>, lng: <?=$lng?> };
       const marker = new google.maps.Marker({ position: markerloc, map: map, draggable:true, animation: google.maps.Animation.DROP });

        google.maps.event.addListener(marker, 'dragend', function()
        {
      	 dragEnd(marker.getPosition());
        });
      }

      function dragEnd(pos)
      {
	//latField, lonField
	const lat = document.getElementById("latField");
	const lon = document.getElementById("lonField");

	lat.value = pos.lat().toFixed(6);
	lon.value = pos.lng().toFixed(6);
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
          <a class="pure-menu-heading" href="#">Go home</a>

          <ul class="pure-menu-list">
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Step 1</a>
              <div id="step-one" hidden>
                <p class="is-center">Drag the marker on the map</p>
                <label class="step" for="latField">Latitude</label>
                <input type="text" id="latField" />
                <br />
                <label class="step" for="lonField">Longitude</label>
                <input type="text" id="lonField" />
              </div>
            </li>
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Step 2</a>
              <div id="step-two" hidden>
                <p class="is-center">
                  Select the type of problem and add it's details
                </p>
                <label for="p-type" class="step"> Choose a problem type</label>
                <select name="problem-type" id="p-type">
                  <!-- POPULATE OPTIONS HERE WITH PHP -->
                </select>
                <p class="is-center">Add a description for the problem</p>
                <textarea
                  name=""
                  id=""
                  cols="30"
                  rows="10"
                  placeholder="Enter a problem description"
                ></textarea>
              </div>
            </li>

            <!-- <li class="pure-menu-item menu-item-divided pure-menu-selected">
              <a href="#" class="pure-menu-link">Services</a>
            </li> -->
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Step 3</a>
              <div id="step-three" hidden>
                <p class="is-center">
                  Add photos that clearly show the problem
                </p>
                <form action="">
                  <input type="file" id="myFile" name="filename" />
                  <input type="submit" value="Submit photos" />
                </form>
              </div>
            </li>

            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Submit</a>
            </li>
          </ul>
        </div>
      </div>

      <div id="main">
        <div class="content">
          <div class="splash-container" style="width: 66%; left:34%">
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
