//Retrieve title / meta-desc from source code
jQuery(document).ready(function(){
const { subscribe, select } = wp.data;
let hasSaved = false;

    subscribe( () => {

        const editor  = select( 'core/editor' );
        const isSaved = editor && editor.didPostSaveRequestSucceed();

        if ( ! hasSaved && isSaved ) {
            
            jQuery.ajax({
                method : 'GET',
                url : seopressAjaxRealPreview.seopress_real_preview,
                data: {
                    action: 'seopress_do_real_preview',
                    post_id: jQuery('#seopress-tabs').attr('data_id'),
                    tax_name: jQuery('#seopress-tabs').attr('data_tax'),
                    origin: jQuery('#seopress-tabs').attr('data_origin'),
                    _ajax_nonce: seopressAjaxRealPreview.seopress_nonce,
                },
                success : function( data ) {
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-title-custom' ).html(data.data.title);
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-title' ).html(data.data.title);
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-title-default' ).html(data.data.title);
                    jQuery( '#seopress_titles_title_meta' ).attr("placeholder", data.data.title);
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-description-custom' ).html(data.data.meta_desc);
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-description' ).html(data.data.meta_desc);
                    jQuery( '#seopress_cpt .google-snippet-preview .snippet-description-default' ).html(data.data.meta_desc);
                    jQuery( '#seopress_titles_desc_meta' ).attr("placeholder", data.data.meta_desc);
                    jQuery( '#seopress_cpt #seopress_social_fb_title_meta' ).attr("placeholder", data.data.og_title);
                    jQuery( '#seopress_cpt #seopress_social_fb_desc_meta' ).attr("placeholder", data.data.og_desc);
                    jQuery( '#seopress_cpt #seopress_social_fb_img_meta' ).attr("placeholder", data.data.og_img);
                    jQuery( '#seopress_cpt #seopress_social_twitter_title_meta' ).attr("placeholder", data.data.tw_title);
                    jQuery( '#seopress_cpt #seopress_social_twitter_desc_meta' ).attr("placeholder", data.data.tw_desc);
                    jQuery( '#seopress_cpt #seopress_social_twitter_img_meta' ).attr("placeholder", data.data.tw_img);
                },
            });
        }
        hasSaved = !! isSaved;
    });
});