<?php
	ob_start(); //Output Buffering Start
	session_start();
	//$page_title='Product'; 
	include 'init.php';
	$catname = isset($_GET['catname']) ? $_GET['catname']: 'Default';
	$cat = getRecord( '*' , 'categories' , 'Name' , $catname);
	
	$sname = isset($_GET['sname']) ? $_GET['sname']: 'Default';
	
	$pid = isset($_GET['pid']) && is_numeric($_GET['pid']) ? intval($_GET['pid']): 0;
	$pcolor = isset($_GET['pcolor']) ? $_GET['pcolor']: '';
	
	$product = getRecord('*' , 'product' , 'P_id' , $pid);

	$getImgs= $con -> prepare("SELECT * FROM productimgs where P_id = ? AND color_id = ?"); 
	$getImgs-> execute(array($pid , $pcolor));
	
	$imgs = $getImgs-> fetchAll(); 
	
	if(!empty($product)){
    	$name = $product['Name'] ;
    	$page_title = $name;
    	$page_title = 'Product';
    	//echo $name;

    ?>
    
    <div class="container">
    	<div class="mainCat">
    		<div class="breadcrumb-div">
    			<ul class="list-unstyled pt-3">
				  	<li><a href="index.php" class="home">Home</a></li>
					<li><a href="maincat.php?catid=<?php if(!empty($cat)){ echo
						$cat['ID']; }?>"><?php echo $catname ?></a>
				  	</li>
				  	<li><a href="subcategory.php?scatid=<?php echo $product['s_id']?>"><?php echo $sname ?></a></li>
				  	<li><a><?php echo  $product['Name']; ?></a></li>
				</ul>
    		</div>
    		
    		<div class="main d-flex">
    			<div class="row">
    				<div class="col-sm-12 col-md-6">
			    		<div class="left-slider ">
						    <div class="row">
						    	<div class="col-sm-4 col-md-4">
									<div class="small-img">
									  <div class="small-container">
									  	<i class="fas fa-angle-up icon-right fa-2x d-none d-sm-block" id="prev-img"></i>
									    <div id="small-img-roll" class="d-none d-sm-block">

									    <?php 
									    if(!empty($imgs)){
																				
							    			foreach ($imgs as $img) {
							    				echo '<img src="uploads/' . $img['url'] . '" class="show-small-img img-fluid" alt="">';
							    			}
									    }
									    		
									    	?>
									    </div>
									    <i class="fas fa-angle-down icon-left fa-2x d-none d-sm-block" id="next-img"></i>
									  </div>
									</div>
								</div>
								<div class="col-sm-8 col-md-8">
									<div class="show" href="uploads/<?php echo 
									$imgs[0]['url'] ;?>" data-toggle="modal" data-target="#modalImage">
										<?php if(!empty($imgs)){
											echo '<img src="uploads/' . $imgs[0]['url'] . '" id="show-img" class="img-fluid">';
										}
									  	?>
									</div>
									<a href="javascript:;" class="favbtn btn" data-id= "<?php echo $product['P_id'];?>" data-color = "<?php echo $pcolor;?>">
									<div class="btn-icon">
												<i class="<?php
												if(isset($_SESSION['fav'])){
												$fav = checkInSessionFav($_SESSION['fav'] , $product['P_id'] , $pcolor);
												if($fav){ echo 'fas' ;} else{ echo 'far'; } } 
												?> fa-heart">
											</i>
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
			    		<div class="product-desc mt-4">
			    			<h2><?php echo $product['Name'] ?></h2>
			    			<p>Product Code: <?php echo $product['P_code'] ?></p>
			    			<div class="prices d-flex">
			    				<div class="advance-price">
			    					<p class="m-0">Advance Price:</p>
			    					<span><?php echo $product['Price'] ;?>$ TRY</span>
			    				</div>
			    				<div class="installment-price">
			    					<p class="m-0">Installment Price:</p>
			    					<span><?php echo $product['Price']; ?>$</span>
			    				</div>
			    			</div>
			    			<div class="colors">
			    				<span>Colors</span>
			    				<?php 
			    				//$pcolor = 'Beige Melange';
			    				$getColors =  $con -> prepare("SELECT Distinct colors.* from inventory, colors WHERE inventory.color_id = colors.ID AND inventory.P_id = ? " );
			    				$getColors-> execute(array($pid));

			    				$colors = $getColors-> fetchAll(); 

			    				
			    				if(!empty($colors)){
			    					$count = 0;
			    					foreach ($colors as $color) {

				    					if($pcolor == $color['ID']){  ?> 
				    						<a href="#" class="samecolor" style="background-color: <?php echo $color['color'] ?>;"></a>
				    			<?php 		$colorname = $color['color'];	
				    					} 
				    					else{ ?> 
				    						<a href="product.php?catname=<?php echo $catname ;?>&sname=<?php echo $sname; ?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $color['ID']; ?>" style="background-color: <?php echo $color['color'] ?>;"></a>
				    			<?php 
				    				}

			    					 }
			    				}
			    				?>
			    				
			    				<p class="m-0"><span>Color:</span> <?php if(!empty ($colorname )) { echo $colorname ; } ?></p>
			    			</div>
			    			<div class="sizes d-flex position-relative">
			    				<span>
			    				<?php 
			    				$stmt =  $con -> prepare("SELECT inventory.* , sizes.size FROM inventory , sizes where sizes.ID = inventory.size_id AND inventory.P_id = ? AND inventory.color_id = ? " );
			    				$stmt-> execute(array($pid , $pcolor));

			    				$pAttrs = $stmt-> fetchAll();
			    				 

			    				if(!empty($pAttrs)){
			    					foreach ($pAttrs as $pAttr) {
			    						if($pAttr['size'] == 'one size'){
			    							if($pAttr['Quantity'] == 0){?>
			    								<a href="#" class="finished disable btn text-uppercase"><?php echo $pAttr['size'] ; ?></a>
			    							<?php }
			    							else{ ?>
			    								<a href="#" class="btn text-uppercase selected " ><?php echo $pAttr['size'] ; ?></a>
			    							<?php }
			    							
			    							
			    						}
			    						else if($pAttr['Quantity'] == 0){
			    							echo '<a href="#" class="finished btn text-uppercase">'. $pAttr['size'] .'</a>';
			    						}
			    						else{
			    							echo '<a href="#" class="btn text-uppercase">'. $pAttr['size'] .'</a>';
			    						}
			    						
			    					}
			    				}
			    				?>
			    				<div class="sizepopover popover"><p>Please choose size first.</p></div>
			    			</span>
			    			</div>
			    			
			    			<button class="addBtn main-btn btn btn-primary btn-block" data-id= "<?php echo $product['P_id'] ?>" data-color = "<?php echo $pcolor ?>">Add to Cart</button>
			    		</div>
			    	</div>
			    </div>
	    	</div>
	    	<div class="details">
	    		<p>Our estimated delivery time will vary from 3-5 business days to your address.</p>

				<p>If you are not satisfied with the products you purchased, you can return it for 120 days with assurance.
				</p>
				<h6>Product Content and Features</h6>
				<div class="features d-flex">
					<div>
						<p><span>Product Content: </span></p>
						<p><span>Main Fabric: </span><?php echo $product['Main Fabric'] ?></p>
					</div>
					<div>
						<p><span>Product Features: </span></p>
						<p><span>Pattern: </span><?php echo $product['pattern'] ?></p>
						<p><span>Thikness: </span><?php echo $product['Thikness'] ?></p>
						<p><span>Product Type: </span><?php echo $product['Product Type'] ?></p>
					</div>
				</div>
	    	</div>
    	</div>
    </div>


	<div class="modal fade" id="modalImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog mt-0" role="document">
	    <div class="modal-content">
	      <div class="modal-header p-2">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body pt-0">
	      <img src="" class="w-100">
	      </div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
    	document.title = "<?=$page_title;?>"
	</script>
<?php
}

include $tpl . 'footer.php'; 
ob_end_flush();

?>