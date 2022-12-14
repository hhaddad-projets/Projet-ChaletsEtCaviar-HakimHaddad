jQuery(function() {

    // Search Widget - Full Screen Search Form

    if ( jQuery( '.bst-search-fullscreen' ).length ) {

        jQuery('.bst-search-fullscreen .bst-search-icon .bstone-search-icon').on("click", function(event) {
            event.preventDefault();
            jQuery(".bst-fullscreen-search-cnt").addClass("open");
            jQuery('.bst-fullscreen-search-cnt form input[type="search"]').focus();
        });
    
        jQuery( ".bst-fullscreen-search-cnt, .bst-fullscreen-search-cnt .bst-closebtn" ).on( "click keyup", function(event) {
            if (
            event.target == this ||
            event.target.className == "close" ||
            event.keyCode == 27
            ) {
                jQuery( this ).removeClass( "open" );
            }
        });

        jQuery( ".bst-fullscreen-search-cnt .bst-closebtn" ).on( "click", function(event) {
            jQuery( ".bst-fullscreen-search-cnt" ).removeClass( "open" );
        });

        jQuery('.bst-search-default .bst-search-icon .bstone-search-icon').on("click", function(event) {
            event.preventDefault();
            var widget_id = jQuery( this ).attr( "data-widgetid" );
            jQuery( '#'+widget_id+' form' ).submit();
        });
    }

    // Search Widget - Animated Search Form
    if ( jQuery( '.bst-search-animated' ).length ) {

        jQuery('.bst-search-animated .bst-search-icon .bstone-search-icon').on("click", function(event) {
            event.preventDefault();

            if( jQuery(".bst-search-widget.bst-search-animated form").hasClass("open") ) {

                var widget_id = jQuery( this ).attr( "data-widgetid" );
                jQuery( '#'+widget_id+' form' ).submit();

            } else {
                jQuery(".bst-search-widget.bst-search-animated form").addClass("open");
                jQuery('.bst-search-widget.bst-search-animated form input[type="search"]').focus();
            }
        });

        jQuery( "body" ).on( "keyup", function(event) {
            if (
                event.keyCode == 27 &&
                jQuery('.bst-search-widget.bst-search-animated form input[type="search"]').is(':focus')
            ) {
                jQuery( ".bst-search-widget.bst-search-animated form" ).removeClass( "open" );
            }
        });

        jQuery( "body" ).on( "click", function(event) {
            if (
            event.target.className != "slide-search bstone-search-icon" &&
            event.target.className != "search-field"
            ) {
                jQuery( ".bst-search-widget.bst-search-animated form" ).removeClass( "open" );
            }
        });
    }
  });