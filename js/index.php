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
		//let images = document.querySelector(".images");
    //console.log(images)
		//let img = document.createElement("img");
		//img.setAttribute("onclick","rm(this)");
		//let grid = document.querySelector(".pure-g");
		//if(grid == null)
		//{
		//	grid = document.createElement("div");
		//	grid.className = "pure-g";
		//}
		//img.className = "preview pure-u-1-4 is-center";
		//img.setAttribute("name", "photo");
		// img.style.width = "200px";
		//img.style.marginRight = "5%";
		//let exists = document.querySelectorAll(".pure-u-1-4");
		//if(exists.length == 0 || exists.length % 3 === 0)
			//img.style.marginLeft = "5%";
		//if(exists.length > 2)
			//img.style.marginTop = "5%";
		//images.appendChild(grid);
		//grid.appendChild(img);
		let uuid;

		switch (type)
		{
			//IF FILES ARE DRAGGED
			case 1:
        let images = document.querySelector(".images");
    console.log(images)
		let img = document.createElement("img");
		img.setAttribute("onclick","rm(this)");
		let grid = document.querySelector(".pure-g");
		if(grid == null)
		{
			grid = document.createElement("div");
			grid.className = "pure-g";
		}
		img.className = "preview pure-u-1-4 is-center";
		img.style.marginRight = "5%";
		let exists = document.querySelectorAll(".pure-u-1-4");
		if(exists.length == 0 || exists.length % 3 === 0)
			img.style.marginLeft = "5%";
		if(exists.length > 2)
			img.style.marginTop = "5%";
		images.appendChild(grid);
		grid.appendChild(img);
		img.removeAttribute("hidden");

				let reader = new FileReader();
				reader.readAsDataURL(file);
        res = uploadFile(file);
				reader.onloadend = function() {
					img.src = reader.result;
				}
				break;
			//IF FILES ARE CHOSEN THROUGH INPUT
			case 2:
        console.log(event.target.files.length);
        for(let i = 0; i < event.target.files.length; i++) {
          let images = document.querySelector(".images");
    console.log(images)
		let img = document.createElement("img");
		img.setAttribute("onclick","rm(this)");
		let grid = document.querySelector(".pure-g");
		if(grid == null)
		{
			grid = document.createElement("div");
			grid.className = "pure-g";
		}
		img.className = "preview pure-u-1-4 is-center";
		img.style.marginRight = "5%";
		let exists = document.querySelectorAll(".pure-u-1-4");
		if(exists.length == 0 || exists.length % 3 === 0)
			img.style.marginLeft = "5%";
		if(exists.length > 2)
			img.style.marginTop = "5%";
		images.appendChild(grid);
		grid.appendChild(img);
		img.removeAttribute("hidden");
    img.src = URL.createObjectURL(event.target.files[i]);
    images.appendChild(grid);
    grid.appendChild(img);
    images.append(img);
    console.log(img);
    uploadFile(event.target.files[i]);
    img.onload = function() {
        URL.revokeObjectURL(img.src);
      }
    }
	    break;
    }
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
          //REPLACE WITH MODAL
          showModal("Only jpegs/jpgs are allowed");
          break;
        }
      }
    }
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
          //REPLACE WITH MODAL
          showModal("Only jpegs/jpgs are allowed");
          break;
        }
      }
    }
  });
  validate();
}
<?php } ?>

function showSteps() {
  const pathArray = window.location.pathname.split("/");
  if (pathArray[1].toUpperCase() == "reports".toUpperCase())
  {
    if(!isNaN(parseInt(pathArray[2])))
    {
      getExtra(parseInt(pathArray[2]));
      getComments((parseIntpathArray[2]));
    }
  }
  const step = document.querySelectorAll(".pure-menu-link");
  const divs = ["step-one", "step-two", "step-three"];

  for (let i = 0; i < step.length; i++) {
    step[i].addEventListener("click", function () {
      step[i].nextElementSibling.removeAttribute("hidden");
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

      modalText.innerHTML = "";
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
    content.style.width = "";
  });
    window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
      content.style.width = "";
    }
  }
}

async function loading() {
  let modal = document.querySelector(".modal");
  let modalImg = document.querySelector(".modal-img");
  modalImg.src = "/images/spinner.gif";
  modal.style.display = "flex";
}

function checkEmpty(field) {
  for (let i = 0; i < field.length; i++) {
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
        if(row['status'] == "FAIL")
        {
          alert(row['errmsg']);
          return;
        }
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
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("enctype","multipart/form-data");
        form.setAttribute("action","/map.php");
				reportInfo.innerHTML += `<p class="title">${row['summary']}</p>`;
				reportInfo.innerHTML += `<p class="created">Created on ${row['created']}</p>`;
				reportInfo.innerHTML += `<p class="updated">Last updated on ${row['lastupdate']}</p>`;
				reportInfo.innerHTML += `<p class="summary">${row['extra']}</p> `;
				reportInfo.innerHTML += `<input type="hidden" id="problemID" name="problemID" value="${id}">`;
				createCarousel(row['photos']);
				<?php
					if(isset($_SESSION['loggedin']))
					{
				?>
				let problemID = document.createElement("input");
        problemID.type = "hidden";
        problemID.name = "problemID";
        problemID.value = id;
        form.appendChild(problemID);
				form.innerHTML += `<h3>Have an update?</h3>`;
				form.innerHTML += `<label>Photos (if any)</label>`;
        let dragNdrop = document.createElement("div");
        dragNdrop.className = "file-drop";
        dragNdrop.innerHTML = "Drag or click here to choose files";
        let fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.setAttribute("accept","image/jpeg");
        fileInput.setAttribute("onchange","previewFile(event,2)");
        fileInput.setAttribute("multiple","");
        fileInput.style.display = "none";
        fileInput.id = "myFiles";
        dragNdrop.appendChild(fileInput);
        form.appendChild(dragNdrop);
				form.innerHTML += `<br /><br/><br/>`;
				form.innerHTML += `<p>HINT: Click on images to remove them!</p>`;
				form.innerHTML += `<div class="images">`;
				form.innerHTML += `</div>`;
				form.innerHTML += `<br /><br/><br/>`;
				<?php if(isset($msg)){?>
				reportInfo.innerHTML += `<p><?=$msg?></p>`;
				<?php } ?>
				form.innerHTML += `<label for="update-text">Update</label>`;
        form.innerHTML += `<br /><br/>`;
        let update = document.createElement("textarea");
        update.name = "update-extra";
        update.setAttribute("oninput","isValid([this.id])");
        update.id = "update-text";
        update.cols = "40";
        update.rows = "10";
        update.style.borderRadius = "8px";
        update.style.resize = "none";
        form.appendChild(update);
				form.innerHTML += `<br /><br/>`;
				form.innerHTML += `<br /><br/>`;
				form.innerHTML += `<button href="#" name="submit" type="submit" value="submit" class="pure-button" id="submit" disabled>Submit</buttons>`;
        reportInfo.appendChild(form);
        <?php
					} else {
				?>
						reportInfo.innerHTML += `<p>You <a href='<?= $refererurl ?>signup.php'>need an account</a> and to be <a href='<?= $refererurl ?>login.php'>logged in</a> to make reports</p>`;
				<?php
					}
				?>
        <?php if(isset($_SESSION['loggedin'])) { ?>
          initialize();
        <?php 
        }
        ?>
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