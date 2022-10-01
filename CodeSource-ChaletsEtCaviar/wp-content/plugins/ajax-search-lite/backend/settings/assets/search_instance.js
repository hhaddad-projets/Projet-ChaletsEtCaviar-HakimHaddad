jQuery(function ($) {
    $('.tabs a[tabid=1]').click(function () {
        $('.tabs a[tabid=101]').click();
    });

    $('.tabs a[tabid=4]').click(function () {
        $('.tabs a[tabid=401]').click();
    });

    $('.tabs a').on('click', function () {
        $('#sett_tabid').val($(this).attr('tabid'));
        location.hash = $(this).attr('tabid');
    });

    // Remove the # from the hash, as different browsers may or may not include it
    var hash = location.hash.replace('#', '');

    if (hash != '') {
        hash = parseInt(hash);
        $('.tabs a[tabid=' + Math.floor(hash / 100) + ']').click();
        $('.tabs a[tabid=' + hash + ']').click();
    } else {
        $('.tabs a[tabid=1]').click();
    }

    $('input[name="search_all_cf"]').change(function () {
        if ($(this).val() == 1)
            $('input[name="customfields"]').parent().addClass('disabled');
        else
            $('input[name="customfields"]').parent().removeClass('disabled');
    });
    $('input[name="search_all_cf"]').change();

    function check_redirect_url() {
        var click = $('select[name="redirect_click_to"]').val();
        var enter = $('select[name="redirect_enter_to"]').val();
        if (
            ( click == 'custom_url' ) ||
            ( enter == 'custom_url' )
        ) {
            $('input[name="custom_redirect_url"]').parent().removeClass('disabled');
        } else {
            $('input[name="custom_redirect_url"]').parent().addClass('disabled');
        }

        if ( click == 'ajax_search' || click == 'nothing' ) {
            $('select[name=click_action_location]').parent().addClass('hiddend');
        } else {
            $('select[name=click_action_location]').parent().removeClass('hiddend');
        }
        if ( enter == 'ajax_search' || enter == 'nothing' ) {
            $('select[name=return_action_location]').parent().addClass('hiddend');
        } else {
            $('select[name=return_action_location]').parent().removeClass('hiddend');
        }
    }

    $('select[name="redirect_click_to"]').change(check_redirect_url);
    $('select[name="redirect_enter_to"]').change(check_redirect_url);
    check_redirect_url();

    $('input[name="exactonly"]').change(function(){
        if ( $(this).val() == 0 ) {
            $('select[name="exact_match_location"]').parent().addClass('disabled');
            $('select[name="keyword_logic"]').closest('div').removeClass('disabled');
        } else {
            $('select[name="exact_match_location"]').parent().removeClass('disabled');
            $('select[name="keyword_logic"]').closest('div').addClass('disabled');
        }
    });
    $('input[name="exactonly"]').change();

    // Keyword logic information message
    $('select[name=keyword_logic]').on('change', function(){
        if ( $(this).val() == 'orex' || $(this).val() == 'andex' ) {
            $(this).closest('.item').find('.kwLogicInfo').removeClass('hiddend');
        } else {
            $(this).closest('.item').find('.kwLogicInfo').addClass('hiddend');
        }
    });
    $('select[name=keyword_logic]').trigger('change');

    // Primary and Secondary fields for custom fields
    $.each(['titlefield', 'descriptionfield'],
        function(i, v){
            $("select[name='"+v+"']").change(function(){
                if ( $(this).val() != 'c__f' ) {
                    $("input[name='"+v+"_cf']").parent().css("display", "none");
                } else {
                    $("input[name='"+v+"_cf']").parent().css("display", "");
                }
            });
            $("select[name='"+v+"']").change();
        });

    // Theme options
    $('select[name=theme]').on('change', function(){
        $('.asl_theme').removeClass().addClass('asl_theme asl_theme-' + $(this).val());
    });
    $('select[name=theme]').trigger('change');

    $('input[name=override_bg]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_bg_color]').parent().addClass('disabled');
        } else {
            $('input[name=override_bg_color]').parent().removeClass('disabled');
        }
    });
    $('input[name=override_bg]').trigger('change');

    $('input[name=override_icon]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_icon_bg_color]').parent().addClass('disabled');
            $('input[name=override_icon_color]').parent().addClass('disabled');
        } else {
            $('input[name=override_icon_bg_color]').parent().removeClass('disabled');
            $('input[name=override_icon_color]').parent().removeClass('disabled');
        }
    });
    $('input[name=override_icon]').trigger('change');

    $('input[name=override_border]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_border_style]').closest('.wpdreamsBorder').addClass('disabled');
        } else {
            $('input[name=override_border_style]').closest('.wpdreamsBorder').removeClass('disabled');
        }
    });
    $('input[name=override_border]').trigger('change');


    // -------------------------------- MODAL MESSAGES ----------------------------------
    var modalItems = [
        {
            'args': {
                'type'   : 'info', // warning, info
                'header' : 'GDPR & Cookie Notice',
                'headerIcons': true,
                'content': 'When using this option in <strong>POST</strong> mode, cookies might be set during the search redirection to store the search filter status and the phrase for pagination.' +
                ' These cookies are <strong>functional</strong> only, they are not used for marketing nor any other purposes.' +
                '<br><br>The cookie names are: <i>asl_data, asl_id, asl_phrase</i>',
                'buttons': {
                    'okay': {
                        'text': 'Okay',
                        'type': 'okay',
                        'click': function(e, button){}
                    }
                }
            }, // Modal args
            'items': [
                ['override_method', 'post']
            ]
        }
    ];
    function modal_check(items) {
        var ret = false;
        // If at least one of the values does not match, it is a pass, return true
        $.each(items, function(k, item){
            if ( typeof item[2] != 'undefined' ) {
                if ( $('*[name='+item[0]+']').val() == item[1] ) {
                    ret = true;
                    return false;
                }
            } else if ( $('*[name='+item[0]+']').val() != item[1] ) {
                ret = true;
                return false;
            }

        });
        return ret;
    }
    $.each(modalItems, function(k, item){
       $.each(item.items, function(kk, _item){
           $('*[name='+_item[0]+']').data('oldval', $('*[name='+_item[0]+']').val());
           $('*[name='+_item[0]+']').on('change', function() {
                var _this = this;
                if ( !modal_check(item.items) ) {
                    if ( typeof item.args.buttons != 'undefined' ) {
                        if ( typeof item.args.buttons.cancel != 'undefined' )
                            item.args.buttons.cancel.click = function ( e, button ) {
                                if ( $(_this).data('oldval') !== undefined ) {
                                    $(_this).val($(_this).data('oldval'));
                                    $('.triggerer', $(_this).closest('div')).trigger('click');
                                }
                                $(_this).data('oldval', $(_this).val());
                            };
                        if ( typeof item.args.buttons.okay != 'undefined' )
                            item.args.buttons.okay.click = function ( e, button ) {
                                $(_this).data('oldval', $(_this).val());
                            };
                    }
                    WPD_Modal.show(item.args);
                } else {
                    $(_this).data('oldval', $(_this).val());
                }
           });
       });
    });

    // Why pro? Modal message
    $('a.whypro').on('click', function(e){
        e.preventDefault();
        var args = {
            'type'   : 'info', // warning, info
            'header' : 'Why get the Pro version?',
            'headerIcons': true,
            'content': $('#whypro_content').get(0).outerHTML,
            'buttons': {
                'okay': {
                    'text': 'Close',
                    'type': 'okay',
                    'click': function(e, button){}
                }
            }
        };
        WPD_Modal.layout({
            'width': '720px',
            'max-width': '720px'
        });
        WPD_Modal.show(args);
    });

    // -------------------------------- MODAL MESSAGES END ------------------------------
});

