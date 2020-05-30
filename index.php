<?php
	ob_start(); //Output Buffering Start
	session_start();
	if(! isset($_SESSION['fav'])){
		$_SESSION['fav']= array();
	}
	if(! isset($_SESSION['cart'])){
		$_SESSION['cart']= array();
	}

    $page_title='Home'; 
    include 'init.php';
			
    if(isset($_SESSION['uid'])){
    	if(isset($_SESSION['fav']) && !empty($_SESSION['fav'])){
			end($_SESSION['fav']);
			$lastKey = key($_SESSION['fav']);		
			foreach ($_SESSION['fav'] as $key => $val) {

				$fav = checkUserFav('*' , 'favourites' , 'user_id' , $_SESSION['uid'] , 'P_id' , $_SESSION['fav'][$key]['id'] , 'color_id' , $_SESSION['fav'][$key]['color']);

				if(empty($fav)){
					$stmt2 = $con -> prepare('INSERT INTO favourites (P_id , color_id , user_id) VALUES (? , ? , ?)');
					$stmt2->execute(array($_SESSION['fav'][$key]['id'] , $_SESSION['fav'][$key]['color'] , $_SESSION['uid']));
				}
				if ($key == $lastKey) {
					$_SESSION['fav']= array();

				}
			}
    	}
    	
    	 //fill Fav session with user fav in database 
    	if(empty($_SESSION['fav'])){

	    	$userfavs = getAll('*' , 'favourites' , 'user_id' , $_SESSION['uid'] );
	    	if(!empty($userfavs)){
		    	foreach ($userfavs as $key => $userfav) {
					
				$_SESSION['fav'][] = array('id' => $userfav['P_id'], 'color' => $userfav['color_id']);

					if ($userfav === end($userfavs)) {
						$favEdit = 1;
			    	}	    
		    	}
		   	}
    	} 

    	//add cart session to database 

    	if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
    		end($_SESSION['cart']);
			$lastKeyCart = key($_SESSION['cart']);	
    		foreach ($_SESSION['cart'] as $key => $val) {

				$order = checkUserOrder('*' , 'orders' , 'user_id' , $_SESSION['uid'] , 'P_id' , $_SESSION['cart'][$key]['id'] , 'color_id' , $_SESSION['cart'][$key]['color'] , 'size' , $_SESSION['cart'][$key]['size']);

				if(empty($order)){

					$stmt2 = $con -> prepare('INSERT INTO orders(user_id  , P_id , color_id , size , Quantity ) VALUES (? , ? , ? , ? , ?)');
					$stmt2->execute(array( $_SESSION['uid'] , $_SESSION['cart'][$key]['id'] , $_SESSION['cart'][$key]['color'] ,  $_SESSION['cart'][$key]['size'] , $_SESSION['cart'][$key]['quantity']));
				}
				if ($key == $lastKeyCart) {
					$_SESSION['cart']= array();

				}		
			} 
      	} 
      	 //fill Fav session with user fav in database 
    	if(empty($_SESSION['cart'])){

	    	$userOrders = getAll('*' , 'orders' , 'user_id' , $_SESSION['uid']);
	    	if(!empty($userOrders)){
		    	foreach ($userOrders as $key => $userOrder) {
					
				$_SESSION['cart'][] = array('id' => $userOrder['P_id'], 'color' => $userOrder['color_id'] , 'size' => $userOrder['size'] , 'quantity' => $userOrder['Quantity']);
					if ($userOrder === end($userOrders)) {

						$cartEdit = 1;		
			    	}	    
		    	}
		   	}
    	} 
    }
    $stmt = $con->prepare("SELECT DISTINCT product.* , productimgs.url , inventory.color_id  FROM product  , productimgs , inventory  where product.P_id = productimgs.P_id AND product.P_id = inventory.P_id AND productimgs.main = 1 GROUP BY inventory.P_id ORDER BY product.Add_Date DESC  LIMIT 4");
    $stmt->execute();
	$products = $stmt->fetchAll();

?>
	
	<!--banner 
	<div class="banner-header d-none d-md-block">
		<div class="container">
		</div>
	</div>-->

	<!--slider -->
	<div id="mainSlider" class="carousel slide" data-ride="carousel">
		<div class="container position-relative">
		  <ol class="carousel-indicators">
		    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
		    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
		    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
		  </ol>
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <a href="#"><img class="d-block w-100" src="uploads/c1.jpg" alt="First slide"></a>
		    </div>
		    <div class="carousel-item">
		      <a href="#"><img class="d-block w-100" src="uploads/c2.jpg" alt="Second slide"></a>
		    </div>
		  </div>
		  <a class="carousel-control-prev" href="#mainSlider" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#mainSlider" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	</div>

	<div class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="banner-img">
						<a href="maincat.php?catid=2"><img src="uploads/b5-1.jpg"></a>
					</div>
					<div class="info my-3">
						<span>From $19.99</span>
						<h4>Boys topwear</h4>
						<a class="btn-border btn btn-default" href="maincat.php?catid=2">Boys Wear ></a>
					</div>
				</div>
				<div class="col-md-6">
					<div class="banner-img">
						<a href="maincat.php?catid=1"><img src="uploads/b5-2.jpg"></a>
					</div>
					<div class="info my-3">
						<span>From $19.99</span>
						<h4>Girls topwear</h4>
						<a class="btn-border btn btn-default" href="maincat.php?catid=1">Girls Wear ></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--2 box images -->
	<div class="banner">
		<div class="container">
			<h2 class="my-3">New Releases</h2>
			<div class="row">
				<?php 
					foreach ($products as $product) {
						$stmt = $con->prepare("SELECT categories.Name , categories.ID FROM categories where ID IN (SELECT Parent FROM categories , subcategories WHERE categories.ID = subcategories.c_id AND subcategories.s_id = ?)");
					    $stmt->execute(array($product['s_id']));
						$catName = $stmt->fetch();
						
						?>
						<div class="col-md-3">
							<div class="banner-img">
								<a href="product.php?catname=<?php echo $catName['Name']?>&sname=dress&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $product['color_id'] ; ?>"><img src="uploads/<?php echo $product['url']; ?>"></a>
								<div class="price"><?php echo $product['Price'] ; ?> $</div>
							</div>
							<div class="info new my-3">
								<h6><?php echo $product['Name'] ; ?></h6>
								<!--<span><?php echo $product['Price'] ; ?> $</span> -->
								
								<a class="btn-border btn btn-default" href= "maincat.php?catid=<?php echo $catName['ID'] ?>"><?php echo $catName['Name']; ?> Wear ></a>
							</div>
						</div>
					<?php } ?>
			</div>
		</div>
	</div>

	<div class="banner">
		<div class="container">
			<img src="layout/images/cover2.jpg" class="w-100">
		</div>
	</div>

	<div class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="banner-img">
						<a href="subcategory.php?scatid=36"><img src="uploads/b4-1.jpg"></a>
					</div>
					<div class="info my-3">
						<span>From $19.99</span>
						<h4>Baby Tshirts</h4>
						<a class="btn-border btn btn-default" href= "maincat.php?catid=3">Babies Wears ></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="banner-img">
						<a href="subcategory.php?scatid=24"><img src="uploads/b4-2.jpg"></a>
					</div>
					<div class="info my-3">
						<span>From $19.99</span>
						<h4>Baby Dresses</h4>
						<a class="btn-border btn btn-default" href= "maincat.php?catid=3">Babies Wears ></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="banner-img">
						<a href="#"><img src="uploads/b4-3.jpg"></a>
					</div>
					<div class="info my-3">
						<span>From $19.99</span>
						<h4>Baby solobit</h4>
						<a class="btn-border btn btn-default" href= "maincat.php?catid=3">Babies Wears ></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	

	<!-- hr with title -->
	<div class="hr-title">
		<span>New Discounts</span>
	</div>

	<div class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-4 mb-4">
					<div class="banner-img">
						<a href="#"><img src="layout/images/b6-1.jpg"></a>
					</div>
					
				</div>
				<div class="col-md-4 mb-4">
					<div class="banner-img">
						<a href="#"><img src="layout/images/b6-2.jpg"></a>
					</div>
					
				</div>
				<div class="col-md-4 mb-4">
					<div class="banner-img">
						<a href="#"><img src="layout/images/b6-3.jpg"></a>
					</div>

				</div>
			</div>
		</div>
	</div>	

<script type="text/javascript">
	window.onload = function() {
			$('.cart-circle').html('<?php echo sizeof($_SESSION['cart']) ?>');
			$('.fav-circle').html('<?php echo sizeof($_SESSION['fav']) ?>');
            $(".cartHover").load(location.href + " .cartHover>*" , "");

	};
	
</script>

<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();

?>