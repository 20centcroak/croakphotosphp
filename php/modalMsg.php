<?php

	header("Content-type:text/html");

	session_start();
	$msg = "an error has occured";
	$msg = $_SESSION["msg"];
	$_SESSION["msg"] ="";

	echo'<div class="modal fade" id="myModal" role="dialog">';
	echo'<div class="modal-dialog">';
	echo'<div class="modal-content">';
	echo'<div class="modal-body">';
	echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
	echo"<p>$msg</p>";        
	echo'</div>';
	echo'</div>';
	echo'</div>';
	echo'</div>';

 ?>