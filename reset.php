<?php
	require_once('common.php');
	if(isset($_POST['submit']))
	{
		if(isset($_POST['hiddenval']) && $_POST['hiddenval'] === '1')
		{
			$email = empty(trim($_POST['email'])) ? NULL : htmlentities($_POST['email']);
			$password = empty(trim($_POST['password'])) ? NULL : htmlentities($_POST['password']);
			$cpassword = empty(trim($_POST['cpassword'])) ? NULL : htmlentities($_POST['cpassword']);
			$uid = intval(mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['uid']))));

			if($email == NULL || !filter_var($email,FILTER_VALIDATE_EMAIL))
			{
				$msg = _("Invalid input");
			}
			else if($password !== $cpassword)
			{
				$msg = _("The passwords you entered didn't match");
			}
			else
			{
				if(isEmailInDB($email))
				{
					$hash = genHash();
					$id = mysqli_query($link, "SELECT `id` FROM `users` WHERE `email`='$email'");
					$query = "INSERT INTO `token` SET `token`='$hash', `user_id`=$id, `type`='reset'";
					mysqli_query($link, $query);
					sendMail(2, $hash, $id, $email);
				}
				header('Location: mailsent.html');
				exit;

				if($password === $cpassword)
				{
					$password = getPasswordHash($cpassword);
					$sql = "UPDATE `users` SET `password` = '$password' WHERE `id`= $uid";
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset your password</title>
	<link rel="stylesheet" href="./css/pure/pure-min.css" />
	<link rel="stylesheet" href="./css/pure/grids-responsive-min.css" />
	<script defer src="./fontawesome-free-5.15.2-web/js/all.min.js"></script>
	<link rel="stylesheet" href="./fontawesome-free-5.15.2-web/css/all.min.css" />
	<link rel="stylesheet" href="./css/styles.css" />
</head>

<body>
<script>
	    function validate() {
    const pass = document.getElementById("aligned-password");
    const cpass = document.getElementById("aligned-cpassword");
    if (cpass.value !== pass.value) {
      cpass.style.borderColor = "red";
      document.getElementById("btn-submit").setAttribute("disabled", "");
    } else {
      cpass.style.borderColor = "#66ff00";
      document.getElementById("btn-submit").removeAttribute("disabled");
    }
  }
</script>
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
            <li class="pure-menu-item">
              <a href="./login.php" class="pure-menu-link">Sign in</a>
            </li>
            <li class="pure-menu-item">
              <a href="" class="pure-menu-link">Sign up</a>
            </li>
          </ul>
        </div>
      </div>
	  <div class="dash-header">
        <div class="splash">
          <h1 class="splash-head">Reset your password</h1>
        </div>
      </div>
      <div class="dash-content-wrapper">
        <div id="signup-content" class="content">
          <div class="pure-g" id="signup-form">
            <div class="pure-u-1-3"></div>
            <div class="pure-u-1-3">
	

<?php
	$hash = mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['hash'])));
	$uid = intval(mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['uid']))));
	if ($hash == "" || is_null($uid))
	{
		?>
              <form action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
                  <?php
                  if (isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <input type="hidden" value="1" name="hiddenval">
                  <div class="pure-control-group">
                    <label for="aligned-email">Email Address</label>
                    <input type="email" name="email" id="aligned-email" placeholder="Email Address" />
                  </div>
                  <div class="pure-controls">
                    <!-- <label for="aligned-cb" class="pure-checkbox">
                        <input type="checkbox" id="aligned-cb" /> I&#x27;ve read
                        the terms and conditions</label
                      > -->
                    <button name="submit" type="submit" id="btn-submit" class="pure-button pure-button-primary">
                      Submit
                    </button>
                  </div>
                </fieldset>
              </form>
            </div>
            <div class="pure-u-1-3"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <hr />

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
</html>
	<?php
	}

	else
	{	
		$sql = "SELECT * FROM `token` WHERE `user_id`=$uid AND `token`='$hash' AND `type`='reset'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res) === 1)
		{
			?>
              <form action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
                  <?php
                  if (isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <input type="hidden" value="1" name="hiddenval">
				  <div class="pure-control-group">
                    <label for="aligned-password">Password</label>
                    <input type="password" name="password" id="aligned-password" placeholder="Password" />
                  </div>
                  <div class="pure-control-group">
                    <label for="aligned-password">Confirm Password</label>
                    <input type="password" name="cpassword" id="aligned-cpassword" onkeyup="validate()" placeholder="Confirm password" />
                  </div>
                  <div class="pure-controls">
                    <!-- <label for="aligned-cb" class="pure-checkbox">
                        <input type="checkbox" id="aligned-cb" /> I&#x27;ve read
                        the terms and conditions</label
                      > -->
                    <button name="submit" type="submit" id="btn-submit" class="pure-button pure-button-primary">
                      Submit
                    </button>
                  </div>
                </fieldset>
              </form>
            </div>
            <div class="pure-u-1-3"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <hr />

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
	<script>
	  	    function validate() {
    const pass = document.getElementById("aligned-password");
    const cpass = document.getElementById("aligned-cpassword");
    if (cpass.value !== pass.value) {
      cpass.style.borderColor = "red";
      document.getElementById("btn-submit").setAttribute("disabled", "");
    } else {
      cpass.style.borderColor = "#66ff00";
      document.getElementById("btn-submit").removeAttribute("disabled");
    }
  }
  </script>
  </body>
</html>
			<?php
			$sql = "DELETE FROM `token` WHERE `user_id` = $uid";
			mysqli_query($link, $sql);
			$sql = "UPDATE `users` SET `email_verified` = 1 WHERE `id`= $uid";
			mysqli_query($link, $sql);
			header('Location: verified.html');
			exit;
		}
		else
		{
			echo "An unexpected error occurred.";
		}
	}
