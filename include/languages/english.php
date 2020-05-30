<?php 


function lang($phrase){
    
    static $lang = array(
        
        'MESSAGE'          => 'Welcome' ,
        'HOME_ADMIN'  => 'HOME' , 
        'CATEGORIES'    => 'Categories',
        'ITEMS'                 => 'Items',
        'MEMBERS'         => 'Members',
        'STATISTICS' 	  => 'Statistics',
		'LOGS' 			      => 'Logs',
        'COMMENTS'     => 'Comments',


    );
    
    return $lang[$phrase];
}