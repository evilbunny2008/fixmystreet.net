<?php
	header("Content-type: text/javascript");
?>

<?php
	require_once('../common.php');

	if(isset($_SESSION['loggedin']))
	{
?>

function previewFile(file, type)
	{
		let images = document.querySelector(".images");
		let img = document.createElement("img");
		img.setAttribute("onclick","rm(this)");
		let grid = document.querySelector(".pure-g");
		if(grid == null)
		{
			grid = document.createElement("div");
			grid.className = "pure-g";
		}
		img.className = "preview pure-u-1-4 is-center";
		img.setAttribute("name", "photo")
		// img.style.width = "200px";
		img.style.marginRight = "5%";
		let exists = document.querySelectorAll(".pure-u-1-4");
		if(exists.length == 0 || exists.length % 3 === 0)
			img.style.marginLeft = "5%";
		if(exists.length > 2)
			img.style.marginTop = "5%";
		images.appendChild(grid);
		grid.appendChild(img);
		let uuid;

		switch (type)
		{
			//IF FILES ARE DRAGGED
			case 1:
				// console.log(file);
				let reader = new FileReader();
				reader.readAsDataURL(file);
				// console.log(file);
				uuid = uploadFile(file).then(function(result) {
					let uuidField = document.createElement("input");
					uuidField.setAttribute("type", "hidden");
					uuidField.name = "uuid[]";
					uuidField.value = result.uuid+"|"+result.filename;
					images.appendChild(uuidField);
          img.setAttribute("uuid",result.uuid);

				});
				reader.onloadend = function() {
					img.src = reader.result;
				}
				break;
			//IF FILES ARE CHOSEN THROUGH INPUT
			case 2:
				// console.log(file);
				img.src = URL.createObjectURL(event.target.files[0]);
				uuid = uploadFile(event.target.files[0]).then(function (result) {
					let uuidField = document.createElement("input");
					uuidField.setAttribute("type", "hidden");
					uuidField.name = "uuid[]";
					uuidField.value = result.uuid+"|"+result.filename;
					images.appendChild(uuidField);
          img.setAttribute("uuid",result.uuid);
				});
				img.onload = function() {
					URL.revokeObjectURL(img.src);
				}
				break;
		}
		img.removeAttribute("hidden");
	}

function validate() {
  let submitButton = document.getElementById("submit");
  let field = [
    "lat",
    "lng",
    "address",
    "council",
    "summary",
    "extra"
  ];
  let res = checkEmpty(field);
  let exists = document.querySelectorAll(".pure-u-1-4");
  if (res === true && exists.length >= 2) {
    submitButton.removeAttribute("disabled");
    submitButton.style.backgroundColor = "";
  } else {
    submitButton.style.borderColor = "grey";
    submitButton.style.backgroundColor = "grey";
    submitButton.setAttribute("disabled", "");
  }
}

function initialize()
{
  const fileDrag = document.querySelector(".file-drop");
  const fileChoose = document.getElementById("myFiles");

  fileDrag.addEventListener("click", function() {
    fileChoose.click();
  });

  fileDrag.addEventListener("dragover", function() {
    event.preventDefault();
  });
  fileDrag.addEventListener("drop", function() {
    //GET THE FILE DATA;
    console.log("drag1");
    event.preventDefault();
    if(event.dataTransfer.items)
    {
      event.preventDefault();
      for (let i = 0; i < event.dataTransfer.items.length; i++)
      {
        // If dropped items aren't files, reject them
        if (event.dataTransfer.items[i].kind === 'file' && event.dataTransfer.items[i].type == "image/jpeg")
        {
          let file = event.dataTransfer.items[i].getAsFile();
          //DO THINGS WITH FILE HERE

          previewFile(file,1);
        }
        else
        {
          // console.log(event.dataTransfer.items[i].type);
          //REPLACE WITH MODAL
          showModal("Only jpegs/jpgs are allowed");
          break;
        }
      }
    }
    // document.querySelector(".img1").src = files[0]
  });
}

function init()
{
  const fileDrag = document.querySelector(".file-drop");
  const fileChoose = document.getElementById("myFiles");

  fileDrag.addEventListener("click", function() {
    fileChoose.click();
  });

  fileDrag.addEventListener("dragover", function() {
    event.preventDefault();
  });

  fileDrag.addEventListener("drop", function() {
    //GET THE FILE DATA;
    event.preventDefault();
    if(event.dataTransfer.items)
    {
      event.preventDefault();
      for (let i = 0; i < event.dataTransfer.items.length; i++)
      {
        // If dropped items aren't files, reject them
        if (event.dataTransfer.items[i].kind === 'file' && event.dataTransfer.items[i].type == "image/jpeg")
        {
          let file = event.dataTransfer.items[i].getAsFile();
          //DO THINGS WITH FILE HERE

          previewFile(file,1);
        }
        else
        {
          // console.log(event.dataTransfer.items[i].type);
          //REPLACE WITH MODAL
          showModal("Only jpegs/jpgs are allowed");
          break;
        }
      }
    }
    // document.querySelector(".img1").src = files[0]
  });
  validate();
}
<?php } ?>

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

function showModal(text) {
  let modal = document.querySelector(".modal");
  let modalText = document.querySelector(".modal-text");
  modalText.innerHTML = text;
  modal.style.display = "flex";
  document.querySelector(".close").addEventListener("click", function() {
    modal.style.display = "none";
  });
    window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}

function showImage(src) {
  let modal = document.querySelector(".modal");
  let modalImg = document.querySelector(".modal-img");
  let content = document.querySelector(".modal-content");
  content.style.width = "50%";
  src = src.replace('_thumb','');
  modalImg.src = src;
  modalImg.style.height = "100%";
  modalImg.style.width = "100%";
  modal.style.display = "flex";
  document.querySelector(".close").addEventListener("click", function() {
    modal.style.display = "none";
  });
    window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
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

  function isValid(field) {
    res = checkEmpty(field);
    const submitButton = document.getElementById("submit"); 
    if (res === true) {
      submitButton.removeAttribute("disabled");
      submitButton.style.backgroundColor = "";
    }
    else {
      submitButton.style.borderColor = "grey";
      submitButton.style.backgroundColor = "grey";
      submitButton.setAttribute("disabled", "");
    }
  }

function getExtra(id)
	{
		http.open('GET', '/extra.php?id=' + id, true);
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				if(http.responseText.trim() == "")
					return;

				let row = JSON.parse(http.responseText.trim());
				// do something with row...
				let parent = document.getElementById('reportProblem');
				if(parent.nextElementSibling.tagName != "DIV")
				{
					const reportInfo = document.createElement('div');
					reportInfo.className = "reportInfo";
					parent.after(reportInfo);
				}
				const reportInfo = document.querySelector(".reportInfo");
				reportInfo.innerHTML = '';
				reportInfo.innerHTML += `<form method="post" id="report" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']?>" >`;
				reportInfo.innerHTML += `<input type="hidden" id="problemID" name="problemID" value="${id}">`;
				reportInfo.innerHTML += `<p class="title">${row['summary']}</p>`;
				reportInfo.innerHTML += `<p class="created">Created on ${row['created']}</p>`;
				reportInfo.innerHTML += `<p class="updated">Last updated on ${row['lastupdate']}</p>`;
				reportInfo.innerHTML += `<p class="summary">${row['extra']}</p> `;
				// reportInfo.innerHTML += `<img class="img1" height="200px" width="200px" src="${row['photos'][0]['file_path']}">`;
				createCarousel(row['photos']);
				<?php
					if(isset($_SESSION['loggedin']))
					{
				?>
				reportInfo.innerHTML += `<h3>Have an update?</h3>`;
				reportInfo.innerHTML += `<label>Photos (if any)</label>`;
				reportInfo.innerHTML += `<div class="file-drop" ondrop=""> Drag or click here to choose files <input type="file" accept="image/jpeg" id="myFiles" multiple style="display:none;" onchange="previewFile(event,2)"></div>`;
				reportInfo.innerHTML += `<br /><br/><br/>`;
				reportInfo.innerHTML += `<p>HINT: Click on images to remove them!</p>`;
				reportInfo.innerHTML += `<div class="images">`;
				reportInfo.innerHTML += `</div>`;
				reportInfo.innerHTML += `<br /><br/><br/>`;
				<?php if(isset($msg)){?>
				reportInfo.innerHTML += `<p><?=$msg?></p>`;
				<?php } ?>
				reportInfo.innerHTML += `<label for="update-text">Update</label>`;
				reportInfo.innerHTML += `<br /><br/>`;
				reportInfo.innerHTML += `<textarea name="update-extra" oninput="isValid([this.id])" id="update-text" cols="40"rows="10" style="border-radius: 8px; resize:none;"></textarea>`;
				reportInfo.innerHTML += `<br /><br/>`;
				reportInfo.innerHTML += `<button href="#" name="submit" type="submit" value="submit" class="pure-button" id="submit" disabled>Submit</buttons>`;
				<?php
					} else {
				?>
						reportInfo.innerHTML += `<p>You <a href='https://fixmystreet.net/signup.php'>need an account</a> and to be <a href='https://fixmystreet.net/login.php'>logged in</a> to make reports</p>`;
				<?php
					}
				?>
				reportInfo.innerHTML += `</form>`;
        <?php if(isset($_SESSION['loggedin'])) { ?>
          initialize();
        <?php 
        }
        ?>
				// const menu = document.getElementById("menu");
				// menu.scrollTop = menu.scrollHeight;
				// reportInfo.innerHTML += ``;
				title = document.querySelector(".title");
				title.style.fontWeight = "bold";
				reportInfo.style.textAlign = "center";
				title = title.innerHTML;
				history.replaceState({}, title, `/reports/${id}`);
				document.title = title;
			}
		}

		http.send();
	}