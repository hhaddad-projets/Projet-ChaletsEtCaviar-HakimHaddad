(function( $ ) {
  "use strict";
  
    

jQuery(document).ready(function($){
	
			
  $("#myfixed_zindex,#myfixed_opacity,#myfixed_transition_time,#disable_css").parent().parent().parent().hide();
  $("#myfixed_bgcolor,#mysticky_disable_at_front_home").parent().parent().parent().hide();
  $("#myfixed_bgcolor").parent().parent().parent().parent().parent().parent().hide();
  $("#myfixed_cssstyle").parent().parent().hide();
  $(".mysticky-hideformreset").hide();
  $(".mysticky-hideform,.mysticky-general").fadeIn(300);
  
  
  $(".btn-general").click(function(){
    $(".btn-general").addClass("nav-tab-active");
    $(".btn-style,.btn-advanced").removeClass("nav-tab-active");
    $("#mysticky_class_selector,#myfixed_disable_small_screen,#myfixed_disable_large_screen,#mysticky_active_on_height,#mysticky_active_on_height_home,#myfixed_fade").parent().parent().parent().show();
    $("#myfixed_zindex,#myfixed_opacity,#myfixed_transition_time,#disable_css,#mysticky_disable_at_front_home").parent().parent().parent().hide();
    $("#myfixed_bgcolor").parent().parent().parent().parent().parent().parent().hide();
    $("#myfixed_cssstyle,#mysticky_disable_at_front_home").parent().parent().hide();
    $(".mysticky-general").fadeIn(300);
    $(".mysticky-style,.mysticky-advanced,.mysticky-hideformreset") .hide();				
  });
						
  $(".btn-general,.btn-style,.btn-advanced").hover(function() {
    $(".btn-general,.btn-style,.btn-advanced").css("cursor","pointer");
  });
							
  $(".btn-style").click(function(){
    $(".btn-style").addClass("nav-tab-active");
    $(".btn-general,.btn-advanced").removeClass("nav-tab-active");
    $("#mysticky_class_selector,#myfixed_disable_small_screen,#myfixed_disable_large_screen,#mysticky_active_on_height,#mysticky_active_on_height_home,#myfixed_fade,#mysticky_disable_at_front_home").parent().parent().parent().hide();
    $("#myfixed_zindex,#myfixed_bgcolor,#myfixed_opacity,#myfixed_transition_time,#disable_css").parent().parent().parent().show();
    $("#myfixed_cssstyle").parent().parent().show();
    $("#mysticky_disable_at_front_home").parent().parent().hide();
    $("#myfixed_bgcolor").parent().parent().parent().parent().parent().parent().show();
    $(".mysticky-general").hide();
    $(".mysticky-hideformreset").hide();
    $(".mysticky-style") .fadeIn(300);
    $(".mysticky-advanced").hide();
  });
						
  $(".btn-advanced").click(function(){
    $(".btn-advanced").addClass("nav-tab-active");
    $(".btn-style,.btn-general").removeClass("nav-tab-active");		
    $("#mysticky_class_selector,#myfixed_disable_small_screen,#myfixed_disable_large_screen,#mysticky_active_on_height,#mysticky_active_on_height_home,#myfixed_fade").parent().parent().parent().hide();
    $("#myfixed_zindex,#myfixed_opacity,#myfixed_transition_time,#disable_css").parent().parent().parent().hide();
    $("#myfixed_cssstyle").parent().parent().hide();
    $("#myfixed_bgcolor").parent().parent().parent().parent().parent().parent().hide();
	$("#mysticky_disable_at_front_home").parent().parent().parent().show();
	$("#mysticky_disable_at_front_home").parent().parent().show();
    $(".mysticky-hideformreset").fadeIn(300);
    $(".mysticky-general").hide();
    $(".mysticky-style") .hide();
    $(".mysticky-advanced").fadeIn(300);
  });		
						
						
						
  $(".confirm").click(function() {
    return window.confirm("Reset to default settings?");
  });
	
});		
	
})(jQuery);	