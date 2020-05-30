<?php
ob_start(); //Output Buffering Start
session_start();
$page_title='Favourites'; 
include 'init.php';
if(isset($_SESSION['fav']) && empty($_SESSION['fav'])){
	?>

	<div class="container d-flex">
		<div class="favs">
	 		<span></span>
	 		<p>You Have No Favorite Items Yet</p>
	 		<p>You haven't added any products to your favorites yet, all you have to do is click the small heart icon on the product images.</p>
	 		<a href="index.php" class="main-btn button">Start Shopping</a>	
		</div>
	</div>
<?php
}
else if(isset($_SESSION['fav']) && !empty($_SESSION['fav'])){
	$favlength = sizeof($_SESSION['fav']);	
	?>
	<div class="container d-flex">
		<div class="favs w-100">
	 		<span></span>
	 		<p>You Have <?php echo $favlength ?> Favorite Items</p>
	 		<div class="fav-boxs">
	 			<div class="product-set">
	 				<div class="row">
	 					<?php 
	 						if(!empty($_SESSION['fav'] )){
							
							//Display Fav Products
							foreach ($_SESSION['fav'] as $key => $val) {
							$product = selectProduct($_SESSION['fav'][$key]['id'], $_SESSION['fav'][$key]['color'] );
							
							$pcolor = $product['ID'];

							if(!empty($product)){
	 						?>
		 					<div class="col-6 col-sm-4 col-md-3 mb-4">
		    					<div class="banner-img">
									<a href="product.php?catname=<?php echo $cat['Name']?>&sname=<?php echo $product['name'] ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $pcolor ; ?>" class= "img-wrapper"><img src="uploads/<?php echo $product['url'] ?>" class= "w-100">
									</a>
									
									<div class="wrapper">
										<div class="btn-wrap">
											<a href="javascript:;" class="favbtn btn fav-page" data-id= "<?php echo $product['P_id'];?>"data-color = "<?php echo $pcolor;?>">
												<div class="btn-icon">
													<i class="fas fa-heart">
												</i>
											</div>
											<div class="btn-text d-none d-xl-block"><span>Add to Favourite</span></div>	
										</a>
									</div>
										<div class="btn-wrap d-none d-xl-block">
											<a href="product.php?catname=<?php echo $pcat['Name']?>&sname=<?php echo $product['name'] ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $pcolor ; ?>" class="addcart btn">
												<div class="btn-icon"><i class="fa fa-eye"></i></div>
												<div class="btn-text"><span>View Product</span></div>
											</a>
										</div>	
									</div>
								</div>
								<div class="info text-center">
									<p class="m-0"><?php echo $product['Name']?></p>
									<span>$<?php if(!empty($product)){ echo $product['Price'] ; } else{ echo 'no price yet '; } ?></span>
								</div>
		 					</div>
	 				<?php  } } } ?>
	 				</div>
	 			</div>
	 		</div>
		</div>
	</div>
<?php
}


include $tpl . 'footer.php'; 

ob_end_flush();

?>