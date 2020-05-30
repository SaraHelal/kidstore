<?php 
	session_start();
	include 'connect.php';
	include 'include/functions/functions.php';
	if(! isset($quantity)){
		$quantity =1;
	}
	
	if(isset($_POST['p_id'])){
		
		$pid = $_POST['p_id'];
		$color = $_POST['color'];
		$size = $_POST['size'];
		$status = 0;

		foreach ($_SESSION['cart'] as $key => $val) {
           
           //Check if it is added before to increment quantity
           if ($val['id'] == $pid && $val['color'] == $color && $val['size'] == $size ) {

            $_SESSION['cart'][$key]['quantity'] = $_SESSION['cart'][$key]['quantity']+1;
            $quantity = $_SESSION['cart'][$key]['quantity'];

            //update quantity in database
            if(isset($_SESSION['uid'])){
            	$stmt = $con -> prepare('UPDATE orders 
				SET Quantity = ?
				where user_id = ? AND P_id = ? AND color_id = ? AND size = ? ');
				$stmt->execute(array($quantity , $_SESSION['uid'] , $pid , $color ,  $_POST['size']));
            }
            
            $status = 1;
            break;
           }
           
       }
       
       //Add it to Cart Session
       	if ($status == 0){
        	$_SESSION['cart'][] = array('id' => $pid, 'color' => $color , 'size' => $_POST['size'], 'quantity' => 1);
        	
        	//Check it in DB to Add it
        	if(isset($_SESSION['uid'])) {
        		$order = checkUserOrder('*' , 'orders' , 'user_id' , $_SESSION['uid'] , 'P_id' , $pid, 'color_id' , $color , 'size' , $_POST['size']);

				if(empty($order)){
					$stmt2 = $con -> prepare('INSERT INTO orders(user_id  , P_id , color_id , size , Quantity ) VALUES (? , ? , ? , ? , ?)');
					$stmt2->execute(array( $_SESSION['uid'] , $pid , $color , $_POST['size'] , 1));
				}
        	} 	
        }
        
        //Function to display Details of Added Product
		cartbox($pid , $color , $_POST['size'] , $quantity);
		
	}

