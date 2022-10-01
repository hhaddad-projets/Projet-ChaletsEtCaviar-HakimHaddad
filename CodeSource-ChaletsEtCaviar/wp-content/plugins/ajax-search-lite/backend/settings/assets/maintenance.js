jQuery(function ($) {
    var $success = $("#asl_i_success");
    var $error = $("#asl_i_error");
    var $error_cont = $("#asl_i_error_cont");

    $('#asl_reset, #asl_wipe').on('click', function(e){
        e.preventDefault();
        asl_clear_msg();
        asl_disable_buttons();

        if ( $(this).attr('id') == 'asl_reset' )
            var r = confirm("Are you sure you want to reset Ajax Search Lite to it's default state?");
        else
            var r = confirm("Are you sure you want to completely remove Ajax Search Lite? (including  database content, files etc..)");
        if (r == true) {
            var data = {
                'action' : 'asl_maintenance_admin_ajax',
                'data' : $(this).closest('form').serialize()
            };
            $.post(ajaxurl, data)
                .done(asl_on_post_success)
                .fail(asl_on_post_failure);
            $('.loading-small', $(this).parent()).removeClass('hiddend');
        }
        return true;
    });

    function asl_on_post_success(response) {
        var res = response.replace(/^\s*[\r\n]/gm, "");
        res = res.match(/!!!ASL_MAINT_START!!!(.*[\s\S]*)!!!ASL_MAINT_STOP!!!/);
        if (res != null && (typeof res[1] != 'undefined')) {
            res = JSON.parse(res[1]);
            if (typeof res.status != "undefined" && res.status == 1 ) {
                if ( res.action == 'redirect' ) {
                    asl_show_success('<strong>SUCCESS: </strong>' + res.msg);
                    setTimeout(function () {
                        location.href = ASL_MNT.admin_url + '/plugins.php';
                    }, 5000);
                } else if ( res.action == 'refresh' ) {
                    asl_show_success('<strong>SUCCESS! </strong>Refreshing this page, please wait..');
                    $('form#asl_empty_redirect input[name=asl_mnt_msg]').val(res.msg);
                    $('form#asl_empty_redirect').submit();
                } else {
                    asl_show_success('<strong>SUCCESS: </strong>' + res.msg);
                }
            } else {
                if (typeof res.status != "undefined" && res.status == 0 ) {
                    asl_show_error('<strong>FAILURE: </strong>' + res.msg);
                } else {
                    asl_show_error('Something went wrong. Response returned: ', response);
                }
                asl_enable_buttons();
            }
        } else { // Failure?
            asl_show_error('Something went wrong. Here is the error message returned: ', response);
            asl_enable_buttons();
        }
    }
    function asl_on_post_failure(response, t) {
        if (t === "timeout") {
            asl_show_error('Timeout error. Please try again!');
        } else {
            asl_show_error('Something went wrong. Here is the error message returned: ', response);
        }
        asl_enable_buttons();
    }

    function asl_show_success(msg) {
        $success.removeClass('hiddend').html(msg);
    }

    function asl_show_error(msg, response) {
        $error.removeClass('hiddend').html(msg);
        if ( typeof response !== 'undefined') {
            console.log(response);
            if (
                typeof response.status != 'undefined' &&
                typeof response.statusText != 'undefined'
            ) {
                $error_cont.removeClass('hiddend').val("Status: " + response.status + "\nCode: " + response.statusText);
            } else {
                $error_cont.removeClass('hiddend').val(response);
            }
        }
    }

    function asl_disable_buttons() {
        $('#asl_reset, #asl_wipe').addClass('disabled');
    }

    function asl_enable_buttons() {
        $('.loading-small').addClass('hiddend');
        $('#asl_reset, #asl_wipe').removeClass('disabled');
    }

    function asl_clear_msg() {
        $error_cont.addClass('hiddend');
        $error.addClass('hiddend');
        $success.addClass('hiddend');
    }
});
