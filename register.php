<?php
	ob_start(); //Output Buffering Start
	session_start();
	$hiddenSearch = 'hidden';
	$page_title='Register'; 
	include 'init.php';
	
	//Register form validation

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['register'])){
			$formErrors = array();
			$username = $_POST['username'];
			$email = $_POST['email'];
			$pass = $_POST['password'];
			$hashedpass = sha1($pass);
			$mobile = $_POST['mobile'];
			

			if(isset($username) && empty($username)){
    				$formErrors[] = 'Username can\'t be empty';
    		}
    		if(isset($pass) && empty($pass)){
    			$formErrors[]= 'password can\'t be empty';
    		}
    		if(isset($email)){
    			$filteredEmail = filter_var($email , FILTER_SANITIZE_EMAIL);

    			if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true){
    				$formErrors[] = 'Email is not correct';
    			}

    		}

    		if(empty($formErrors)){

    		 	$stmt = $con->prepare('INSERT INTO users(Username , Email , Password , mobile , Date) VALUES 
    		 		(? , ? , ? , ? , now())');
    		 	$stmt -> execute(array($username , $email , $hashedpass , $mobile ));
    		 	$count = $stmt->rowCount();
    		 	if($count > 0){
    		 		$getid = getRecord('*' , 'users' , 'Email' , $email );
    		 		$_SESSION['userName']=$getid['Username'];// Register Session Name
            		$_SESSION['uid']=$getid['UserID']; // Register Session id
    		 		header('location:index.php'); // Redirect to Dashboard
            		exit();

    		 	}
    		 	else{
    		 		echo'You can not register right now'; 
    		 	}
    		}
    		else{
    		 	print_r($formErrors);
    		}
		}
	}
 ?>

<div class="container d-flex">
 	<div class="login">
	 	<p>Member Registeration</p>
	 	<form id="registerForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	 		
	 		<div class="input-group mb-3">
			    <div class="input-group-prepend">
			      <span class="input-group-text"><i class="fas fa-user"></i></span>
			    </div>
				<input type="text" id="username" class="form-control username" data-text="name" placeholder="Username" name="username" pattern=".{3,}" required autocomplete="off">
				<span class="validate"><i class="fas"></i></span>
			</div>
			<span class="input-error"></span>

	 		<div class="input-group mb-3">
			    <div class="input-group-prepend">
			      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
			    </div>
				<input type="email" id="emailRegister" class="form-control" data-text="Email" placeholder="E-mail" name="email" autocomplete="off" required>
				<span class="validate"><i class="fas"></i></span>
			</div>
	 		<span class="input-error"></span>

	 		<div class="input-group mb-3">
			    <div class="input-group-prepend">
			      <span class="input-group-text"><i class="fas fa-key"></i></span>
			    </div>
	 			<input type="password" class="form-control password" data-text="password" placeholder="Your password"name="password" autocomplete="off" pattern="(?=.*\d)(?=.*[A-Za-z]).{6,}"
  				title="Must contain at least one  number and one uppercase and lowercase letter, and at least 6 or more characters" required>
	 			<span class="validate"><i class="fas"></i></span>
			</div>
			<span class="input-error"></span>
			
			<div class="input-group mb-3">
			    <div class="input-group-prepend">
			      <span class="input-group-text"><i class="fa fa-mobile"></i></span>
			    </div>
	 			<input type="number" class="mobile form-control" data-text="mobile" placeholder="mobile" name="mobile" required autocomplete="off">
	 			<span class="validate"><i class="fa"></i></span>
			</div>
			<span class="input-error"></span>

	 		<div class="custom-control custom-checkbox mb-1">
	    		<input type="checkbox" class="custom-control-input" id="customCheck1" name="example1">
	    		<label class="custom-control-label" for="customCheck1">I would like to be contacted by e-mail to improve marketing and service.</label>
	    	
	  		</div>

	  		<div class="custom-control custom-checkbox mb-1">
	    		<input type="checkbox" class="custom-control-input" id="customCheck2" name="example1">
	    		<label class="custom-control-label" for="customCheck2">I would like to be contacted by SMS or by calling to improve the marketing and service provided.</label>
	  		</div>

	 		<div class="custom-control custom-checkbox mb-3">
	    		<input type="checkbox" class="custom-control-input" id="customCheck3" required >
	    		<label class="custom-control-label" for="customCheck3"> approved the Usage <a href="#">and Privacy Agreement</a> and the <a href="#">Clarification Text. </a>
	    		</label>
	  		</div>

		  	<input type="submit" class="submit main-btn button btn-block border-0" name="register" value="Sign Up"/>

	 	</form>
	 	<div class="question text-center my-3">
		 	<p class="mb-2">or</p>
		 	<p>Already a member? <a href="login.php">Login</a></p>
	 	</div>
	</div>
</div>


<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>