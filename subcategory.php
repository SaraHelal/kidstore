<?php
ob_start(); //Output Buffering Start
session_start();
include 'init.php';
if(! isset($_SESSION['fav'])){
	$_SESSION['fav']= array();
}
if(! isset($_SESSION['cart'])){
	$_SESSION['cart']= array();
}

$scatid = isset($_GET['scatid']) && is_numeric($_GET['scatid']) ? intval($_GET['scatid']): 0;
$scat = getRecord('*' ,'subcategories' , 's_id' , $scatid);

$mainCat = $con->prepare("SELECT * FROM categories where ID IN( SELECT Parent FROM categories WHERE ID = ?)");
$mainCat->execute(array($scat['c_id']));
$cat = $mainCat->fetch();

if(isset($_GET['size'])){
	$sizeQuery = '(' . $_GET['size'] . ')' ;
	$sizeArr = explode(',' , $_GET['size']);
}
else{
	$getSizes = getfields('*' , 'sizes');
	foreach ($getSizes as $size) {
		$sizeArr[] = $size['ID'];
	}
	$sizeQuery = '(' . implode(',', $sizeArr) . ')';
}
$pricesql1 = '';
$pricesql2 = '';
$pricesql3 = '';
$pricesql4 = '';

$minPrice = [];
if(isset($_GET['price'])){
	$priceArr = explode(',' , $_GET['price']);
	$count = 0;
	
	foreach ($priceArr as $price) {
		if($price == '1'){
			if($count == 0){
				$pricesql1 = 'AND (Price BETWEEN 20 AND 35)';
				
			}
			else if($count > 0){
				$pricesql1 = 'OR (Price BETWEEN 20 AND 35)';
			}
			$minPrice[] = 20;
			$count++;

		}
		else if($price == '2'){
			if($count == 0){
				$pricesql2 = 'AND (Price BETWEEN 35 AND 70)';
				
			}
			else if($count > 0){
				$pricesql2 = 'OR (Price BETWEEN 35 AND 70)';
			}
			$minPrice[] = 35;
			$count++;
		}
		
		else if($price == '3'){
			if($count == 0){
				$pricesql3 = 'AND (Price BETWEEN 70 AND 100)';
			}
			else if($count > 0){
				$pricesql3 = 'OR (Price BETWEEN 70 AND 100)';
			}
			$minPrice[] = 70;
			$count++;

		}

		else if($price == '4'){
			if($count == 0){
				$pricesql4 = 'AND (Price BETWEEN 100 AND 150)';
			}
			else if($count > 0){
				$pricesql4 = 'OR (Price BETWEEN 100 AND 150)';
			}
			$minPrice[] = 100;
			$count++;

		}
	}
}

if(isset($_GET['color'])){
	$colorArr = explode(',' , $_GET['color']);
	$colorQuery = '(' . $_GET['color'] . ')' ;
}
else{
	$getColors = getfields('*' , 'colors');
	foreach ($getColors as $color) {
		$colorArr[] = $color['ID'];
	}
	$colorQuery = '(' . implode(',', $colorArr) . ')';
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];
	if($sort == 'Default'){
		$order = '';
		$sortid = 0;
	}
	else if($sort == 'AscPrice'){
		$order = 'ORDER BY product.Price ASC';
		$sortid = 1;
	}
	else if($sort == 'DescPrice'){
		$order = 'ORDER BY product.Price DESC';
		$sortid = 2;
	}
	else if($sort == 'Newest'){
		$order = 'ORDER BY product.Add_Date';
		$sortid = 3;
	}
	else{
		$order = '';
		$sortid = 0;
	}
}
else{
	$order = '';
	$sortid = 0;

}
	//echo 'Order is : ' . $order;


$getProducts= $con -> prepare("SELECT DISTINCT inventory.P_id ,inventory.color_id FROM inventory , product where product.P_id = inventory.P_id AND inventory.size_id IN {$sizeQuery} AND inventory.color_id IN {$colorQuery} AND inventory.P_id IN (SELECT DISTINCT P_id FROM product where s_id = ? AND Price IN (SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 $pricesql4 )) $order");


$getProducts-> execute(array($scatid));
$products = $getProducts-> fetchAll();


if(!empty($scat)){
$page_title= $scat['name'] ; 
if(!empty($products)){
	?>
	<div class="container">
		<div class="mainCat">

			<div class="breadcrumb-div">
				<ul class="list-unstyled">
					<li><a href="index.php" class="home">Home</a></li>
					<li><a href="maincat.php?catid=<?php echo $cat['ID'] ?>">
						<?php echo $cat['Name'] ?>

					</a></li>
					<li><a><?php echo $scat['name'] ;?></a></li>
				</ul>
			</div>
			<div class="main-show  d-flex">
				<div class="left-menu d-none d-lg-block ">
					<a class="clearFilters" href="#" >Clear All Filters</a>
					<div class="feature-box">
						<span>Body Size</span>
						<div class="box-selection overflow-auto">
							<?php
							$getSizes =  $con->prepare("SELECT DISTINCT size_id , sizes.* FROM inventory , sizes , product , subcategories where inventory.size_id = sizes.ID AND product.P_id = inventory.P_id AND product.s_id = subcategories.s_id AND inventory.color_id IN {$colorQuery} AND subcategories.s_id = ? AND product.Price IN(SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 $pricesql4) AND inventory.quantity != 0 ORDER BY sizes.orderSize"); 
							$getSizes-> execute(array($scatid));
							$sizes = $getSizes-> fetchAll();
							
							if(!empty($sizes)){
								$sizecount= 0;
								foreach ($sizes as $size) {	$sizecount++;
									?>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="s<?php echo $sizecount; ?>"   name="example1" data-group = "size<?php echo $sizecount; ?>" <?php if(isset($_GET['size'])){
									 	if(in_array($size["ID"] , $sizeArr)){ echo 'checked' ;} }
									 	 ?>>
										<label class="custom-control-label filter-change filter-checkbox" for="s<?php echo $sizecount; ?>" data-size = "<?php echo $size['ID']; ?>" data-edit = 'change' ><?php echo $size['size'] ; ?></label>
									</div>	    
								<?php } } ?>

							</div>
						</div>

						<div class="feature-box">
							<?php 
								$getPrice =  $con->prepare("SELECT min(Price) AS min , max(Price) AS max FROM product , subcategories where product.s_id = subcategories.s_id AND subcategories.s_id = ? AND P_id IN (SELECT P_id FROM inventory WHERE inventory.color_id IN {$colorQuery} AND inventory.size_id IN {$sizeQuery})"); 
								$getPrice-> execute(array($scatid));
								$priceRange = $getPrice-> fetch(); 
								if(!empty($priceRange)){
							?>
							<span>Price</span>
								<div class="box-selection overflow-auto">
									<div class="row">
										<?php if($priceRange['max'] >= 20 && $priceRange['min'] <= 35 ){ ?>
											<div class="custom-control custom-checkbox mb-3 col-md-12">
												<input type="checkbox" class="custom-control-input" id="p1" name="example1" <?php if(isset($_GET['price'])){
														foreach ($minPrice as $price) {
															if($price == 20){
																echo 'checked';
															}
														}

												 	 }
												 	 ?>>
												<label class="custom-control-label filter-change filter-checkbox" for="p1" data-minPrice= "20" data-maxPrice="35" data-price ="1" data-edit = 'change' >20 - 35</label>
											</div>
											<?php }
											
										 if($priceRange['max'] >= 35 && $priceRange['min'] <= 70){ ?>
										<div class="custom-control custom-checkbox mb-3 col-md-12">
											<input type="checkbox" class="custom-control-input" id="p2" name="example1"<?php if(isset($_GET['price'])){
										 	 
													foreach ($minPrice as $price) {
														if($price == 35){
															echo 'checked';
														}
													}

											 	 }
											 	 ?>>
											<label class="custom-control-label filter-change filter-checkbox" for="p2" data-minPrice= "35" 
											data-maxPrice="70" data-price ="2" data-edit = 'change'>35 - 70</label>
										</div>	
									<?php } 
									 if($priceRange['max'] >= 70 && $priceRange['min'] <= 100){ ?>
										<div class="custom-control custom-checkbox mb-3 col-md-12">
											<input type="checkbox" class="custom-control-input" id="p3" name="example1"<?php if(isset($_GET['price'])){
										 	 
													foreach ($minPrice as $price) {
														if($price == 70){
															echo 'checked';
														}
													}

											 	 }
											 	 ?>>
											<label class="custom-control-label filter-change filter-checkbox" for="p3" data-minPrice= "70" data-maxPrice="100" data-price ="3" data-edit = 'change'>70 - 100</label>
										</div>
									<?php } ?>

									<?php 
									 if($priceRange['max'] >= 100 && $priceRange['min'] <= 150){ ?>
										<div class="custom-control custom-checkbox mb-3 col-md-12">
											<input type="checkbox" class="custom-control-input" id="p4" name="example1"<?php if(isset($_GET['price'])){
										 	 
													foreach ($minPrice as $price) {
														if($price == 100){
															echo 'checked';
														}
													}

											 	 }
											 	 ?>>
											<label class="custom-control-label filter-change filter-checkbox" for="p4" data-minPrice= "100" data-maxPrice="150" data-price ="4" data-edit = 'change'>100 - 150</label>
										</div>
									<?php } ?>
									</div>		
								</div>
							<?php } ?>
						</div>								

						<div class="feature-box">
							<span>Color</span>
							<div class="box-selection overflow-auto">
								<div class="color-palette">
									<?php 
									$getColors = $con -> prepare("SELECT DISTINCT color_id , colors.* FROM inventory , colors , product , subcategories where inventory.color_id = colors.ID AND product.P_id = inventory.P_id AND product.s_id = subcategories.s_id  AND subcategories.s_id = ? AND inventory.size_id IN {$sizeQuery} AND product.Price IN(SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 $pricesql4)");
									$getColors ->execute(array($scatid));
									$colors = $getColors -> fetchAll();
									if(!empty($colors)){
										$count = 0;
										echo '<div class="color-palette">';
										foreach ($colors as $color) {
											$count++; ?>
											<input type="checkbox" id="c<?php echo $count; ?>"  name="cb" data-group = "color<?php echo $count; ?>"  <?php if(isset($_GET['color'])){
											 	if(in_array($color["ID"] , $colorArr)){ echo 'checked' ;} }
											 	 ?>>
											
											<label for="c<?php echo $count; ?>" class="filter-change filter-checkbox mainClass" data-color= "<?php echo $color['ID']; ?>" data-scatid = "<?php echo $scatid; ?>" data-sname = "<?php echo $scat['name'];?>" data-cat = "<?php echo $cat['Name'];?>"
												data-edit = 'change'></label> 
											<?php echo '<style>.color-palette #c' . $count . ' + label:before{background-color:' . $color['color'] . ';}</style>'; 
											 }
										 	echo '</div>';
										 	} ?>
									
								</div>
							</div>
						</div>		
					</div>
					<div class="product-set main-cat w-100">
						<div class="choises d-flex justify-content-between mb-n3">
							<div class="count-products d-flex align-items-center">
								<p class="m-0">We found <span><?php echo sizeof($products) ;?></span> products</p>
							</div>
							
							<div class="hidden-btns d-flex py-4">
								<button class="filter-btn-choose form-control" data-sid= <?php echo $scatid ; ?>>
									Filter
								</button>


								
									<select class="suggest-list form-control">
										<option value="0" <?php if($sortid == 0){ echo 'selected'; } ?>>Suggested</option>
										<option value="1" <?php if($sortid == 1){ echo 'selected'; } ?>>Towards increasing price</option>
										<option value="2" <?php if($sortid == 2){ echo 'selected'; } ?>>Towards decreasing price</option>
										<option value="3" <?php if($sortid == 3){ echo 'selected'; } ?>>The newests</option>
									</select>
								
							</div>
							<div class="arrange d-flex mt-3">
								<div class="bars d-flex my-3">
									<div class="nBars two-bars d-flex mr-2 ">
										<div class="bar"></div>
										<div class="bar"></div>
									</div>
									<div class="nBars three-bars d-flex">
										<div class="bar"></div>
										<div class="bar"></div>
										<div class="bar"></div>
									</div>
								</div>
								<div class="suggested">
									<select class="suggest-list form-control">
										<option value="0" <?php if($sortid == 0){ echo 'selected'; } ?>>Suggested</option>
										<option value="1" <?php if($sortid == 1){ echo 'selected'; } ?>>Towards increasing price</option>
										<option value="2" <?php if($sortid == 2){ echo 'selected'; } ?>>Towards decreasing price</option>
										<option value="3" <?php if($sortid == 3){ echo 'selected'; } ?>>The newests</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<?php 

								foreach ($products as $product) {
									$getImg= $con -> prepare("SELECT url FROM productimgs
									where P_id = ? AND color_id= ? and main=1");
								$getImg-> execute(array($product['P_id'] , $product['color_id']));
								$img = $getImg-> fetch();
							?>			

							<div class="gridsize col-6 col-md-4  mb-4">
								<div class="banner-img">
									<a href="product.php?catname=<?php echo $cat['Name']?>&sname=<?php echo $scat['name'] ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $product['color_id'] ; ?>"><img src="uploads/<?php echo $img['url'] ?>" class= "w-100"></a>

									<div class="wrapper">
										<div class="btn-wrap">
											<a href="javascript:;" class="favbtn btn" data-id= "<?php echo $product['P_id'];?>" data-color = "<?php echo $product['color_id'];?>">
												<div class="btn-icon">
													<i class="<?php
													if(isset($_SESSION['fav'])){
													$fav = checkInSessionFav($_SESSION['fav'] , $product['P_id'] , $product['color_id']);
													if($fav){ echo 'fas' ;} else{ echo 'far'; } } 
													?> fa-heart">
												</i>
											</div>
											<div class="btn-text d-none d-xl-block"><span>Add to Favourite</span></div>	
										</a>
									</div>
									<div class="btn-wrap d-none d-xl-block">
										<a href="product.php?catname=<?php echo $cat['Name']?>&sname=<?php echo $scat['name'] ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $product['color_id'] ; ?>" class="addcart btn">
											<div class="btn-icon"><i class="fa fa-eye"></i></div>
											<div class="btn-text"><span>View Product</span></div>
										</a>
									</div>	
								</div>
							</div>
							<div class="info text-center">
								<?php 
									$productInfo = getRecord('*' , 'product' , 'P_id' , $product['P_id']);
								?>
								<p class="m-0"><?php echo $productInfo['Name']?></p>
								<span>$<?php if(!empty($products)){ echo $productInfo['Price'] ; } else{ echo 'no price yet '; }?></span>
							</div>
						</div>
						<?php 
					} 	?>
				</div>
			</div>
		</div>
	</div>

	<div class="hidden-filter">
		<div class="hidden-filter-title">
    		<h5>Filter</h5>
    		<span class="closeNav"><i class="fa fa-times"></i></span>
  		</div>
  		<div class="filter-features">
		    <div class="feature-box">
		      <span class="title">Body Size</span>
		      <span><i class="fas fa-plus float-right"></i></span>
		    </div>
		    <div class="menu-item py-3">
		      	<div class="size overflow-auto">
		 
			        <?php
			          global $con;
			         $getSizes =  $con->prepare("SELECT DISTINCT size_id , sizes.* FROM inventory , sizes , product , subcategories where inventory.size_id = sizes.ID AND product.P_id = inventory.P_id AND product.s_id = subcategories.s_id AND inventory.color_id IN {$colorQuery} AND subcategories.s_id = ? AND product.Price IN(SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 $pricesql4) AND inventory.quantity != 0 ORDER BY sizes.orderSize"); 
						$getSizes-> execute(array($scatid));
			          $sizes = $getSizes-> fetchAll();
			          if(!empty($sizes)){
			                $sizecount= 0;
			                foreach ($sizes as $size) { $sizecount++;
			                  ?>

			                  <div class="custom-control custom-checkbox mb-3">
			                    <input type="checkbox" class="custom-control-input" id="ss<?php echo $sizecount; ?>"   name="example1" data-group = "size<?php echo $sizecount; ?>"
			                    	<?php if(isset($_GET['size'])){
									 	if(in_array($size["ID"] , $sizeArr)){ echo 'checked' ;} }
							 		?>>
			                    <label class="custom-control-label filter-checkbox" for="ss<?php echo $sizecount; ?>" data-size = "<?php echo $size['ID']; ?>" ><?php echo $size['size'] ; ?></label>
			                  </div>      
		           	<?php } } ?>
		      </div>
		    </div>
			<div class="feature-box">
				<?php 
					$getPrice =  $con->prepare("SELECT min(Price) AS min , max(Price) AS max FROM product , subcategories where product.s_id = subcategories.s_id AND subcategories.s_id = ? AND P_id IN (SELECT P_id FROM inventory WHERE inventory.color_id IN {$colorQuery} AND inventory.size_id IN {$sizeQuery})"); 
					$getPrice-> execute(array($scatid));
					$priceRange = $getPrice-> fetch(); 
					if(!empty($priceRange)){
				?>
						<span class="title">Price</span>
  						<span><i class="fas fa-plus float-right"></i></span>
			</div>
			<div class="menu-item py-3">
				<div class="price overflow-auto">
				<?php  if($priceRange['max'] >= 20 && $priceRange['min'] <= 35 ){ ?>
					<div class="custom-control custom-checkbox mb-3 col-md-12">
						<input type="checkbox" class="custom-control-input" id="pp1" name="example1" <?php if(isset($_GET['price'])){
								foreach ($minPrice as $price) {
									if($price == 20){
										echo 'checked';
									}
								}

						 	 }
						 	 ?>>
						<label class="custom-control-label filter-checkbox" for="pp1" data-minPrice= "20" data-maxPrice="35" data-price ="1" >20 - 35</label>
					</div>
					<?php }
					
				 if($priceRange['max'] >= 35 && $priceRange['min'] <= 70 ){ ?>
				<div class="custom-control custom-checkbox mb-3 col-md-12">
					<input type="checkbox" class="custom-control-input" id="pp2" name="example1"<?php if(isset($_GET['price'])){
				 	 
							foreach ($minPrice as $price) {
								if($price == 35){
									echo 'checked';
								}
							}

					 	 }
					 	 ?>>
					<label class="custom-control-label filter-checkbox" for="pp2" data-minPrice= "35" 
					data-maxPrice="70" data-price ="2">35 - 70</label>
				</div>	
			<?php } 
			  if($priceRange['max'] >= 70 && $priceRange['min'] <= 100 ){ ?>
				<div class="custom-control custom-checkbox mb-3 col-md-12">
					<input type="checkbox" class="custom-control-input" id="pp3" name="example1"<?php if(isset($_GET['price'])){
				 	 
							foreach ($minPrice as $price) {
								if($price == 70){
									echo 'checked';
								}
							}

					 	 }
					 	 ?>>
					<label class="custom-control-label filter-checkbox" for="pp3" data-minPrice= "70" data-maxPrice="100" data-price ="3">70 - 100</label>
				</div>
			<?php 
			} 

			 if($priceRange['max'] >= 100 && $priceRange['min'] <= 150){ ?>
				<div class="custom-control custom-checkbox mb-3 col-md-12">
					<input type="checkbox" class="custom-control-input" id="pp4" name="example1"<?php if(isset($_GET['price'])){
				 	 
							foreach ($minPrice as $price) {
								if($price == 100){
									echo 'checked';
								}
							}

					 	 }
					 	 ?>>
					<label class="custom-control-label filter-checkbox" for="pp4" data-minPrice= "100" data-maxPrice="150" data-price ="4" >100 - 150</label>
				</div>
			<?php } ?>
			</div>		
		</div>
		<?php } ?>
		
    

    <div class="feature-box">
      <span class="title">Colors</span>
      <span><i class="fas fa-plus float-right"></i></span>
    </div>
    
	<div class="menu-item py-3">
	<div class="color overflow-auto">
		<div class="color-palette">
			<?php 
			$getColors = $con -> prepare("SELECT DISTINCT color_id , colors.* FROM inventory , colors , product , subcategories where inventory.color_id = colors.ID AND product.P_id = inventory.P_id AND product.s_id = subcategories.s_id  AND subcategories.s_id = ? AND inventory.size_id IN {$sizeQuery} AND product.Price IN(SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 $pricesql4)");
			$getColors ->execute(array($scatid));
			$colors = $getColors -> fetchAll();
			if(!empty($colors)){
				$count = 0;
				echo '<div class="color-palette">';
				foreach ($colors as $color) {
					$count++; ?>
					<input type="checkbox" id="cc<?php echo $count; ?>"  name="cb" data-group = "color<?php echo $count; ?>"  <?php if(isset($_GET['color'])){
					 	if(in_array($color["ID"] , $colorArr)){ echo 'checked' ;} }
					 	 ?>>
					
					<label for="cc<?php echo $count; ?>" class="filter-checkbox mainClass" data-color= "<?php echo $color['ID']; ?>" data-scatid = "<?php echo $scatid; ?>" data-sname = "<?php echo $scat['name'];?>" data-cat = "<?php echo $cat['Name'];?>"
						></label> 
					<?php echo '<style>.color-palette #cc' . $count . ' + label:before{background-color:' . $color['color'] . ';}</style>'; 
					 }
				 	echo '</div>';
				 	} ?>
			
				</div>
			</div>
		</div>		
    </div>
    
  <button class="btn btn-primary btn-block filter-button responsiveBtnFilter" data-edit = "filterBtn" type="button">Filter</button>
	</div>


<?php

}

else{?>

	<div class="container">
		<div class="noProducts my-5">
			<p class="alert alert-dark">Your Filter has no Result .. Try another Filter</p>
			<div class='alert alert-info'>You will be Redirect to   Previous Page After 4 seconds</div>
		<?php header("refresh: 4; url =subcategory.php?scatid=" . $scatid . "") ?>
		</div>
	</div>

<?php 
}?>
 	<script type="text/javascript">
    	document.title = "<?=$page_title;?>"
	</script>
<?php }

else {
header("Location: index.php"); 

exit();
}

include $tpl . 'footer.php'; 
ob_end_flush();

?>