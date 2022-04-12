<?php
    require_once('common.php');

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 1)
    {
        header("Location: /");
    }
    if (isset($_POST['submit']))
    {
        if (isset($_POST['hiddenval']) && $_POST['hiddenval'] === '1')
        {
            $email = empty($_POST['email']) ? NULL : mysqli_real_escape_string($link, $_POST['email']);
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
	          header("Location: /");
		  exit;
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
  <link rel="stylesheet" href="/css/pure/pure-min.css" />
  <link rel="stylesheet" href="/css/pure/grids-responsive-min.css" />
  <script defer src="/fontawesome-free-5.15.2-web/js/all.min.js"></script>
  <link rel="stylesheet" href="/fontawesome-free-5.15.2-web/css/all.min.css" />
  <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="/css/styles.css" />
</head>

<body>

  <body>
    <?=$header?>
      <div class="dash-header">
        <div class="splash">
          <h1 id="sign-up">Login</h1>
          <div id="" class="">
          <div id="login-form">
              <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
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
                  <?php
                  if (isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <a class="hint" href="/reset.php">Forgot password?</a>
                </fieldset>
              </form>
          </div>
        </div>
        </div>
      </div>
      <!-- <div class="dash-content-wrapper"> -->
        <!-- <div id="signup-content" class="content"> -->
      <!-- </div> -->
    </div>
    <?=$footer?>
  </body>
</body>

</html>
