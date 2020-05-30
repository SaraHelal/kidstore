<?php
	ob_start(); //Output Buffering Start
	session_start();
	$hiddenSearch = 'hidden';
	$page_title='Login'; 
	include 'init.php';

	if(isset($_GET['name'])){
	echo $_GET['name'];
	}


	//Login form validation
	if ($_SERVER['REQUEST_METHOD']=='POST'){
 	if(isset($_POST['login']))
 	{
        $email =$_POST['email'];
        $pass =$_POST['password'];
        $hashedpass= sha1($pass);
        echo $email . '<br>';
        echo $hashedpass;
        //Check if user exsits in DATABASE
        $stmt = $con->prepare("	SELECT * FROM users WHERE Email=? AND Password = ? ");
        $stmt->execute(array($email , $hashedpass ));
        $getid = $stmt -> fetch();
        $count = $stmt->rowCount();
        
        //If count >0 this means user is in DB

        if($count > 0 ){
            $_SESSION['userName']=$getid['Username'];// Register Session Name
            $_SESSION['uid']=$getid['UserID']; // Register Session id
            header('location:index.php'); // Redirect to Dashboard
            exit(); 
        }
        else{
        	header('location:login.php?msg=failed'); // Redirect to Dashboard
            exit(); 
        }
	}
}
 ?>

 <div class="container">
 	<?php 
 	if (isset($_GET["msg"]) && $_GET["msg"] == 'failed') {?>
 		<div class="error-login">
			Please check your e-mail or password.
		</div>
	<?php }	?>
	</div>
	<div class="d-flex">
	 	<div class="login">
		 	<p>Member Login</p>
		 	<form id="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		 		<div class="input-group mb-3">
				    <div class="input-group-prepend">
				      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
				    </div>
					<input id="email" type="email" class="form-control" data-text="Email" placeholder="E-mail" name="email" autocomplete="off" required>
				    <span class="validate"><i class="fas"></i></span>
				</div>

		 		<span class="input-error"></span>

		 		<div class="input-group mb-3">
				    <div class="input-group-prepend">
				      <span class="input-group-text"><i class="fas fa-key"></i></span>
				    </div>
		 			<input id="password" type="password" class="form-control" data-text="password" placeholder="Your password" name="password" autocomplete="new-password" required>
		 			<span class="validate"><i class="fas"></i></span>
				</div>
				<span class="input-error"></span>

		 		<div class="custom-control custom-checkbox mb-3">
		    		<input type="checkbox" class="custom-control-input" id="customCheck" name="example1">
		    		<label class="custom-control-label" for="customCheck">Remember me</label>
		    		<a href="#"><span>I forgot my password</span></a>
		  		</div>
		  		
		  		<input type="submit" class="submit main-btn button btn-block border-0" name="login" value="login"/>
		 	</form>
		 	<div class="question text-center my-3">
			 	<p class="mb-2">or</p>
			 	<p>Not a member yet? <a href="register.php">Sign Up</a></p>
		 	</div>
		</div>
	</div> 
</div>



<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();

?>