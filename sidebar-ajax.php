<?php 
	session_start();
	include 'connect.php';
	include 'include/functions/functions.php';
	$mainCats = getAll('*' , 'categories' , 'Parent' , 0);
	if(isset($_POST['id']) && $_POST['id'] == 0){

	
?>
	<div class="sidebar-header">
        <h5>Menu</h5>
        <span class="closeNav"><i class="fa fa-times fa-2x"></i></span>
    </div>
    <ul class="menu-items list-unstyled">
      	<?php foreach ($mainCats as $mainCat) { ?>
		        <li data-id="<?php echo $mainCat['ID'] ;?>">
		          <div class="d-flex justify-content-between">
		            <p><?php echo $mainCat['Name'] ;?></p>
		            <i class="arrow fa fa-chevron-right fa-2x" aria-hidden="true"></i>
		          </div>
		        </li>
		<?php } ?>
    </ul>

  <?php }

  else {
  	$getName = getRecord('*' , 'categories' , 'ID' , $_POST['id']);
   ?>
  	<div class="sidebar-header">
        <h5><?php echo $getName['Name'] ?></h5>
        <span class="closeNav"><i class="fa fa-times fa-2x"></i></span>
        <i class="backarrow arrow fa fa-chevron-left fa-2x float-left position-absolute" aria-hidden="true"></i>
    </div>
    <?php 
  	$cats = getAll('*' , 'categories' , 'Parent' , $_POST['id'] );
  	if(!empty($cats)){
  		echo "<ul class='menu-items list-unstyled'>";?>
      <div class="maincat d-flex justify-content-between">
        <a href="maincat.php?catid=<?php echo $getName['ID'] ; ?>" class="all-cats-sidebar">All <?php echo $getName['Name'] ; ?> Wears </a>
        <i class="arrow fa fa-chevron-right fa-2x" aria-hidden="true"></i>
      </div>
  		<?php foreach ($cats as $cat) { ?>
			<li data-id="<?php echo $cat['ID'] ;?>">
		        <div class="d-flex justify-content-between">
		        	<p><?php echo $cat['Name'] ;?></p>
		            <i class="arrow fa fa-chevron-right fa-2x" aria-hidden="true"></i>
		        </div>
		    </li>  			
  		<?php }
  		echo '</ul>';
  	} else{
  		$subs = getAll('*' , 'subcategories' , 'c_id' , $_POST['id'] );
      $maincat = getRecord('Parent' , 'categories' , 'ID' , $_POST['id']);
  		if(!empty($subs)){
  			echo "<ul class='menu-items list-unstyled'>";
  			foreach ($subs as $sub) { ?>
			<li data-id="" data-sid="<?php echo $sub['s_id'] ; ?>" data-maincat="<?php echo $maincat['Parent'] ; ?>" class="">
		        <div class="d-flex justify-content-between">
		        	<p><?php echo $sub['name'] ;?></p>
		            <i class="arrow fa fa-chevron-right fa-2x"  aria-hidden="true"></i>
		        </div>
		    </li>  			
  		<?php }
  		echo '</ul>';

  		}
  	}
  	?>
	
<?php  } ?>

     