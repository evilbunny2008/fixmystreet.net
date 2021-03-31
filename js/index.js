"use strict";

function showSteps() {
const step = document.querySelectorAll(".pure-menu-link");
const divs = ["step-one", "step-two", "step-three"];

	for (let i = 0; i < step.length-1; i++) {
			step[i].addEventListener("click", function() {
			step[i].nextElementSibling.removeAttribute("hidden");
			if(!step[i].nextElementSibling.hasAttribute("hidden")) {
				step[i].setAttribute("hidden", "");
			}
			for(let j=0; j<divs.length; j++) {
				if(step[i].nextElementSibling.id != divs[j]) {
					if(!document.getElementById(divs[j]).hasAttribute("hidden")) {
						document.getElementById(divs[j]).setAttribute("hidden", "");
					}
				}
			}
		});
	}
}