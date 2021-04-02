"use strict";

function showSteps() {
  const step = document.querySelectorAll(".pure-menu-link");
  const divs = ["step-one", "step-two", "step-three"];

  for (let i = 0; i < step.length - 1; i++) {
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
    if (field[i].value.trim() == "") {
      return false;
    }
  }
  return true;
}

function validate() {
  let sumbitButton = document.getElementById("submit");
  let field = [
    "latField",
    "lonField",
    "address",
    "council",
    "summary",
    "description",
    "myFile",
  ];
  let res = checkEmpty(field);
  if (res === true) {
    sumbitButton.removeAttribute("disabled");
    sumbitButton.style.backgroundColor = "#add8e6";
  } else {
    sumbitButton.style.borderColor = "red";
    sumbitButton.style.boxShadow = "0 0 0 0.15rem red";
    sumbitButton.setAttribute("disabled", "");
  }
}
