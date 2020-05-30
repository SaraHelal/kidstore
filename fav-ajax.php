<?php 
	session_start();
	include 'connect.php';
	include 'include/functions/functions.php';
	
	if(isset($_POST['p_id'])){
		$pid = $_POST['p_id'];
		$color = $_POST['color'];

		if($_POST['heartStatus'] == 1){

			//Add product to Fav Session
			$_SESSION['fav'][] = array('id' => $pid, 'color' => $color);

			//Add Product to Fav DB
			if(isset($_SESSION['uid'])){

				//Check If It Is there brfore 
				$fav = checkUserFav('*' , 'favourites' , 'user_id' , $_SESSION['uid'] , 'P_id' , $pid , 'color_id' , $color);
				
				if(empty($fav)){
					$stmt2 = $con -> prepare('INSERT INTO favourites (P_id , color_id , user_id) VALUES (? , ? , ?)');
					$stmt2->execute(array($pid , $color , $_SESSION['uid']));
				}	
			}
		}

		//Deleting Product From Fav Session
		elseif($_POST['heartStatus'] == 0){
			
			foreach ($_SESSION['fav'] as $key => $val) {
	           if ($val['id'] == $pid && $val['color'] == $color) {
	            	unset($_SESSION['fav'][$key]);
					$_SESSION['fav'] = array_values($_SESSION['fav']);
	            	break;
	        	}
        	}

        	//Remove Product From Fav DB	
			if(isset($_SESSION['uid'])){
				$fav = checkUserFav('*' , 'favourites' , 'user_id' , $_SESSION['uid'] , 'P_id' , $pid , 'color_id' , $color);
				if(!empty($fav)){
					$stmt = $con -> prepare('DELETE FROM favourites WHERE user_id = ? AND P_id = ? AND color_id = ? ');
					$stmt->execute(array($_SESSION['uid'] , $pid  , $color));
				}		
			}			
		}
				
		echo sizeof($_SESSION['fav']);
	}

