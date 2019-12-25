<?php
session_start();

empty($_SESSION["user"])?header("Location:https://photos.croak.fr/signin.htm"):$userName = $_SESSION["user"];
$_SESSION["nb_errors"] = 0;

?>
<!DOCTYPE html>
<html>
<title>Croak Photos</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/upload.css">

<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-container w3-top w3-black w3-large w3-padding" style="z-index:4">
  <button class="w3-btn w3-hide-large w3-padding-0 w3-hover-text-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
</div>

<!-- Sidenav/menu -->
<nav class="w3-sidenav w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidenav"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&amp;f=y" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8">
      <span>Welcome <strong><?php echo $userName?></strong></span><br>
      <a href="#" class="w3-hover-none w3-hover-text-red w3-show-inline-block"><i class="fa fa-envelope"></i></a>
      <a href="#" class="w3-hover-none w3-hover-text-green w3-show-inline-block"><i class="fa fa-user"></i></a>
      <a href="#" class="w3-hover-none w3-hover-text-blue w3-show-inline-block"><i class="fa fa-cog"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <a href="#" class="w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
  <a href="#" class="w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  Upload</a>
  <a href="#" class="w3-padding"><i class="fa fa-eye fa-fw"></i>  Organise</a>
<!--  <a href="#" class="w3-padding"><i class="fa fa-users fa-fw"></i>  Traffic</a>-->
<!--  <a href="#" class="w3-padding"><i class="fa fa-bullseye fa-fw"></i>  Geo</a>-->
  <a href="#" class="w3-padding"><i class="fa fa-cog fa-fw"></i>  Settings</a><br><br>
</nav>


<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Administration</b></h5>
  </header>
  
  <?php if ($_GET["status"]==="activated") echo '<p>you account is now validated</p>';?>

	<article>
	  <div id="holder"><i class="fa fa-cloud-upload" aria-hidden="true"></i></div> 
	  <p id="upload" class="hidden"><label>Upload your browser for drag&drop of files<br><input type="file"></label></p>
	  <p id="filereader">File API & FileReader API not supported</p>
	  <p id="formdata">XHR2's FormData is not supported</p>
	  <p id="progress">XHR2's upload progress isn't supported</p>
	  <p>Upload progress: <progress id="uploadprogress" min="0" max="100" value="0">0</progress></p>
	</article>
	<script src="js/upload.js"></script>


 <script src="js/upload.js"></script>

<script>
// Get the Sidenav
var mySidenav = document.getElementById("mySidenav");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidenav, and add overlay effect
function w3_open() {
    if (mySidenav.style.display === 'block') {
        mySidenav.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidenav.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidenav with the close button
function w3_close() {
    mySidenav.style.display = "none";
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
