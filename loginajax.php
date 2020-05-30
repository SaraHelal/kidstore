<?php 
	include 'connect.php';
	include 'include/functions/functions.php';
if(isset($_POST['input'])){
	$inputType = $_POST['input'];
	$value = $_POST['value'];
	if($inputType == 'email' ){
		if($value ==''){
			echo 'Please enter your e-mail address.';
		}
		else{
			if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
  				echo("validate");
			} 
			else {
  				echo("Please enter a valid e-mail address.");
			}
		}
		}

	elseif ($inputType == 'password'){
		if($value ==''){
			echo 'Please enter your password.';
		}
		else{
  			echo("validate");
		}
	}
}


    
