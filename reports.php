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
    <link rel="stylesheet" href="./css/pure/pure-min.css" />
    <link rel="stylesheet" href="./css/pure/grids-responsive-min.css" />
    <!-- <script defer src="/js/Chart.bundle.min.js"></script> -->
    <script defer src="./fontawesome-free-5.15.2-web/js/all.min.js"></script>
    <link
      rel="stylesheet"
      href="./fontawesome-free-5.15.2-web/css/all.min.css"
    />
    <link rel="stylesheet" href="./css/styles.css" />
  </head>
  <body>
    <div class="flex-wrapper">
      <div class="header">
        <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
          <a class="pure-menu-heading" href="./index.php">FixMyStreet.net</a>
          <ul class="pure-menu-list">
            <li class="pure-menu-item">
              <a href="./index.php" class="pure-menu-link">Report a problem</a>
            </li>
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Help</a>
            </li>
            <li class="pure-menu-item pure-menu-selected">
              <a href="./reports.php" class="pure-menu-link">All reports</a>
            </li>
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Local alerts</a>
            </li>
            <li class="pure-menu-item">
              <a href="./login.php" class="pure-menu-link">Sign in</a>
            </li>
            <li class="pure-menu-item">
              <a href="./signup.php" class="pure-menu-link">Sign up</a>
            </li>
          </ul>
        </div>
      </div>

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
    <div class="content">
      <h2 class="content-head is-center">FixMyStreet</h2>
      <p class="is-center">
        This version of FixMyStreet is written in PHP and runs on a MySQL
        database!
        <br />
        It is inspired by
        <a target="_blank" href="https://github.com/mysociety/fixmystreet"
          >MySociety's FixMyStreet.com</a
        >
        <br />
        Would you like to contribute to FixMyStreet.net? Our code is open source
        and available on
        <a
          target="_blank"
          href="https://github.com/evilbunny2008/fixmystreet.net"
          >github</a
        >
      </p>
    </div>
  </body>
</html>
