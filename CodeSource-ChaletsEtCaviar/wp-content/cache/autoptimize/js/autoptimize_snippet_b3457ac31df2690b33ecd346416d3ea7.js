
var bstone_menu_toggle=document.querySelector(".main-header-menu-toggle"),bstone_body=document.querySelector("body");bstone_menu_toggle.addEventListener("click",function(e){e.preventDefault(),bstone_menu_toggle.classList.toggle("toggled"),bstone_body.classList.toggle("menu-toggled")});var masonary_required=document.querySelector(".bst-masonry-posts"),masonary_container=document.querySelector(".bst-posts-cnt"),masonary_element=document.querySelector("article");if(null!==masonary_required&&null!==masonary_container&&null!==masonary_element)var elem=document.querySelector(".bst-masonry-posts .bst-posts-cnt"),msnry=new Masonry(elem,{itemSelector:"article",columnWidth:".masonry-grid-sizer",isAnimated:!0});var sctop_element=document.querySelector("#bstone-scroll-top");if(null!==sctop_element){function toggle_bstone_sctop(){document.body.scrollTop>100||document.documentElement.scrollTop>100?document.getElementById("bstone-scroll-top").className="sctop-enabled":document.getElementById("bstone-scroll-top").className=""}document.getElementById("bstone-scroll-top").addEventListener("click",function(e){e.preventDefault();var o=-window.scrollY/(500/15),t=setInterval(function(){0!=window.scrollY?window.scrollBy(0,o):clearInterval(t)},15)}),window.onscroll=function(){toggle_bstone_sctop()}}jQuery.noConflict(),function(e){e(function(){e(".owl-carousel").length&&e(".owl-carousel").owlCarousel({items:1,loop:!0,autoplay:!0,autoplayTimeout:3e3,autoplayHoverPause:!0})})}(jQuery);