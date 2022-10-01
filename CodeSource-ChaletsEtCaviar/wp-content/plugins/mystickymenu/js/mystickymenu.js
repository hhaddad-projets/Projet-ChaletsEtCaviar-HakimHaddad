/*!
 * myStickymenu by m.r.d.a
 * v2.0.4
 */

(function( $ ) {
	"use strict";

	$(document).ready(function($){
		
	// get Sticky Class setting if class name existts
	if ($(option.mystickyClass) [0]){
		// Do nothing
		}
	else {
		// Do something if class does not exist and stop
		console.log("myStickymenu: Entered Sticky Class does not exist, change it in Dashboard / Settings / myStickymenu / Sticky Class. ");
		return;
	}
		
			
   		// Get class name
		var mystickyClass = document.querySelector(option.mystickyClass);
		
		// get disable at small screen size setting 
		var disableWidth = parseInt(option.disableWidth);
		
		// get disable at large screen size setting 
		var disableLargeWidth = parseInt(option.disableLargeWidth);
		
		// get body width
		//var bodyWidth = parseInt(document.body.clientWidth);
		
		// get transition effect (slide or fade)
		var mystickyTransition = option.mystickyTransition;
		
		// get activaton height setting
		var activationHeight = parseInt(option.activationHeight);
		
		// if is admin bar showing, needed for auto calc of activation height when admin bar is showing
		var adminBar = option.adminBar;

		// disable on scroll down	
		var mysticky_disable_down = option.mysticky_disable_down;
		

	
		
		var viewportWidth;		
		
		
		function calcViewportWidth(e){
			
			// Calculate actual viewport width
    	var e = window, a = 'inner';
    		
			if (!('innerWidth' in window )) {
      			a = 'client';
      			e = document.documentElement || document.body;
   			}
    		viewportWidth = e[ a+'Width' ];
			
			
		}
		
		
		calcViewportWidth();
		
		
		
		
		
		
		
		
			var parentmysticky = mystickyClass.parentNode;
			
			var wrappermysticky = document.createElement('div');
			var position = 0;
			for(var i = 0; i < parentmysticky.childNodes.length; i++) {
            	if(parentmysticky.childNodes[i] == mystickyClass) {
               		position = i;
                	break;
            	}
        	}
			
			wrappermysticky.id = 'mysticky-wrap';
			wrappermysticky.appendChild(mystickyClass);
			parentmysticky.insertBefore(wrappermysticky, parentmysticky.childNodes[position]);

			var parentnav = mystickyClass.parentNode;
			var wrappernav = document.createElement('div');
			wrappernav.id = 'mysticky-nav';
			parentnav.replaceChild(wrappernav, mystickyClass);
			wrappernav.appendChild(mystickyClass);
			
		

			
			// get activation height from settings
			if ( activationHeight == "0" ) {
				var autoActivate = true;
			}
			
			
			
			
			
			var mydivHeight;
			
			
			function initialDivHeight(){
				
				
				
				// get initial element height of selected sticky class 
				mydivHeight = (mystickyClass.offsetHeight);
				
				
				
				// when initial element have margin bottom - awaken example using #masthead class
				
				if (parseInt($(mystickyClass).css("marginBottom")) > 0) {
					
					// element have margin bottom, apply it to initial wrap
					//$(mystickyClass).css("marginBottom").replace('px', '')
					wrappermysticky.style.marginBottom = ($(mystickyClass).css("marginBottom"));
				}
					
			
				if (mydivHeight == "0") {
					// something is wrong, wrapper cant be zero, if so content will jump while scroll. Awaken theme (for example) with .awaken-navigation-container class selected will use this part. Calculate height based on element children height

					$(mystickyClass).children().filter(':visible').each(function(){
					mydivHeight = $(this).outerHeight(true);
					
					});
				
				}
				
				if (viewportWidth >= disableWidth) {
					//wrappermysticky.style.height = mydivHeight + 'px';
				}
			}
				
			initialDivHeight();
			
			
			
			
			var myfixedHeight;
	
			function fixedDivHeight(){
			//if ( autoActivate == true ) {
				
				// calculate element height while fixed
				
				mystickyClass.classList.add('myfixed')
					
				myfixedHeight = $(".myfixed").outerHeight();
					
				if (myfixedHeight == "0") {
					// something is wrong, wrapper cant be zero, try to calculate again with div children.
					$(".myfixed").children().filter(':visible').each(function(){
						myfixedHeight = $(this).outerHeight(true); 
					});
				}
					
				mystickyClass.classList.remove('myfixed');
					
			//}
			
			}
				
			fixedDivHeight();
			
			
			
			
		var adminBarHeight = 0;	
			
		function calcAdminBarHeight(){
			
		
		
		
		if ((adminBar == "true" ) && (viewportWidth > 600)) {
			
      		if ($("#wpadminbar")[0]){
		
      			adminBarHeight = $('#wpadminbar').height();
		
			} else {
				
				adminBarHeight = 0;
				
			}
		
		
		
		} else {
			
			adminBarHeight = 0;
		}
		
		
		//wrappernav.style.top = adminBarHeight + "px";
		
		if (mystickyTransition == "on") {
		
		wrappernav.style.top = "-" + myfixedHeight + "px";
		//wrappernav.style.top = "-" + myfixedHeight  + "px";
		
		} else {
			wrappernav.style.top = adminBarHeight + "px";
			
			}
		
		}
		
		
		
		calcAdminBarHeight();
			
			
			
			
			
			
			var mydivWidth;
			
			
			function initialDivWidth(){ 
			
			var rect = $(mystickyClass)[0].getBoundingClientRect();
			mydivWidth = rect.width;
			
			//var mydivWidth = ((mystickyClass.offsetWidth) + 'px');
			//mystickyClass.style.width = mydivWidth + "px";
			
			
			}
			
			initialDivWidth();
			
			
		
		
			var deactivationHeight = activationHeight;
			
			function calcActivationHeight() { 
			
				// If activate height (Make visible on Scroll) is set to 0, automatic calculation will be used.
				if ( autoActivate == true ) {
				
					// Automatic calculation of activation and deactivation height (Make visible on Scroll is set to 0).
					if (mystickyTransition == "on") {
						// Slide effect is selected
						//activationHeight  = $(mystickyClass).offset().top + mystickyClass.offsetHeight - adminBarHeight; 
						activationHeight  = $(mystickyClass).offset().top + mydivHeight - adminBarHeight;
						deactivationHeight = $(mystickyClass).offset().top + mydivHeight - adminBarHeight;
						//deactivationHeight = $(mystickyClass).offset().top - adminBarHeight;
						
						if (mysticky_disable_down == "on") { 
							deactivationHeight = $(mystickyClass).offset().top - adminBarHeight;
						
						}
						
					} 
					
					if (mystickyTransition == "fade") {
						
						if (mysticky_disable_down == "false") { 
							// Fade effect is selected	
							activationHeight  = $(mystickyClass).offset().top - adminBarHeight;
							
							deactivationHeight = $(mystickyClass).offset().top - adminBarHeight;
						
						}
						
						if (mysticky_disable_down == "on") { 
						
						// Fade effect is selected	
						activationHeight  = $(mystickyClass).offset().top - adminBarHeight + mydivHeight; 
						deactivationHeight = $(mystickyClass).offset().top - adminBarHeight;
						
						}
	
					}
						
				}
			
			}

			calcActivationHeight();
			
	
			
			
			
			
			function headerDeactivateOnHeight() {
				
				
				if ( autoActivate == true ) {
					
						if ( mydivHeight > myfixedHeight ){
							// Auto activate is true, Make visible on Scroll is set to 0, menu is probably header
							
							
							
							if (mystickyTransition == "on") { 
								// slide effect is selected
								deactivationHeight = activationHeight; 
							
								if (mysticky_disable_down == "on") { 
									deactivationHeight = activationHeight - myfixedHeight;
						
								}
					
							
							} else { 
							activationHeight  = mydivHeight;
							deactivationHeight  = mydivHeight;
							
							}
			
						}
					
					}
				
			}
				
			headerDeactivateOnHeight();
			

			
			var hasScrollY = 'scrollY' in window;	
			var lastScrollTop = 0;
			

			function onScroll(e) {
				
								
				//initialDivHeight();
				
				// if body width is larger than disable at small screen size setting 
				
				
				
				
				if (viewportWidth >= disableWidth) {
					
					
					
				if ( disableLargeWidth == 0 || viewportWidth <= disableLargeWidth ) {
					
					
								
				//if (mysticky_disable_down == "on") { 
		
				var y = hasScrollY ? window.scrollY : document.documentElement.scrollTop;
				
				
				
				//var yScrollPosition = $(this).scrollTop();
				
	
				// add up or down class to the element depending on scroll direction  
				if (0 <= y ) {

					//var st = $(this).scrollTop();
					
   
					if (y >= lastScrollTop){
	   
						// downscroll code
						
						
				
						
						// add myfixed and wrapfixed class to selected fixed element while scroll down
						y >= activationHeight ? mystickyClass.classList.add('myfixed') : "";
						y >= activationHeight ? wrappernav.classList.add('wrapfixed') : "";
						
						y >= activationHeight ? wrappermysticky.style.height = mydivHeight + 'px' : "";
						y >= activationHeight ? mystickyClass.style.width = mydivWidth + "px" : "";
					
					
						if (mystickyTransition == "on") {
						
							if (mysticky_disable_down == "false") { 
								//y < activationHeight  + (myfixedHeight + 250) - adminBarHeight ? wrappernav.style.top = "-" + myfixedHeight  + "px" : '';
								//wrappernav.style.top = "-" + myfixedHeight  + "px"
								y >= activationHeight + myfixedHeight  - adminBarHeight ? wrappernav.style.top = adminBarHeight + "px" : wrappernav.style.top = "-" + myfixedHeight  + "px";
					
							} 
	
							if ( mydivHeight > myfixedHeight ){
								// if it's header (guess)
							
								if (mysticky_disable_down == "false") { 
							
									y < activationHeight + myfixedHeight ? wrappernav.style.top = "-" + mydivHeight  + "px" : '';
									y >= activationHeight + myfixedHeight ? wrappernav.style.top = adminBarHeight + "px" : '';
							
								}
							
							}
					
						}
		
						wrappernav.classList.add('down');
						wrappernav.classList.remove('up');
							
							
						if (mysticky_disable_down == "on") { 
       						wrappernav.style.top = "-" + (mydivHeight + adminBarHeight ) + "px";
						}
							
							
							
							
   					} else {
						// upscroll code
						
							
						var x = hasScrollY ? window.scrollY : document.documentElement.scrollTop;
							//x > deactivationHeight ? '' : mystickyClass.classList.remove('myfixed') ;
							//x > deactivationHeight ? '' : wrappernav.classList.remove('wrapfixed');
							
							x > deactivationHeight ? "" : wrappermysticky.style.height = "";
							x > deactivationHeight ? "" : mystickyClass.style.width = "";
		
						if (mystickyTransition == "on") {
						
							
						
								x > deactivationHeight ? '' : mystickyClass.classList.remove('myfixed') ;
								x > deactivationHeight ? '' : wrappernav.classList.remove('wrapfixed');
								
								if (mysticky_disable_down == "false") { 
								
								x < deactivationHeight  + myfixedHeight + 200 - adminBarHeight ? wrappernav.style.top = "-" + myfixedHeight  + "px" : '';
							
								} 
						
						} else {
						
							x > deactivationHeight ? "" : mystickyClass.classList.remove('myfixed') ;
							x > deactivationHeight ? "" : wrappernav.classList.remove('wrapfixed');
							
						
						}
								
		
						wrappernav.classList.remove('down');
						wrappernav.classList.add('up');
						
						if (mysticky_disable_down == "on") { 
							wrappernav.style.top = adminBarHeight + "px";
						}
	  
  					}

   					lastScrollTop = y;
   
				} else {
					//if (mysticky_disable_down == "on") { 
					wrappernav.classList.remove('up');
					//}
				}
				
				
			}	// if disableWidth is greater than zero
			
			
			}	// if disableLargeWidth is 0 or greater than zero
			
	
			
	
			}

			document.addEventListener('scroll', onScroll);
		
		
		
			
		var width = $(window).width()
			
		function OnResizeDocument () {
				

			// don't recalculate on height change, only width				
			if($(window).width() != width ){
  

				wrappernav.classList.remove('up');
				wrappernav.classList.remove('down');
	
				if ($(".wrapfixed")[0]){
    				// If class wrapfixed exists
					// Remove myfixed and wrapfixed clases so we can calculate 
					mystickyClass.classList.remove('myfixed');
					wrappernav.classList.remove('wrapfixed');
					
					
			
				} else {
    				// Else class wrapfixed does not exists
					initialDivHeight();
					
					// Remove width
					mystickyClass.style.removeProperty("width");
					initialDivWidth();	

				}
				calcViewportWidth();
				calcAdminBarHeight();
				fixedDivHeight();
				calcActivationHeight();
				headerDeactivateOnHeight();
				
			}	
			
		}
	
		window.addEventListener('resize', OnResizeDocument);
						
		// need to test this, it should fire script on mobile orientation change, since onresize is somehow faulty in this case
		window.addEventListener('orientationchange', OnResizeDocument);
	

	

	});


})(jQuery);