<?php
include 'connect.php';
$totalPrice = 0;


function getfields($field , $table){
	global $con;
	$stmt = $con->prepare("SELECT $field FROM $table");
	$stmt->execute();
	$all = $stmt -> fetchAll();
	return $all;
}

//Function to display Details of Added Product After Clicking AddCart Button
function cartbox($pid, $color , $size , $quantity ) {
  	global $con;
	$stmt = $con->prepare("SELECT DISTINCT product.* , productimgs.url , colors.* , subcategories.name ,subcategories.c_id  FROM product , inventory , productimgs , colors ,subcategories where product.P_id = inventory.P_id AND product.s_id = subcategories.s_id AND inventory.P_id = productimgs.P_id AND inventory.color_id = productimgs.color_id AND inventory.color_id = colors.ID AND productimgs.main = 1 AND inventory.P_id= ? AND inventory.color_id = ? ");

	$stmt->execute(array($pid , $color));
	$product = $stmt->fetch();
	$catname = selectParent($product['c_id']);
	if(!empty($product)){ 
		if(!isset($totalPrice)){
			$totalPrice = 0;
		}
		 ?>
		<a href="product.php?catname=<?php echo $catname['Name'] ;?>&sname=<?php echo $product['name'] ;?>&pid=<?php echo $product['P_id'] ?>&pcolor=<?php echo $product['ID'] ?>"><img src="uploads/<?php echo $product['url']?>"></a>
        <div class="info">
        	<p><?php echo $product["Name"]?></p>
        	<p>Size: <?php echo $size?></p>
        	<p class="pb-2">Quentity: <?php echo $quantity;?></p>
        	<span><?php echo $product["Price"]?> $</span>
        </div>
		
	<?php 
	$totalPrice += $product["Price"] * $quantity;
} 
	else {
		echo 'error';
	} 
   return $totalPrice;
}

 //Function to Display Details of Products in Cart & Fav Pages
 function selectProduct($pid, $color) {
      	global $con;
		$stmt = $con->prepare("SELECT DISTINCT product.* , productimgs.url , colors.* , subcategories.name ,subcategories.c_id FROM product , inventory , productimgs , colors , subcategories where product.P_id = inventory.P_id AND inventory.P_id = productimgs.P_id AND inventory.color_id = productimgs.color_id AND inventory.color_id = colors.ID AND product.s_id = subcategories.s_id AND productimgs.main = 1 AND inventory.P_id= ? AND inventory.color_id = ?");

		$stmt->execute(array($pid , $color));
		$product = $stmt->fetch();
		 
	return $product;
}

function selectParent($cid){
	global $con;
	$stmt = $con->prepare("SELECT * FROM categories WHERE ID IN (SELECT Parent FROM categories  WHERE ID = ?)");

	$stmt->execute(array($cid));
	$product = $stmt->fetch();
	return $product;
}
function checkUserFav($field , $table , $where , $value , $and2 , $value2 , $and3 , $value3){
	global $con;
	$getItems = $con -> prepare("SELECT $field FROM $table WHERE $where = ? AND $and2 = ? AND $and3 = ?");
	$getItems ->execute(array($value , $value2 , $value3));
	$item = $getItems -> fetch();
	return $item;
}
function checkUserOrder($field , $table , $where , $value , $and2 , $value2 , $and3 , $value3 , $and4 , $value4){
	global $con;
	$getItems = $con -> prepare("SELECT $field FROM $table WHERE $where = ? AND $and2 = ? AND $and3 = ? AND $and4 = ? ");
	$getItems ->execute(array($value , $value2 , $value3 , $value4));
	$item = $getItems -> fetch();
	return $item;
}

function checkFav($field , $table , $where , $value , $and2 , $value2){
	global $con;
	$getItems = $con -> prepare("SELECT $field FROM $table WHERE $where = ? AND $and2 = ? ");
	$getItems ->execute(array($value , $value2));
	$item = $getItems -> fetch();
	return $item;
}
//fav button to far - fas heart fav
function checkInSessionFav($array , $pid , $color){
	foreach ($array as $key => $val) {
       if ($array[$key]['id'] == $pid && $array[$key]['color'] == $color) {
        //echo 'plus';
        	return 1;
    	}
    }
    return 0;	
}

function getAll($field , $table , $where , $value , $and = NULL ){
	global $con;
	$getItems = $con -> prepare("SELECT $field FROM $table WHERE $where = ? $and");
	$getItems ->execute(array($value));
	$All = $getItems -> fetchAll();
	return $All;
}


function getRecord($field , $table , $where , $value ,$and =NULL ){
	global $con;
	$getItems = $con -> prepare("SELECT $field FROM $table WHERE $where = ? $and");
	$getItems ->execute(array($value));
	$item = $getItems -> fetch();
	return $item;
}


function checkItem($field, $table, $where , $val1 , $and , $val2){
	global $con;
	$getAll = $con -> prepare("SELECT $field FROM $table WHERE $where = ? AND $and = ?");
	$getAll ->execute(array($val1 , $val2));
	$count = $getAll ->rowCount();
	return $count;
}


function get_title(){
	
	global $page_title;

	if(isset($page_title)){
		echo $page_title;
	}
	else
	{
		echo "Default";
	}
}




/*
**Function to Count number of items rows 
*/

function countItems($item , $table){

	global $con;

	$stmt2 = $con->prepare("SELECT Count($item) FROM $table");

	$stmt2->execute();

	return $stmt2 ->fetchColumn();
	
}

/*
**Function getLatest with Limit
*/

function getLatest($select , $from , $order , $limit =5){
	global $con;
	$getStmt = $con -> prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit ");
	$getStmt ->execute();
	$rows = $getStmt ->fetchAll();
	return $rows;

};



/* subcategory functions */
function selectsubProducts($searchVal , $sizeQuery , $colorQuery , $scatid , $pricesql1 , $pricesql2 , $pricesql3 , $order){
	global $con;
	if($searchVal == ''){
		$getProducts= $con -> prepare("SELECT DISTINCT inventory.P_id ,inventory.color_id FROM inventory , product where product.P_id = inventory.P_id AND inventory.size_id IN {$sizeQuery} AND inventory.color_id IN {$colorQuery} AND inventory.P_id IN (SELECT DISTINCT P_id FROM product where s_id = ? AND Price IN (SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 )) $order");

		$getProducts-> execute(array($scatid ));
	}
	else{

		$getProducts= $con -> prepare("SELECT DISTINCT inventory.P_id ,inventory.color_id FROM inventory , product where product.P_id = inventory.P_id AND inventory.size_id IN {$sizeQuery} AND inventory.color_id IN {$colorQuery} AND product.Name LIKE ?  AND inventory.P_id IN (SELECT DISTINCT P_id FROM product where Price IN (SELECT Price FROM product WHERE Price > 1 $pricesql1 $pricesql2 $pricesql3 )) $order");

		$getProducts-> execute(array($searchVal));
	}
			$products = $getProducts-> fetchAll();
		return $products;

}

function SelectToArr($Arrs , $catType ){

	if($catType == 1){
		foreach ($Arrs as $Arr) {
			$sidArr[] = $Arr['s_id'];
		}
	}
	else if($catType == 2){
		foreach ($Arrs as $Arr) {
			$sidArr[] = $Arr['s_id'];
		}
	}
	else if($catType == 3){
		foreach ($Arrs as $Arr) {
			$sidArr[] = $Arr['color_id'];
		}
	}
	$sidQuery = '(' . implode(',', $sidArr) . ')';
	return $sidQuery;
}
	
