
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
         <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo get_title();?></title>
        <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"/>
        <!-- Image zoomer -->
        <link href="<?php echo $css ?>slider.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $css ?>front.css"/>              
    </head>
    <body>
        
        <div class="upper-bar">
            <div class="container d-flex justify-content-between">
                <div class="login-hidden">
                <?php   if(isset($_SESSION['userName'])){?>
                 <span><?php echo $_SESSION['userName'] ; ?> </span><a href="logout.php"><span class="text-uppercase">Logout</span></a>
                <?php }
                else{?>
                    <a href="login.php"><span class="text-uppercase">SignUp | Login</span></a>               
                <?php }  ?>               
                </div>
                <div class="">
                   <a href="#"><span class="text-uppercase">Order follow</span></a>
                </div>
            </div>
        </div>
       <!-- Main navbar in Home pages   -->
        
        <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container nav-icons position-relative p-0">
            <div class="d-flex justify-content-between overflow-hidden my-1 w-100">
                <button id="btnSidebar" class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="logo mt-2">
                    <a href="index.php"><img src="layout/images/logo2.png" style="width: 244px;"></a>
                </div>
                <div class="search-inner d-none d-md-block">
                    <form class="w-100">
                        <div class="input-group input-group-lg mb-1">
                            <input type="text" class="searchInput form-control p-0" name="textSearch" placeholder="Search for products..."  aria-describedby="searchIcon">
                            <div class="input-group-append">
                                <span class="input-group-text p-0">
                                    <a href="#" class="searchBtn" >
                                        <i class="fa fa-search mt-2"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </form> 
                     <div class="searchResults row" id='searchFocus'>
                    </div>
                                                   
                </div>                
                <div class="nav-right d-flex my-2">
                    <div class="login-icon d-none d-lg-block">
                        <?php   if(isset($_SESSION['userName'])){?>
                                    <a class="myaccount d-flex" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                        <div class="icon">
                                            <i class="far fa-user"></i>
                                            <span class="desc d-none d-xl-block">Welcome</span>
                                        </div>
                                    </a>
                                    <div style="position: absolute;">
                                        <div class="popover-welcome welcome popover">
                                            <p>Welcome <span><?php echo $_SESSION['userName'] ;?></span></p>
                                          <a href="logout.php" class="log main-btn btn btn-block p-1">Log out</a>
                                        </div>
                                    </div>
                                <?php }else{?>
                                    <a class="myaccount d-flex" href="login.php"  aria-haspopup="true" aria-expanded="false">
                                        <div class="icon">
                                            <i class="far fa-user"></i>
                                            <span class="desc d-none d-xl-block">Login</span>
                                        </div>
                                    </a>
                                    <div style="position: absolute;">
                                        <div class="popover-welcome popover">
                                            <p>Be the first informed of discounts
                                            Discover new collections first.
                                            Pay cash at the door</p>
                                            <a href="login.php" class="log main-btn btn btn-primary btn-block">Login</a>
                                            <a href="register.php" class="log main-btn btn btn-primary btn-block first">Sign Up</a>
                                            
                                      </div>
                                    </div>            
                        <?php }?>
                        
                    </div>
                    <div class="heart-fav">
                        <a href="favs.php">
                            <div class="icon position-relative">
                                <span class="fav-circle rounded-circle position-absolute"><?php if(isset($_SESSION['fav'])){ echo sizeof($_SESSION['fav']); } else { echo 0; } ?>
                                </span>
                                <i class="far fa-heart fa-x"></i>
                                <span class="desc d-none d-xl-block">My Favourite</span>
                            </div>
                        </a>
                    </div>
                    <div class="addCart">
                        <a href="cart.php">
                            <div class="icon position-relative">
                                <span class="cart-circle rounded-circle position-absolute"><?php if(isset($_SESSION['cart'])){ echo sizeof($_SESSION['cart']); } else { echo 0; } ?></span>
                                <i class="fas fa-cart-plus"></i>
                                <span class="desc d-none d-xl-block">My Cart</span>
                            </div>
                        </a>
                        <div class="cartAdded position-absolute">
                            <div class="cart-popover added popover">
                                <span><span class= "numberProducts">1 item </span> has been added to your cart.</span>
                                <div class="cart d-flex pt-2 pb-3 justify-content-around">
                                    
                                </div>
                                <a href="cart.php" class="btn main-btn btn-block btn-primary">Go to Cart</a>
                            </div>
                        </div>
                        <div class="cartHover position-absolute">
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            $totalPrice = 0;
                            echo '<div class="cart-popover hovered popover">';
                        ?>
                        
                            <span>There are <span class="numberProducts"><?php echo sizeof($_SESSION['cart']);?> products</span> in your cart</span>
                            
                            <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                                echo '<div class="cartboxes d-flex">';
                                foreach ($_SESSION['cart'] as $key => $val) {
                                    echo'<div class="box d-flex pt-2 pb-3 justify-content-around">';
                                    $totalPrice+= cartbox($_SESSION['cart'][$key]['id'] , $_SESSION['cart'][$key]['color'] , $_SESSION['cart'][$key]['size'] , $_SESSION['cart'][$key]['quantity']);

                                    echo '</div>';

                                } 
                                echo '</div>';?>
                                
                               <?php echo '<p class="mb-2">Total Price : <span class="totalPrice">' . $totalPrice . ' $</span></p>'; 
                    
                                 echo '<a href="cart.php" class="main-btn btn btn-block btn-primary">Go to Cart</a>'; }  ?> 
                                                          
                            
                        <?php echo '</div>' ; } ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>  
    </nav>
    <!--hidden search in xs only -->
  <?php  if(!isset($hiddenSearch)){
    ?>

    <div class="hidden-search d-block d-md-none">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-inner d-flex">
                        <form class="w-100">
                            <div class="input-group input-group-lg mb-1">
                                <input type="text" class="searchInput form-control p-0"  name="textSearch" placeholder="Search for products..."  aria-describedby="searchIcon">
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <a href="#" class="searchBtn">
                                            <i class="fa fa-search mt-2"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </form>                                
                    </div> 
                </div>               
            </div>
        </div>
    </div>
   <?php } ?>




        

