<?php 
    require_once('common.php');
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 1)
    {
        header("Location: /");
	exit;
    }
    if(isset($_POST['submit']))
    {
        if(isset($_POST['hiddenval']) && $_POST['hiddenval'] === '1')
        {
          $email = empty(trim($_POST['email'])) ? NULL : mysqli_real_escape_string($link, trim($_POST['email']));
          $password = empty(trim($_POST['password'])) ? NULL : mysqli_real_escape_string($link, trim($_POST['password']));
          $cpassword = empty(trim($_POST['cpassword'])) ? NULL : mysqli_real_escape_string($link, trim($_POST['cpassword']));
          $phoneNo = empty(trim($_POST['number'])) ? NULL : mysqli_real_escape_string($link, trim($_POST['number']));
          $name = empty(trim($_POST['name'])) ? NULL : mysqli_real_escape_string($link, trim($_POST['name']));

          if($email == NULL || $password == NULL || $name == NULL || !filter_var($email,FILTER_VALIDATE_EMAIL))
          {
              $msg = _("An error ocurred.");
          }
          else if(isEmailInDB($email))
          {
              $msg = _("This email is already present in our database!");
          }
          else if($password === $cpassword && !isEmailInDB($email)) //SECOND CONDITION NOT NECESSARY (?)
          {
              if(!is_null($phoneNo) && isNumberInDB($phoneNo))
              {
                  $msg = _("Phone number already exists in our database");
              }
              else
              {
                  $password = getPasswordHash($cpassword);
                  registerUser($email, $password, $phoneNo, $name);
	          header("Location: signedup.html");
                  exit;
              }
          }
          else
          {
              $msg = _("The passwords you entered didn't match");
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
  <link rel="stylesheet" href="css/pure/pure-min.css" />
  <link rel="stylesheet" href="css/pure/grids-responsive-min.css" />
  <script defer src="fontawesome-free-5.15.2-web/js/all.min.js"></script>
  <link rel="stylesheet" href="fontawesome-free-5.15.2-web/css/all.min.css" />
  <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="css/styles.css" />
</head>

  <body>
  <?=$header?>
      <div class="dash-header signup">
        <div class="splash">
          <h1 id="sign-up">Sign Up</h1>
          <div id="" class="">
          <div id="signup-form">
          <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="pure-form pure-form-stacked">
                <fieldset>
                  <?php
                  if(isset($msg) && $msg != '')
                  { 
                      ?><div style='text-align:center; color:black'><?= $msg ?></div>
            <?php } ?>
                  <input type="hidden" value="1" name="hiddenval">
                  <div class="pure-control-group">
                    <label for="aligned-number">Phone number</label>
                    <input type="tel" name="number" id="aligned-number" placeholder="Phone number" />
                    <span class="pure-form-message-inline">This is an optional field.</span>
                  </div>
                  <div class="pure-control-group">
                    <label for="aligned-name">Name</label>
                    <input type="text" name="name" id="aligned-name" placeholder="Your name" />
                  </div>
                  <div class="pure-control-group">
                    <label for="aligned-email">Email Address</label>
                    <input type="email" name="email" id="aligned-email" placeholder="Email Address" />
                  </div>
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
        </div>
        </div>
      </div>
      <!-- <div class="dash-content-wrapper"> -->
        <!-- <div id="signup-content" class="content"> -->
      <!-- </div> -->
    </div>
    <?=$footer?>
  </body>
<script>
  function validate() {
    const pass = document.getElementById("aligned-password");
    const cpass = document.getElementById("aligned-cpassword");
    if(cpass.value !== pass.value) {
      cpass.style.borderColor = "red";
      document.getElementById("btn-submit").setAttribute("disabled", "");
    } else {
      cpass.style.borderColor = "#66ff00";
      document.getElementById("btn-submit").removeAttribute("disabled");
    }
  }
</script>

</html>
