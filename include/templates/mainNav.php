<?php
  ob_start();
    include 'connect.php';

    $getpCats = $con -> prepare("SELECT * FROM categories WHERE Parent = 0");
    $getpCats ->execute();
    $pcats = $getpCats ->fetchAll();
    

?>
<div class="hidden-nav d-none d-lg-block">
</div>
 <nav class="navbar navbar-expand-lg navbar-light">
      

      <div class="collapse navbar-collapse d-none d-lg-block" id="navbarHeader">
        <div class="container position-relative p-0">
            <ul class="mainNav navbar-nav mr-auto">
              <?php

              foreach ($pcats as $pcat) {
                
               /* $getSubCats= $con -> prepare("SELECT categories.Name AS cat_name , subcategories.name AS sub_name FROM categories , subcategories WHERE categories.ID = subcategories.cat_id AND "); */
               $getCats= $con -> prepare("SELECT * FROM categories WHERE Parent = ? ");
                $getCats-> execute(array($pcat['ID']));
                $cats = $getCats-> fetchAll();

                ?>
              <li class="nav-item dropdown">
                <a class="nav-link text-uppercase" href="maincat.php?catid=<?php echo $pcat['ID'] ?>" role="button" data-hover="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo $pcat['Name'] ?>
                </a>
                <div class="dropdown-menu static-drop">
                    <div class="flexdrop col-3 p-4">
                     

                        <?php
                      
                          foreach ($cats as $cat) {
                            $getSubCats= $con -> prepare("SELECT * FROM subcategories , categories 
                             WHERE subcategories.c_id = categories.ID AND subcategories.c_id = ?  "); 
                            $getSubCats-> execute(array($cat['ID']));
                            $subCats = $getSubCats-> fetchAll();
                            
                            echo '<ul>';
                            echo "<li>";
                            echo '<span class="dropdown-item cat-title">' . $cat['Name'] .'</span>';
                            echo '<ul class="subcat-drop">';
                            foreach ($subCats as $subCat) {
                              
                              if(!empty($subCats)){
                                echo '<li>';
                                echo '<a href="subcategory.php?scatid='. $subCat['s_id'].'">' . $subCat['name'] . '</a>';
                                echo '</li>';
                              }
                            }
                            echo '</ul>';

                            echo "</li>";
                            
                          }
                          echo '</ul>';
                      

                        ?>
                    </div>
                    <div class="menuImgs w-100 col-9">
                      <div class="imgs d-flex w-100" style="padding:28px;">
                        <?php 
                         $getSubImgs= $con -> prepare("SELECT * FROM subcategories  
                             WHERE subcategories.c_id IN (SELECT ID FROM categories WHERE Parent = ?) AND img !='' LIMIT 3"); 
                            $getSubImgs-> execute(array($pcat['ID']));
                            $subImgs = $getSubImgs-> fetchAll();
                            //print_r($subImgs);
                            if(!empty($subImgs)){
                             foreach ($subImgs as $subImg) { ?>
                                <div>
                                  <a href="subcategory.php?scatid=<?php echo $subImg['s_id']; ?>" class="p-0"><img src="uploads/<?php echo $subImg['img']; ?>" class="w-100"></a>
                                  <p class="text-center py-2"><?php echo $subImg['name']; ?></p>
                                </div>

                            <?php  } 
                            }
                        ?>
                         
                         </div>

                    </div>
                </div>
              </li>
             
            <?php } ?>
            </ul>
        </div>
      </div>
    </nav>

    <nav id="sidebar" class="">
      
    </nav>