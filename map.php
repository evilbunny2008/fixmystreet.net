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
  <meta name="description" content="Report problems in your area so they can be fixed." />
  <title>FixMyStreet.net</title>
  <link rel="stylesheet" href="./css/pure/pure-min.css" />
  <link rel="stylesheet" href="./css/pure/grids-responsive-min.css" />
  <script defer src="./fontawesome-free-5.15.2-web/js/all.min.js"></script>
  <link rel="stylesheet" href="./fontawesome-free-5.15.2-web/css/all.min.css" />
  <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="./css/styles.css" />

  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
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
//	alert(pos);
//	TODO: show side bar with shire info etc
      }
  </script>
</head>

<body>
    <?=$header?>
      <div class="dash-header">
        <div class="splash">
          <h1 class="splash-head">Login</h1>
        </div>
      </div>

    <div class="splash-container">
      <div class="splash">
        <div id="map"></div>
      </div>
    </div>
    <?=$footer?>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhlxBCx0UGWd-GXPTxfovIwHCOejXP8GA&callback=initMap&libraries=&v=weekly&map_ids=fb9e22c9cd8fdff0"
      async
    ></script>
  </body>
</html>
