$(document).ready(function () {
    $( ".hamburger-toggle" ).on( "click", function(event) {
         $("body").toggleClass("menu-open");
    });
    
    $( "li.mobile-has-submenu > a" ).on( "click", function(event) {
        if($(this).parent('li').hasClass("mobile-has-submenu")){
           $(this).parent('li').toggleClass('open')
           }
    });
    
    
    $('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})
    
    /*Car listing*/
    $(document).on("click",".car-listing ul li", function(event){
          event.preventDefault();
          $(this).toggleClass("active");
    });
    
    /*Prepare Listing*/
    $(document).on("click",".prepare-listing li", function(event){
          event.preventDefault();
          $(this).toggleClass("active");
    });
    
    /*airport option*/
    $(document).on("click",".airport-opt li", function(event){
          event.preventDefault();
          $(this).siblings("li").removeClass("active");
          $(this).addClass("active");
    });
    
});
    
    

jQuery(window).load(function() {
    site_header_height();
    
});

jQuery(window).resize(function(){
    site_header_height();
    
});

function site_header_height(){
	var headerH = jQuery('.site-header').outerHeight();
	if (jQuery(window).width() < 960) {
		var new_height = headerH ;
		jQuery('.page-container').css('marginTop',new_height);
	}else{
		jQuery('.page-container').css('marginTop',0);
	}	
}