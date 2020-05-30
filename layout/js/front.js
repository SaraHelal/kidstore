
 $(function () {
   
    'use strict';
    var favStatus;



      const params = new URLSearchParams(location.search);
      if(params.get('size') || params.get('price') || params.get('color') ){
        $('.clearFilters').show();
      }
      else{
        $('.clearFilters').hide();

      }
    
 

   
    //Begin Main Actions
   $('.login-icon').hover(function(){
        $('.popover-welcome').fadeIn('slow').delay(500);
      } , function(){
        $('.popover-welcome').fadeOut('slow');
    });
    $('[placeholder]').focus(function () {

      $(this).attr('data-text',$(this).attr('placeholder'));
      $(this).attr('placeholder', '');
    }).blur(function () {
      $(this).attr('placeholder', $(this).attr('data-text'));
    }); 


    //Confirm Delete 
    $('.confirm').click( function() {
        return confirm('Are You Sure You Want To Delete This Item ?');
    });

    /*$('.color-palette span').css({'color' : 'black !important'}); */

    $('input:text').val('');

    //Product Button Animations When Hover
    $(".btn-wrap").hover(function(){
      $(".btn-text", this).css("width",150);
    } , function(){
      $(".btn-text", this).css("width",0);
    }); 

    $('.holder').click(function(){

      $('.hiddenLeftMenu').fadeToggle();
    });


    //Cart Icon Header Popover When Hover
    $('.addCart').hover(function(){
      $('.cart-popover.hovered').fadeIn('slow').delay(500);
    } , function(){
      $('.cart-popover.hovered').fadeOut('slow');
    });


    //Display Main Menu In Mobile View
    $('#btnSidebar').on('click' , function(){
      sidebarAjax();
    });

    function sidebarAjax(id = 0){

      $.ajax({
        method: "POST",
        url:"sidebar-ajax.php", 
        data: {id : id } ,
        success: function(data , status , xhr){
          $('#sidebar').html(xhr.responseText);
          $('#sidebar').css('visibility' , 'visible');
        } ,
        complete: function(xhr, status){
        }
      })
    };
    
    $("#sidebar").delegate(".menu-items li", "click", function(){
      var id = $(this).data('id');
      if(id == ''){
        var s_id = $(this).data('sid');
        var url = "subcategory.php?scatid=" + s_id;
        $(location).attr('href',url);
      }
      else{
        sidebarAjax(id);
      }
    });
    
    $("#sidebar").delegate(".closeNav", "click", function(){
      $('#sidebar').css('visibility' , 'hidden');
    });

    
    $("#sidebar").delegate(".backarrow", "click", function(){
      var id = $(this).parent().siblings('ul').find('li').data('id');
      if(id == ''){
        id = $(this).parent().siblings('ul').find('li').data('maincat');
        sidebarAjax(id);
      }
      else{
        sidebarAjax();

      }
    });

    //*******************************************//
    //*******************************************//

    //Display Fixed mainnav When Scroll
    window.onscroll = function() {stickyScroll()};

    function stickyScroll(){

      if($(window).width() > 1024)
      {
       if(window.pageYOffset >= 188){
            $('.mainNav').addClass('mainNav-sticky');
            $('.hidden-nav').addClass('hidden-nav-sticky');
            $('.nav-right').addClass('nav-right-sticky');
          }
          else
          {
            $('.mainNav').removeClass('mainNav-sticky');
            $('.hidden-nav').removeClass('hidden-nav-sticky');
            $('.nav-right').removeClass('nav-right-sticky');
          }
   
        }
        else{
            $('.mainNav').removeClass('mainNav-sticky');
            $('.hidden-nav').removeClass('hidden-nav-sticky');
            $('.nav-right').removeClass('nav-right-sticky');

        }
    };



    //End Main Actions

    //*******************************************//


    //Begin Login Form 

    $("#loginForm :input").focusout(function(){
      var thisInput = $(this);
      var inputVal = $(this).val();
      var inputError = thisInput.parent().next();
      if ($(this).attr('type')== 'email'){
        var inputType = 'email';
      }
      else if($(this).attr('type') == 'password'){
        var inputType = 'password';

      }
      $.ajax({

        method: "POST",
            url:"loginajax.php", 
            data: { input: inputType , value: inputVal} ,
            success: function(data , status , xhr){
              if(data!== "validate"){
                inputError.html(data);
                inputError.css('margin','-10px 0 10px');
                thisInput.next().find('i').removeClass('fa-check');
              }
              else{
                inputError.html('');
                thisInput.next().find('i').addClass('fa-check');

              }
            }
      })

    });

    //End Login Form
    
    //*******************************************//

    //Begin Registeration Form

    $('#registerForm .submit').on('click' , function(event){
      var mobileCorrectIcon = $('.mobile').next().find('i');
      var emailCorrectIcon = $('#emailRegister').next().find('i');
      var userCorrectIcon = $('.username').next().find('i');
      var passCorrectIcon = $('.password').next().find('i');


      if (!mobileCorrectIcon.hasClass('fa-check') 
        || !emailCorrectIcon.hasClass('fa-check') 
        || !userCorrectIcon.hasClass('fa-check') 
        || !passCorrectIcon.hasClass('fa-check') ){
        event.preventDefault();
      }     
      
    });
    $("#registerForm :input").focusout(function(){
      var thisInput = $(this);
      var inputVal = $(this).val();
      var inputError = thisInput.parent().next();
      var inputType ='';
      if ($(this).attr('type')== 'email'){
        inputType = 'email';
      }
      else if($(this).attr('type') == 'password'){
        inputType = 'password';

      }
      else if($(this).attr('type') == 'number'){
        inputType = 'mobile';

      }
      else if($(this).attr('type') == 'text'){
        inputType = 'text';
      
      }
      if($(this).attr('type') !== 'checkbox'){
        registerAjax(inputType , inputVal , thisInput , inputError);
      }
    });

    
    function registerAjax(inputType , inputVal , thisInput , inputError){
      $.ajax({

        method: "POST",
            url:"registerajax.php", 
            data: { input: inputType , value: inputVal} ,
            success: function(data , status , xhr){
              if(data!== "validate"){
                inputError.html(data);
                inputError.css('margin','-10px 0 10px');
                thisInput.next().find('i').removeClass('fa-check');
              }
              else{
                inputError.html('');
                thisInput.next().find('i').addClass('fa-check');

              }
            }
      })

    };

    //End Registeration Form

    //*******************************************//


    //Begin Fav Page

    $('.favbtn').on('click' , function(){
      var $heart = $(this).find('i');
      var $heartStatus = favStatus == 'far' ? 1 : 0 ;

      var $product_id = ($(this).data('id'));
      var $color = ($(this).data('color'));

      var thisBtn = $(this);
       
      $.ajax({
        method: "POST",
        url:"fav-ajax.php", 
        data: {p_id : $product_id , color : $color ,
        heartStatus : $heartStatus} ,
        success: function(data , status , xhr){
          if ( $heartStatus == 1) 
          {
                $heart.removeClass('far');
                $heart.addClass('fas');
                favStatus = 'fas';
                
            }
            else {
                $heart.removeClass('fas');
                $heart.addClass('far');
                favStatus = 'far';
            }
        } ,
        complete: function(xhr, status){
          $('.fav-circle').html(xhr.responseText);
          if(thisBtn.hasClass('fav-page')){
            location.reload(true);
          }          
        }
      })
    });
    var favStatus;
    $('.favbtn').hover(function(){
      var $heart = $(this).find('i'); 
      
        
        if($heart.hasClass('fas')){
           favStatus = 'fas';
        }
        else if ($heart.hasClass('far')){
           favStatus = 'far';
          $heart.removeClass('far');
          $heart.addClass('fas');
        }     
      
    }, function(){
        var $heart = $(this).find('i'); 
        $heart.removeClass('fas');
        $heart.addClass(favStatus);

    });

    //End Fav Page
    
    //*******************************************//

    //Begin Cart Page

    
    $('.cartTable .delete').on('click' , function(){
      var product_id = ($(this).data('id'));
      var color = ($(this).data('color'));
      var size = ($(this).data('size'));
      var quantity = ($(this).data('quantity'));
      editQAjax(product_id , color , size , quantity);
    })
    
    $(document).on('click', '.QEdit', function () {
      var product_id = $(this).parent().data('id');
      var color = $(this).parent().data('color');
      var size = $(this).parent().data('size');
      var quantity = $(this).parent().data('quantity');

      var edit = $(this).data('edit');
      editQAjax(product_id , color , size , quantity , edit);   

    });

    //Function to Delete product from Cart Table OR To Edit Quantity 
    function editQAjax( p_id , color , size , quantity , edit = ''){
      $.ajax({
          method: "POST",
          url:"editCartAjax.php", 
          data: {p_id : p_id , color : color , size : size ,
           quantity : quantity , edit : edit} ,
          success: function(data , status , xhr){
          } ,
          complete: function(xhr, status){
              location.reload(true);
          }
      }) 
    };


    //End Cart Page
    
    //*******************************************//


    //Begin Product Page

    //Disable Modal Popover Image In Mobile
    $('#modalImage').on('show.bs.modal', function (e) {
        var button = e.relatedTarget;
        if($(button).hasClass('no-modal')) {
          //e.stopPropegation();
       }  
    });

    //Display Modal Popover Image when click Product Image
    $('.show').on('click' , function(){
      var src = $(this).find('img').attr('src');
      $('#modalImage .modal-body img').attr("src" , src);
      if($(window).width() < 768){
        $(this).addClass('no-modal');
      }      
    });

    //Add Class selected to Size buttons
    $('.product-desc .sizes a').on('click' , function(){
      $(this).addClass('selected').siblings().removeClass('selected');

    });

    //Add Product to Cart
    $(".addBtn").click(function(e){
        if($('.sizes a').hasClass('disable')){
          e.preventDefault();
          return false;
        }
        else if (!$('.sizes a').hasClass('selected')) {
            $('.product-desc .popover').fadeIn('slow').delay(1000).fadeOut('slow');
            e.preventDefault();
            return false;
        }
        else{
          var size = $('.sizes a.selected').text();
          var product_id = ($(this).data('id'));
          var color = ($(this).data('color'));


            $.ajax({
              method: "POST",
              url:"addcart-ajax.php", 
              data: {p_id : product_id , color : color , size : size } ,
              success: function(data , status , xhr){
                $('.cart-popover.added .cart').html(data);
                $('.cart-popover.added').fadeIn('slow').delay(2000).fadeOut('slow');
              } ,
              complete: function(xhr, status){

                  $(".addCart .icon").load(location.href + " .addCart .icon>*" , "");
                  $(".cartHover").load(location.href + " .cartHover>*" , "");

              }
            }) 
        }
    });

    //End Product Page
    
    //*******************************************//

    //Begin Subcategory Page
    $('.suggest-list').on('change' , function(){
      var listId = this.value;
      var sortType;
      if(listId == 0){
        sortType = 'Default';
      }
      else if(listId == 1){
        sortType = 'AscPrice';
      }
      else if(listId == 2){
        sortType = 'DescPrice';
      }
      else if(listId == 3){
        sortType = 'Newest';
      }

      //alert(listId);
      const params = new URLSearchParams(location.search);
      params.set('sort', sortType);
      //alert(params.toString());
      window.history.replaceState({}, '', `${location.pathname}?${params.toString().replace(/%2C/g, ',')}`);
      location.reload(true);
      
    });


  
    //Display Products in (2||3) col
    $('.nBars').on('click' , function(){
      var nBars;
      var grid = $('.gridsize');
      if($(this).hasClass('two-bars')){
        nBars = 2;
        if(grid.hasClass('col-md-4')){
          grid.removeClass('col-md-4');
        }
        grid.addClass('col-md-6');
      }
      else if($(this).hasClass('three-bars')){
        nBars = 3;
        if(grid.hasClass('col-md-6')){
          grid.removeClass('col-md-6');
        }
        grid.addClass('col-md-4');
      }
    })
  

    //Close Hidden Filter Menu In SubCategory Page In Mobile View

    const colorArr =[];
    const minPriceArr =[];
    const maxPriceArr =[];
    const sizeArr =[];
    const priceArr = [];
    const removeSizeArr = [];
    const removePriceArr = [];
    const removeColorArr = [];

    //Action click on filter checkboxes
    $(document).on('click', ".filter-checkbox", function () {
      const params = new URLSearchParams(location.search);
      
      var fInput = $(this);
      var checked = fInput.prop('checked');

      var group = fInput.data('group');
      var checkboxes = $('input[type="checkbox"][data-group="' + group + '"]');
        var otherCheckboxes = checkboxes.not(fInput);
        otherCheckboxes.prop('checked', checked);
      var cat = $('.mainClass').data('cat');
      var scatid = $('.mainClass').data('scatid');
      var sname = $('.mainClass').data('sname');


      if (typeof $(this).data('price') !== 'undefined'){
        if($(this).data('edit') == 'change'){
          
          addQueryString('price' ,  $(this).data('price') , $(this).prev().prop("checked") , params);
          location.reload(true);
        }
        else if($(this).data('edit') !== 'change'){
          
          saveQueryString('price' ,  $(this).data('price') , $(this).prev().prop("checked") , params);

        }

        
      }
      
      else if (typeof $(this).data('color') !== 'undefined'){
        if($(this).data('edit') == 'change'){

          addQueryString('color' ,  $(this).data('color') , $(this).prev().prop("checked") , params);
          location.reload(true);


        }
        else if($(this).data('edit') !== 'change'){
          
          saveQueryString('color' ,  $(this).data('color') , $(this).prev().prop("checked") , params);
          

        }
        
      }

      else if (typeof $(this).data('size') !== 'undefined'){
        if($(this).data('edit') == 'change'){

          addQueryString('size' ,  $(this).data('size') , $(this).prev().prop("checked") ,params);
          location.reload(true);

        }
        else if($(this).data('edit') !== 'change'){

          saveQueryString('size' ,  $(this).data('size') , $(this).prev().prop("checked") ,params);

        }
        
      }

  
      
    })
    //Filters in Large screens
    function addQueryString( filterType , filterValue  , checkStatus , params ){
      if(!checkStatus) {
        if(!params.get(filterType)){
          params.append(filterType, filterValue);
        }
        else{
          var oldFilter = params.get(filterType);
          var oldSize = oldFilter.replace('%2C', ',');
          var filterArr = oldFilter.split(',');
          var index = filterArr.indexOf(filterValue.toString());
          if (index == -1) {
            
            filterArr.push(filterValue.toString());
            params.set(filterType, filterArr.toString());
            
          }

        }
        window.history.replaceState({}, '', `${location.pathname}?${params.toString().replace(/%2C/g, ',')}`);
      }     

      else{

        if(params.get(filterType)){
          var oldFilter = params.get(filterType);
          var oldFilter = oldFilter.replace('%2C', ',');
          var filterArr = oldFilter.split(',');
          var index = filterArr.indexOf(filterValue.toString());
          filterArr.splice(index, 1);
          params.set(filterType, filterArr.toString());

          if(filterArr.length !== 0){
            window.history.replaceState({}, '', `${location.pathname}?${params.toString().replace(/%2C/g, ',')}`);

          }
          else{
            params.delete(filterType);                
          window.history.replaceState({}, '', `${location.pathname}?${params.toString().replace(/%2C/g, ',')}`);

          }

        }
      }
    }
    //filters in small screen
    function saveQueryString( filterType , filterValue  , checkStatus , params ){
      //alert(params.get(filterType).split(','));
      if(params.get(filterType)){
        var filterParam = params.get(filterType).split(',');
       // alert(filterParam);
        var index = filterParam.indexOf(filterValue.toString());
      }
      else{
        index = -1;
      }
      if(filterType == 'size') {
        if(!checkStatus){
        //  alert('checking && adding');
          checkInRemoveArr(removeSizeArr , filterValue);
         // alert(removeSizeArr);
          sizeArr.push(filterValue);
         // console.log(sizeArr);
        }
        else{
         // alert('removing');

          removeCheck(filterValue , sizeArr , removeSizeArr , index);
        }
      }
      else if(filterType == 'price'){
        if(!checkStatus){
          //alert('checking && adding');
          checkInRemoveArr(removePriceArr , filterValue);
         // alert(removePriceArr);
          priceArr.push(filterValue);
         // console.log(priceArr);

        }
        else{
          //alert('removing');
          removeCheck(filterValue , priceArr, removePriceArr , index);
        }


      }
      else if(filterType == 'color'){
        if(!checkStatus){
          //alert('checking && adding');
          checkInRemoveArr(removeColorArr , filterValue);
          //alert(removeColorArr);
          colorArr.push(filterValue);
          //console.log(colorArr);

        }
        else{
         // alert('removing');

          removeCheck(filterValue , colorArr , removeColorArr , index);
        }
      }     

    }
    
    //remove checkboxs clicked before Ar Add it to removeArr if it is in quaryString
    function removeCheck(value , arr , removeFilterArr , index){
      if(index !== -1){
        //alert('found && aading to remove arr');
        removeFilterArr.push(value.toString());
      }
      else if (index == -1) {
       // alert('not found && simple removing')
        for( var i = 0; i < arr.length; i++){ 
          if ( arr[i] === value) { 
            arr.splice(i, 1); i--;
          }
        }
      }
    }


    //clear filter anchor
    $('.clearFilters').click(function(){
      var scatid = $('.mainClass').data('scatid');
      var combine = 'scatid=' + scatid;
      window.history.replaceState({}, '', `${location.pathname}?${combine}`);
      location.reload(true);

    });
    //click on button in hidden filter
    $(document).on('click', ".responsiveBtnFilter", function () {
      var scatid = $('.mainClass').data('scatid');
      const params = new URLSearchParams(location.search);

      /*alert('Urls is ' + params.get('size') + ' // '
       + params.get('price') + ' // ' + params.get('color'));
      //alert('remove filters is : ' + removeSizeArr + ' // '
       + removePriceArr + ' // ' + removeColorArr);
      //alert('filter arrays is : ' + sizeArr + ' // '
       + priceArr + ' // ' + colorArr);*/
      var sizesUrl = params.get('size');
      addFilterArray(sizesUrl , sizeArr , removeSizeArr);
      var pricesUrl = params.get('price');
      addFilterArray(pricesUrl , priceArr , removePriceArr);
      var colorsUrl = params.get('color');
      addFilterArray(colorsUrl , colorArr , removeColorArr);
    
      var orderUrl = params.get('sort');
      if(orderUrl !== ''){
        var combine = 'scatid=' + scatid + '&sort=' + orderUrl;
      }
      else{
        var combine = 'scatid=' + scatid;
      }
      
      
      if(sizeArr.length !== 0){
        
        combine = combine + '&size=' + sizeArr.toString();
        
      }
      if(priceArr.length !== 0){
        combine = combine + '&price=' + priceArr.toString();
        
      }
      if(colorArr.length !== 0){
        
        combine = combine + '&color=' + colorArr.toString();
        
      }
      if(combine !== ''){
        window.history.replaceState({}, '', `${location.pathname}?${combine}`);
        location.reload(true);
      }


    });

    //Function to push quary string to filter arrays and remove from them if they were been clicked from hidden checkboxes

    function addFilterArray(filterUrl , filterArr ,removeArr){
     /* alert(' string is : ' + filterUrl + ' And filter Arr is : ' + filterArr
       + ' And removeArr is : ' +removeArr);*/
      if(filterUrl ){
        var filter = filterUrl.split(',');
        for (var i = 0; i < filter.length; i++) {
          filterArr.push(filter[i]);
        }

        if(removeArr.length !== 0){
          for (var i = 0; i < removeArr.length; i++) {
            var removeIndex =filterArr.indexOf(removeArr[i].toString()); 
            if(removeIndex !== -1){
              filterArr.splice(removeIndex , 1);
            }
          }
        }
      }

    };
    
    function checkInRemoveArr(removeArr , value){
      var count = 0;
      if(removeArr.length !== 0){
          for (var i = 0; i < removeArr.length; i++) {
            if(removeArr[i] == value){
              removeArr.splice(i , 1);
              
              break;
            }
          }
        }
        return removeArr;
    };

    // To display hidden  popover fiter menu
    $('.filter-btn-choose').on('click' , function(){
      var sid = $(this).data('sid');
        $('.hidden-filter').css("visibility", "visible");
    });

    //toggle in hidden filter menu items
    $(document).on('click' , '.hidden-filter .feature-box' , function(){
      $(this).next().toggle();
      var nextFeature = $(this).next().next();
      if($(this).next().is(':visible')){
        if(!$(this).is(':nth-last-child(2)')){
          nextFeature.css('border-top' , 0);
        }
        
      }
      else{
        if(!$(this).is(':nth-last-child(2)')){
          nextFeature.css('border-top' , '1px solid #CCC');
        }
          
      }
    });
    
  

    $(".hidden-filter").delegate(".closeNav", "click", function(){
      $('.hidden-filter').css('visibility' , 'hidden');
    });
    
    });

    //End Subcategory Page

    //*******************************************//

      



