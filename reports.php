<?php
    require_once('common.php');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="A layout example that shows off a responsive product landing page."
    />
    <title>Dashboard</title>
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="css/pure/pure-min.css" />
    <link rel="stylesheet" href="css/pure/grids-responsive-min.css" />
    <!-- <script defer src="/js/Chart.bundle.min.js"></script> -->
    <script defer src="fontawesome-free-5.15.2-web/js/all.min.js"></script>
    <link
      rel="stylesheet"
      href="fontawesome-free-5.15.2-web/css/all.min.css"
    />
    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
      <?= $header?>
      <div class="dash-header">
        <div class="splash">
          <h1 class="splash-head">Dashboard</h1>
        </div>
      </div>
      <div class="dash-content-wrapper">
        <div class="content">
          <div class="pure-g">
            <div class="pure-u-1-2">
              <div class="l-box">
                <p class="is-center">graph 1</p>
              </div>
            </div>
            <div class="pure-u-1-2">
              <div class="l-box">
                <p class="is-center">graph 2</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr />
    <?=$footer?>
  </body>
</html>
