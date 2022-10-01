/**
 * Bstone Light JS
 **/

jQuery.noConflict();

jQuery( document ).ready(function( $ ) {
    
    if ( $( "h1.ocdi__title.dashicons-before.dashicons-upload" ).length ) {
        $( "h1.ocdi__title.dashicons-before.dashicons-upload" ).html('Bstone Demo Import');
        $( "h1.ocdi__title.dashicons-before.dashicons-upload" ).removeClass("dashicons-upload");
    }

    // Get Json Data
    fetch('https://wpbstone.com/wp-json/wp/v2/').then(response => {
        return response.json();
    }).then(data => {
        
    }).catch(err => {
        
    });

});