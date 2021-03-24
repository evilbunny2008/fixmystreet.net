<?php
	require_once('common.php');
	if(isset($_POST['submit']))
	{
		if(isset($_POST['hiddenval']) && $_POST['hiddenval'] === '1')
		{
			$email = empty(trim($_POST['email'])) ? NULL : mysqli_real_escape_string($link, $_POST['email']);

			if($email == NULL || !filter_var($email,FILTER_VALIDATE_EMAIL))
			{
				$msg = _("Invalid input");
			}
			if(isEmailInDB($email))
			{
				$hash = genHash();
				$ret = mysqli_fetch_assoc(mysqli_query($link, "SELECT `id` FROM `users` WHERE `email`='$email'"));
				$id = $ret['id'];
				$query = "INSERT INTO `token` SET `token`='$hash', `user_id`=$id, `type`='reset'";
				mysqli_query($link, $query);
				sendMail(2, $hash, $id, $email);
      }
      header('Location: mailsent.html');
      exit;
		}
		elseif(isset($_POST['hiddenval']) && $_POST['hiddenval'] === '2')
		{
			$password = empty(trim($_POST['password'])) ? NULL : mysqli_real_escape_string($link, $_POST['password']);
			$cpassword = empty(trim($_POST['cpassword'])) ? NULL : mysqli_real_escape_string($link, $_POST['cpassword']);
			$uid = intval(trim(strip_tags($_REQUEST['uid'])));

			if($password !== $cpassword)
			{
				$msg = _("The passwords you entered didn't match");
			}

      if($password === $cpassword)
      {
        $password = getPasswordHash($cpassword);
        $sql = "UPDATE `users` SET `password` = '$password' WHERE `id`= $uid";
        mysqli_query($link, $sql);
        $sql = "DELETE FROM `token` WHERE `user_id` = $uid";
        mysqli_query($link, $sql);
        header('Location: reset.html');
        exit;
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
  <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
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
    <?= $header ?>
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
	$hash = isset($_REQUEST['hash']) ? mysqli_real_escape_string($link, trim(strip_tags($_REQUEST['hash']))) : "";
	$uid = isset($_REQUEST['uid']) ? intval(trim(strip_tags($_REQUEST['uid']))) : NULL;
	if ($hash == "" || is_null($uid))
	{
		?>
              <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="pure-form pure-form-stacked">
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
    <?=$footer?>
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
              <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
                  <?php
                  if (isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <input type="hidden" value="2" name="hiddenval">
                  <input type="hidden" value="<?=$uid?>" name="uid">
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
    <?=$footer?>
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
		}
		else
		{
			echo "An unexpected error occurred.";
		}
	}
