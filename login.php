<?php
    require_once('common.php');

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 1)
    {
        $url = $_SERVER['SERVER_PROTOCOL'].$_SERVER['HTTP_HOST'];
        header("Location: $url");
    } 
    if (isset($_POST['submit']))
    {
        if (isset($_POST['hiddenval']) && $_POST['hiddenval'] === '1')
        {
            $email = empty($_POST['email']) ? NULL : htmlentities($_POST['email']);
            $password = empty($_POST['password']) ? NULL : mysqli_real_escape_string($link, $_POST['password']); 
            if ($email == NULL || $password == NULL || !filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $msg = _("An error occurred");
            } 
            else
            {
                if(comparePasswordHash($email, $password))
                {
                  $_SESSION['loggedin'] = 1;
                  $_SESSION['email'] = $email;
                }
                else
                {
                    $msg = _("Authorization failed");
                }
            }
        }
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
  <link rel="stylesheet" href="./css/styles.css" />
</head>

<body>

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
            <li class="pure-menu-item">
              <a href="./reports.php" class="pure-menu-link">All reports</a>
            </li>
            <li class="pure-menu-item">
              <a href="#" class="pure-menu-link">Local alerts</a>
            </li>
            <li class="pure-menu-item  pure-menu-selected">
              <a href="" class="pure-menu-link">Sign in</a>
            </li>
            <li class="pure-menu-item">
              <a href="./signup.php" class="pure-menu-link">Sign up</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="dash-header">
        <div class="splash">
          <h1 class="splash-head">Login</h1>
        </div>
      </div>
      <div class="dash-content-wrapper">
        <div id="signup-content" class="content">
          <div class="pure-g" id="signup-form">
            <div class="pure-u-1-3"></div>
            <div class="pure-u-1-3">
              <form action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
                <?php
                  if ($msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <input type="hidden" value="1" name="hiddenval">
                  <div class="pure-control-group">
                    <label for="aligned-email">Email Address</label>
                    <input type="email" name="email" id="aligned-email" placeholder="Email Address" />
                  </div>
                  <div class="pure-control-group">
                    <label for="aligned-password">Password</label>
                    <input type="password" name="password" id="aligned-password" placeholder="Password" />
                  </div>
                  <div class="pure-controls">
                    <button type="submit" name="submit" class="pure-button pure-button-primary">
                      Submit
                    </button>
                  </div>
                  <a href="./reset.php">Forgot password?</a>
                </fieldset>
              </form>
            </div>
            <div class="pure-u-1-3"></div>
          </div>
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
  </body>
</body>

</html>