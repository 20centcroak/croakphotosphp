<?php
session_start();
if (!isset($_SESSION["gallery"])){
	header("Location:https://photos.croak.fr");
}
?>
<!DOCTYPE html>
<html>
<title>Croak</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">

<body onload="request(display)">

<!-- Sidenav -->
<nav class="w3-sidenav w3-black w3-animate-top w3-center w3-xxlarge" style="display:none;padding-top:150px" id="mySidenav">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-closenav w3-jumbo w3-display-topright" style="padding:6px 24px">
    <i class="fa fa-remove"></i>
  </a>
    <a href="https://photos.croak.fr/" class="w3-text-grey w3-hover-black">Home</a>
    <a href="https://photos.croak.fr/signup" class="w3-text-grey w3-hover-black">Create Account</a>
  <a href="https://photos.croak.fr/usr/vip" class="w3-text-grey w3-hover-black">Example</a>
</nav>

<!-- !PAGE CONTENT! -->
<div class="w3-content" style="max-width:1500px">

<!-- Header -->
<header class="w3-container w3-padding-32 w3-center w3-opacity w3-margin-bottom">
  <span class="w3-opennav w3-xxlarge w3-right w3-margin-right" onclick="w3_open()"><i class="fa fa-bars"></i></span> 
  <div class="w3-clear"></div>
  <h1 id="title"></h1>
</header>

<!-- Photo Grid -->
<div id="myGrid">
	<div id="pictures" class="w3-row-padding" style="margin-bottom:128px">		 
	</div>
</div>

<!-- End Page Content -->
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-light-grey w3-center w3-opacity w3-xlarge" style="margin-top:128px"> 
   <a href="https://www.flickr.com/photos/vlez" class="w3-hover-text-grey"><i class="fa fa-flickr"></i></a>
</footer> 
 
<script src="js/oXHR.js"></script>
<script src="js/manageThumbs.js"></script> 

</body>
</html>