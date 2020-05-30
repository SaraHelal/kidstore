<?php 
	session_start();
	include 'connect.php';
	include 'include/functions/functions.php';

	if(! isset($quantity)){
		$quantity =1;
	}
	//echo 'begin ajax';

	if(isset($_POST['p_id'])){
		$pid = $_POST['p_id'];
		$color = $_POST['color'];
		$size = $_POST['size'];
		$quantity = $_POST['quantity'];
		$edit = $_POST['edit'];
		echo 'edit is ' . $edit;

		foreach ($_SESSION['cart'] as $key => $val) {
           if ($val['id'] == $pid && $val['color'] == $color && $val['size'] == $size ) {
	           	
	           	if($edit == 'plus'){
    	        	$_SESSION['cart'][$key]['quantity'] = $_SESSION['cart'][$key]['quantity']+1;
	            	$quantity = $_SESSION['cart'][$key]['quantity'];
	            	
	            	//increment quantity in database
	            	if(isset($_SESSION['uid'])){
	            		$stmt = $con -> prepare('UPDATE orders 
						SET Quantity = ?
						WHERE user_id = ? AND P_id = ? AND color_id = ? AND size = ? ');
						$stmt->execute(array($quantity , $_SESSION['uid'] , $pid , $color ,  $size));
	            	}

				}
				else if ($edit == 'minus'){
					if ($quantity > 1 ){

					//Delete Quantity From Cart Session

						$_SESSION['cart'][$key]['quantity'] = $_SESSION['cart'][$key]['quantity']-1;
	            		$quantity = $_SESSION['cart'][$key]['quantity'];

	            		//decrement quantity in database
	            		if(isset($_SESSION['uid'])){
	            			$stmt = $con -> prepare('UPDATE orders 
							SET Quantity = ?
							WHERE user_id = ? AND P_id = ? AND color_id = ? AND size = ? ');
							$stmt->execute(array($quantity , $_SESSION['uid'] , $pid , $color ,  $size));
	            		}
		            }
				}
				else if ($edit == ''){
					//Delete Quantity From Cart Session
            		unset($_SESSION['cart'][$key]);
						$_SESSION['cart'] = array_values($_SESSION['cart']);
					if(isset($_SESSION['uid'])){

						//Delete Quantity From DB
	            		$stmt = $con -> prepare('DELETE FROM orders WHERE user_id = ? AND P_id = ? AND color_id = ? AND size = ?');
						$stmt->execute(array($_SESSION['uid'] , $pid , $color ,  $size));
	            	}

				}

            break;
        	}
        }	
	}
?>
