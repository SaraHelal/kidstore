<?php
	ob_start(); //Output Buffering Start
	session_start();
	include 'init.php';
	$page_title='Main Category'; 


	$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;
	$catname = isset($_GET['catname']) ? $_GET['catname']: '';

	$pcat = getRecord('*' , 'categories' , 'ID' , $catid , 'AND Parent = 0 LIMIT 1');
	if(!empty($pcat) ){
		$page_title= $pcat['Name'] ; 


		?>
		
		<div class="container">
			<div class="mainCat">
				<div class="breadcrumb-div">
					<ul class="list-unstyled">
						<li><a href="index.php" class="home">Home</a></li>
						<li><a>
							<?php echo $pcat['Name'] ?> 	
						</a></li>
					</ul>
				</div>
				<div class="main-show  d-flex">
					<div class="left-menu">
						<div class="holder">
							<span> <?php echo $pcat['Name'] ?><i class="fas fa-angle-down"></i></span>
						</div>
						<div class="hiddenLeftMenu">
							<ul class="list-unstyled p-2">
								
								<?php 
								$cats = getAll('*' , 'categories' , 'Parent' , $pcat['ID'] );
								
								foreach ($cats as $cat) {
									$subCats = getAll('*' , 'subcategories' , 'c_id' , $cat['ID'] );
									echo "<li class='subcat'>";
									echo '<span class="cat-title">' . $cat['Name'] .'</span>';
									echo '<ul class="list-unstyled subcat-drop">';
									
									if(!empty($subCats)){
										foreach ($subCats as $subCat) {
											echo '<li class="subcat">';
											echo '<a href="subcategory.php?scatid='. $subCat['s_id'].'">' . $subCat['name'] . '</a>';
											echo '</li>';
										}
									}
									echo "</ul>";
									echo "<hr class='custom-hr-menu'>";
								}

							echo "</ul>";                

							?>

						</div>
					</div>
					<div class="product-set">
						<div class="row">
							<?php 
							$getProducts= $con -> prepare("SELECT product.* ,subcategories.name  FROM product , categories , subcategories WHERE subcategories.s_id = product.s_id AND subcategories.c_id = categories.ID AND categories.Parent = ? "); 
							$getProducts-> execute(array($catid));
							$products = $getProducts-> fetchAll(); 
								
								foreach ($products as $product) {
									$items = getAll("*" , "productimgs" , "P_id", $product['P_id'] , 'AND main = 1');

									if(!empty($items)){	
										foreach ($items as $item ) {
											$pcolor = $item['color_id'];
											if(isset($_SESSION['fav'])){
											$fav = checkInSessionFav($_SESSION['fav'] , $product['P_id'] , $pcolor);
											}

											?>
											<div class="gridsize col-6 col-md-4 mb-4">
												<div class="banner-img">
													<a href="product.php?catname=<?php echo $pcat['Name']?>&sname=<?php echo $product['name'] ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $pcolor ; ?>" class= "img-wrapper"><img src="uploads/<?php echo $item['url'] ?>" class= "w-100">
													</a>
											<div class="wrapper">
												<div class="btn-wrap">
													<a href="javascript:;" class="favbtn btn" data-id= "<?php echo $product['P_id'];?>"data-color = "<?php echo $pcolor;?>">
														<div class="btn-icon">
															<i class="<?php
															if($fav){ echo 'fas' ;} else{ echo 'far'; } 
															?> fa-heart">
														</i>
													</div>
													<div class="btn-text d-none d-xl-block"><span>Add to Favourite</span></div>	
													</a>
												</div>
												<div class="btn-wrap d-none d-xl-block">
													<a href="product.php?catname=<?php echo $pcat['Name']?>&sid=<?php echo $product['s_id']?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $pcolor ; ?>" class="addcart btn">
														<div class="btn-icon"><i class="fa fa-eye"></i></div>
														<div class="btn-text"><span>View Product</span></div>
													</a>
												</div>	
											</div>
										</div>
										<div class="info text-center">
											<p class="m-0"><?php echo $product['Name']?></p>
											<span>$ <?php if(!empty($products)){ echo $product['Price'] ; } else{ echo 'no price yet '; } ?> </span>
										</div>

									</div>
								<?php } }}/*}*/?>
						</div>
					</div>
				</div>
			</div>
		</div>
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

