<?php
	require_once('common.php');

	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
	{
		header("Location: https://fixmystreet.net");
		exit;
	}
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
</head>

<body>
  <?php
    if(isset($_POST['submit']))
    {
      $place = empty(trim($_POST['place'])) ? NULL : mysqli_real_escape_string($link, $_POST['place']);
      if($place == NULL)
      {
        $msg = "Input cannot be empty.";
      }
      else
      {
        echo $header;
        ?>
    <div class="splash-container">
      <div class="splash">
      <h1 id="main-message" class="splash-head">Report, view, or discuss local problems</h1>
      <p class="splash-subhead">
        (like graffiti, illegal dumping, broken paving slabs, or street
        lighting)
      </p>
      <div class="pure-u-1 pure-u-md-2-3">
        Enter a nearby street name and area: e.g. ‘George Street, Sydney, NSW’
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="pure-form">
          <fieldset>
            <br>
            <input type="hidden" name="hiddenval" value="1">
          <?php
                  if(isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
            <input type="text" name="place" class="pure-input-rounded" />
            <button type="submit" name="submit" class="pure-button">Search</button>
          </fieldset>
        </form>
      </div>
        <div style="background-color: white; height:100%; border-radius: 16px;">
          <ul>
            <li>ITEM 1</li>
            <li>ITEM 2</li>
            <li>ITEM 3</li>
          </ul>
        </div>
      </div>
    </div>
    <div style="top: 85%;" class="content-wrapper">
        <?php
        echo $footer;
        die;
        ?>
    </div>
    </div>
      <?php
      }  
    }
  ?>
  <div class="header">
    <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
      <a class="pure-menu-heading" href="">FixMyStreet.net</a>
      <ul class="pure-menu-list">
        <li class="pure-menu-item pure-menu-selected">
          <a href="./index.php" class="pure-menu-link">Report a problem</a>
        </li>
        <li class="pure-menu-item">
          <a href="#" class="pure-menu-link">Help</a>
        </li>
        <li class="pure-menu-item">
          <a href="./reports.php" class="pure-menu-link">All reports</a>
        </li>
        <li class="pure-menu-item">
          <a href="#" class="pure-menu-link">Local alerts</a>
        </li>
        <?php
        if(isset( $_SESSION['loggedin']) && $_SESSION['loggedin'] === 1)
              {
                  echo '<li class="pure-menu-item">
                  <a href="./logout.php" class="pure-menu-link">Log out</a>
                  </li>';
              }
              else
              {
                  echo '        <li class="pure-menu-item">
                  <a href="./login.php" class="pure-menu-link">Sign in</a>
                </li>
                <li class="pure-menu-item">
                  <a href="./signup.php" class="pure-menu-link">Sign up</a>
                </li>';
              }
        ?>
      </ul>
    </div>
  </div>
  <div class="splash-container">
    <div class="splash">
      <h1 id="main-message" class="splash-head">Report, view, or discuss local problems</h1>
      <p class="splash-subhead">
        (like graffiti, illegal dumping, broken paving slabs, or street
        lighting)
      </p>
      <div class="pure-u-1 pure-u-md-2-3">
        Enter a nearby street name and area: e.g. ‘George Street, Sydney, NSW’
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="pure-form">
          <fieldset>
            <br>
            <input type="hidden" name="hiddenval" value="1">
          <?php
                  if(isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
            <input type="text" name="place" class="pure-input-rounded" />
            <button type="submit" name="submit" class="pure-button">Search</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      <h2 class="content-head is-center">How to report a problem</h2>

      <div class="pure-g">
        <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
          <h3 class="content-subhead">
            <i class="fa fa-road"></i>
            Enter a nearby postcode, or street name and area
          </h3>
          <!-- <p>Enter a nearby UK postcode, or street name and area</p> -->
        </div>
        <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
          <h3 class="content-subhead">
            <i class="fa fa-map-marker"></i>
            Locate the problem on a map of the area
          </h3>
          <!-- <p>
              Phasellus eget enim eu lectus faucibus vestibulum. Suspendisse
              sodales pellentesque elementum.
            </p> -->
        </div>
        <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
          <h3 class="content-subhead">
            <i class="fa fa-info-circle"></i>
            Enter details of the problem
          </h3>
          <!-- <p>Enter details of the problem</p> -->
        </div>
        <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
          <h3 class="content-subhead">
            <i class="fa fa-paper-plane"></i>
            We send it to the council on your behalf
          </h3>
          <!-- <p>We send it to the council on your behalf</p> -->
        </div>
      </div>
    </div>
    <hr />
    <div class="content">
      <h2 class="content-head is-center">FixMyStreet.net</h2>
      <p class="is-center">
        This version of FixMyStreet is written in PHP and runs on a MySQL
        database!
        <br />
        It is inspired by
        <a target="_blank" href="https://github.com/mysociety/fixmystreet">MySociety's FixMyStreet.com</a>
        <br />
        Would you like to contribute to FixMyStreet.net? Our code is open
        source and available on
        <a target="_blank" href="https://github.com/evilbunny2008/fixmystreet.net">github</a>
      </p>
    </div>
  </div>
</body>

</html>
