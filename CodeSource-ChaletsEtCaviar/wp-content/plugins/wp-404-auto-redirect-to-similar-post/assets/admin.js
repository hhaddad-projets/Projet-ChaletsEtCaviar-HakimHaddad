jQuery(document).ready(function($){
    
    $('.nav-tab-wrapper a').click(function(event){
		event.preventDefault();

		var context = $(this).closest('.nav-tab-wrapper').parent();
		$('.nav-tab-wrapper a', context).removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.nav-tab-panel', context).hide();
		$($(this).attr('href'), context).show();
	});
	 

	$('.nav-tab-wrapper').each(function(){
		if ( $('.nav-tab-active', this).length )
			$('.nav-tab-active', this).click();
		else
			$('a', this).first().click();
	});
    
    if($('[name="wp404arsp_settings[fallback][type]"]').length > 0 && $('[name="wp404arsp_settings[fallback][url]"]').length > 0 && $('[name="wp404arsp_settings[fallback][home_url]"]').length > 0){
        $('[name="wp404arsp_settings[fallback][type]"]').change(function(){
            
            $('[name="wp404arsp_settings[fallback][url]"]').val($('[name="wp404arsp_settings[fallback][home_url]"]').val());
            $('[name="wp404arsp_settings[fallback][url]"]').prop('readOnly', true);
            $('[name="wp404arsp_settings[fallback][url]"]').removeClass('hidden');
            $('[name="wp404arsp_settings[fallback][url]"]').addClass('disabled');
            
            if($(this).val() == 'custom'){
                $('[name="wp404arsp_settings[fallback][url]"]').prop('readOnly', false);
                $('[name="wp404arsp_settings[fallback][url]"]').removeClass('disabled');
                
            }else if($(this).val() == 'disabled'){
                $('[name="wp404arsp_settings[fallback][url]"]').addClass('hidden');
                
            }
        });
    }
    
    function wp404arsp_disable_taxonomies(checkbox){
        if(checkbox.is(':checked')){
            $('.wp404arsp_settings_taxonomies').hide();
        }else{
            $('.wp404arsp_settings_taxonomies').show();
        }
    }
    
    if($('#wp404arsp_settings_rules_redirection_disable_taxonomies').length > 0){
        wp404arsp_disable_taxonomies($('#wp404arsp_settings_rules_redirection_disable_taxonomies'));
        
        $('#wp404arsp_settings_rules_redirection_disable_taxonomies').change(function(){
            wp404arsp_disable_taxonomies($(this));
        });
    }
    
    if($('#wp404arsp_settings_redirection_preview').length > 0){
        $('#wp404arsp_settings_redirection_preview .button').click(function(event){
            event.preventDefault();
            
            $button = $(this);
            $loading = $('#wp404arsp_settings_redirection_preview .loading');
            $button.prop('disabled', true);
            $loading.addClass('is-active');
            $request = $('#wp404arsp_settings_redirection_preview input[type=text]').val();
            if($request.length == 0)
                return;
            
            if($request.substring(0,1) != '/'){
                $request = '/' + $request;
                $('#wp404arsp_settings_redirection_preview input[type=text]').val($request)
            }
            
            $.post(ajaxurl, {
                action: 'wp404arsp_ajax_preview',
                request: $request,
            })
            .done(function(response){
                $button.prop('disabled', false);
                $loading.removeClass('is-active');
                $('#wp404arsp_settings_redirection_preview .results').html(response);
            });
        });
    }
    
});