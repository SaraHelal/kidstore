<?php
	ob_start(); //Output Buffering Start
	session_start();
	$page_title='Orders'; 
	$noFooter = '';
	include 'init.php';


	if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
	?>

	<div class="container d-flex">
		<div class="cart-boxs">
		    <span></span>
	 		<p>You have no items in your cart.</p>
	 		<a href="index.php" class="main-btn button">Start Shopping</a>	
		</div>
	</div>
	<?php
	}
	elseif(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
		
	?>
		
		<div class="container">
			<div class="row cartDivTitle hidden-xs">
		        <div class="col-sm-2 col-md-2"></div>
		        <div class="col-sm-3 col-md-3">Product Description</div>
		        <div class="col-sm-7 col-md-7 ml-n5">
	            	<div class="row">
		                <div class="col-sm-3 col-md-3">Color</div>
		                <div class="col-sm-3 col-md-3 text-center">Body Size</div>
		                <div class="col-sm-2 col-md-4 text-center">Quantity</div>
		                <div class="col-sm-2 col-md-2 text-center">Price</div>     
	            	</div>
	        	</div>
	        	<div class="clear"></div>
			</div>
		
		<?php 			
			$total = 0;
			$count=0;
			foreach ($_SESSION['cart'] as $key => $val) {

				$product = selectProduct($_SESSION['cart'][$key]['id'], $_SESSION['cart'][$key]['color'] );
				$catname = selectParent($product['c_id']);
				if(!empty($product)){
				?>
				<div class="cartTable row py-3 align-content-center">
			        <div class="col-4  col-md-2">
			        	
			        	<a href="product.php?catname=<?php echo $catname['Name'] ;?>&sname=<?php echo $product['name'] ;?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $_SESSION['cart'][$key]['color'] ?>"><img src="uploads/<?php echo $product['url']?>" class="img-thumbnail" style="width: 120px;"></a>
			        	
			        </div>
			        <div class="hidden-xs col-md-3 row align-content-center">
			        	<div class="col-md-12">
			        		<p class="m-0"><?php echo $product['Name'] ;?></p>
			        	
					      	<a class="delete confirm" href="#" data-id="<?php echo $product['P_id'] ;?>" data-color="<?php echo $_SESSION['cart'][$key]['color'] ;?>" data-size="<?php echo $_SESSION['cart'][$key]['size'] ;?>" data-quantity="<?php echo $_SESSION['cart'][$key]['quantity'] ;?>"><i class="fa fa-times"></i> Delete</a>
					    </div>
			        </div>

			        <div class="col-8  col-md-7">
		            	<div class="row align-content-center h-100">
		            		<p class="visible-xs hidden-desc col-12 m-0"><?php echo $product['Name'] ;?></p>

			                <div class="col-12 col-md-3">
			                	<span class="visible-xs">color: </span>
			                	<span class="color" id="c<?php echo $count ; ?>"><?php echo '<style>.cartTable .color#c' . $count . '{background-color:' . $product['color'] . ';}</style>'; ?></span>
			                </div>
			                <div class="col-12 col-md-3 ">
			                	<span class="visible-xs">size: </span>
			                	<span class="size"><?php echo $_SESSION['cart'][$key]['size'] ; ?>
			                		
			                	</span>
			                </div>
			                <div class="col-12 col-md-4">
			                	<div data-id="<?php echo $product['P_id'] ;?>" data-color="<?php echo $_SESSION['cart'][$key]['color'] ;?>" data-size="<?php echo $_SESSION['cart'][$key]['size'] ;?>" data-quantity="<?php echo $_SESSION['cart'][$key]['quantity'] ; ?>" >
			                		<span class="visible-xs">Quantity: 
			                		</span>
			                		<i class="QEdit far fa-plus-square" data-edit = 'plus'>
			                			
			                		</i>
			                		<?php echo $_SESSION['cart'][$key]['quantity'] ; ?>
			                		<i class="QEdit far fa-minus-square <?php if($_SESSION['cart'][$key]['quantity'] == 1 ){ echo 'minusDisabled' ;} ; ?>" data-edit = 'minus'>
			                			
			                		</i>
			                	</div>
			                </div>
			                <div class="col-12 col-md-2 ">
			                	<span class="visible-xs">Price: 
			                	</span>
			                	<?php echo $product['Price'];
					      			$total+= $product['Price'] * $_SESSION['cart'][$key]['quantity'] ;?>$
					      	</div>
					      	<div class="visible-xs col-12">
					      		<a class="delete confirm" href="#" data-id="<?php echo $product['P_id'] ;?>" data-color="<?php echo $_SESSION['cart'][$key]['color'] ;?>" data-size="<?php echo $_SESSION['cart'][$key]['size'] ;?>" data-quantity="<?php echo $_SESSION['cart'][$key]['quantity'] ;?>">
					      			<i class="fa fa-times"></i> Delete
					      		</a>
					      	</div>     
		            	</div>
		        	</div>
		        	<div class="clear"></div>
				</div>

			<?php } 
			$count++;
			}?>
		<div class="total-price">
				<div class="row">
					<div class="offset-md-6 col-md-4 offset-1 col-6">
						<div class="">
							<p>Total Price </p>
							<p>Shipping Price </p>
							<p>The overall Total </p>
						</div>
					</div>
					<div class="price col-md-2 col-5">
						<div class="">
						<p><?php echo $total ?>$</p>
						<p class="free">FREE</p>
						<p><?php echo $total ?>$</p>
						</div>
					</div>
				</div>
			</div>
	 		<a href="#" class="completeCartBtn main-btn button float-right">Complete Order</a>	
			
	</div>

	<?php }
    include $tpl . 'footer.php'; 
    ob_end_flush();

?>