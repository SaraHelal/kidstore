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
				$record = getRecord('*' , 'users', 'Email' , $value );
				if(!empty($record)){
					echo 'The e-mail address has already been registered.';
				}
				else{
  					echo("validate");
  				}
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
			if (preg_match('/[A-Za-z]/', $value) && preg_match('/[0-9]/', $value)
			&& strlen($value) > 5)
			{
				echo("validate");   
			}
  			else
  			{
  				echo 'Your password must contain letters and numbers and must be at least six characters.';
  			}
		}
	}


elseif ($inputType == 'mobile'){
		if($value ==''){
			echo 'Please enter your phone number.';
		}
		else{
			if (strlen($value) > 9)
			{
				echo("validate");   
			}
  			else
  			{
  				echo 'Please enter a valid phone number.Phone number must be 10 numbers.';
  			}
		}
	}
	elseif ($inputType == 'text'){
		if(strlen($value) == 0){
			echo 'Please enter your name.';
		}
		elseif(strlen($value) < 4){
			echo 'Username must be larger than 3 characters';
		}
		else{
			echo "validate";
		}

	}

}    
