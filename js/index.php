<?php
	header("Content-type: text/javascript");
?>
function showSteps() {
  const step = document.querySelectorAll(".pure-menu-link");
  const divs = ["step-one", "step-two", "step-three"];

  for (let i = 0; i < step.length; i++) {
    step[i].addEventListener("click", function () {
      step[i].nextElementSibling.removeAttribute("hidden");
      // if(!step[i].nextElementSibling.hasAttribute("hidden")) {
      // 	step[i].setAttribute("hidden", "");
      // }
      for (let j = 0; j < divs.length; j++) {
        if (step[i].nextElementSibling.id != divs[j]) {
          if (!document.getElementById(divs[j]).hasAttribute("hidden")) {
            document.getElementById(divs[j]).setAttribute("hidden", "");
          }
        }
      }
    });
  }
}

function checkEmpty(field) {
  for (let i = 0; i < field.length; i++) {
//    alert(field[i]);
    if (document.getElementById(field[i]).value.trim() == "") {
      return false;
    }
  }
  return true;
}

<?php
	require_once('../common.php');

	if(isset($_SESSION['loggedin']))
	{
?>
function validate() {
  let sumbitButton = document.getElementById("submit");
  let field = [
    "lat",
    "lng",
    "address",
    "council",
    "summary",
    "extra",
    "myFile1",
    "myFile2",
  ];
  let res = checkEmpty(field);
  if (res === true) {
    sumbitButton.removeAttribute("disabled");
    sumbitButton.style.backgroundColor = "";
  } else {
    sumbitButton.style.borderColor = "grey";
    sumbitButton.style.backgroundColor = "grey";
    sumbitButton.setAttribute("disabled", "");
  }
}
<?php } ?>
