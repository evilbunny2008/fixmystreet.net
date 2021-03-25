<html>
<head>
<script src="ol.js"></script>
<link rel="stylesheet" href="ol.css">
<style>
    .map {
      height: 400px;
      width: 100%;
    }
  </style>
</head>
<body>
<h2>My Map</h2>
    <div id="map" class="map"></div>
    <script type="text/javascript">
      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          })
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([37.41, 8.82]),
          zoom: 4
        })
      });
    </script>
</body>
</html>
