<?php
$params = array();
$messages = "";

$inst = wd_asl()->instances->get(0);
$sd = &$inst['data'];
$_def = get_option('asl_defaults');
$_dk = 'asl_defaults';
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=470596109688127&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asl-accessibility']) ? ' wd-accessible' : ''; ?>'>
    <?php if (ASL_DEBUG == 1): ?>
        <p class='infoMsg'>Debug mode is on!</p>
    <?php endif; ?>

    <?php if (wd_asl()->o['asl_performance']['use_custom_ajax_handler'] == 1): ?>
        <p class='noticeMsgBox'>AJAX SEARCH LITE NOTICE: The custom ajax handler is enabled. In case you experience issues, please
            <a href='<?php echo get_admin_url() . "admin.php?page=ajax-search-lite/backend/performance_options.php"; ?>'>turn it off.</a></p>
    <?php endif; ?>

    <div class="wpdreams-box" style='vertical-align: middle;'>
        <a class='gopro' href='https://wp-dreams.com/go/?to=asp_demo_gopro' target='_blank'>Get the pro version!</a>
        <a class="whypro" href="#">Why Pro?</a>
        &nbsp;|&nbsp;Maybe leave a like :)
        <div style='display: inline-block;' class="fb-like" data-href="https://www.facebook.com/pages/WPDreams/383702515034741" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
        or you can follow me
        <a href="https://twitter.com/ernest_marcinko" class="twitter-follow-button" data-show-count="false">Follow @ernest_marcinko</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <div class="hiddend">
            <div id="whypro_content">
                <?php include(ASL_PATH . "backend/whypro.php"); ?>
            </div>
        </div>
    </div>


    <?php ob_start(); ?>
    <div class="wpdreams-box">

            <label class="shortcode"><?php _e("Search shortcode:", "ajax-search-lite"); ?></label>
            <input type="text" class="shortcode" value="[wpdreams_ajaxsearchlite]"
                   readonly="readonly"/>
            <label class="shortcode"><?php _e("Search shortcode for templates:", "ajax-search-lite"); ?></label>
            <input type="text" class="shortcode"
                   value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?&gt;"
                   readonly="readonly"/>
    </div>
    <div class="wpdreams-box" style="float:left;">

        {--messages--}

        <form action='' method='POST' name='asl_data'>
            <ul id="tabs" class='tabs'>
                <li><a tabid="1" class='current general'><?php _e("General Options", "ajax-search-lite"); ?></a></li>
                <li><a tabid="2" class='multisite'><?php _e("Image Options", "ajax-search-lite"); ?></a></li>
                <li><a tabid="3" class='frontend'><?php _e("Frontend Options", "ajax-search-lite"); ?></a></li>
                <li><a tabid="4" class='layout'><?php _e("Layout options", "ajax-search-lite"); ?></a></li>
                <li><a tabid="7" class='advanced'><?php _e("Advanced", "ajax-search-lite"); ?></a></li>
            </ul>
            <div id="content" class='tabscontent'>
                <div tabid="1">
                    <fieldset>
                        <legend><?php _e("Genearal Options", "ajax-search-lite"); ?></legend>

                        <?php include(ASL_PATH . "backend/tabs/instance/general_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="2">
                    <fieldset>
                        <legend><?php _e("Image Options", "ajax-search-lite"); ?></legend>

                        <?php include(ASL_PATH . "backend/tabs/instance/image_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="3">
                    <fieldset>
                        <legend><?php _e("Frontend Search Settings", "ajax-search-lite"); ?></legend>

                        <?php include(ASL_PATH . "backend/tabs/instance/frontend_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="4">
                    <fieldset>
                        <legend><?php _e("Layout Options", "ajax-search-lite"); ?></legend>

                        <?php include(ASL_PATH . "backend/tabs/instance/layout_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="7">
                    <fieldset>
                        <legend><?php _e("Advanced Options", "ajax-search-lite"); ?></legend>

                        <?php include(ASL_PATH . "backend/tabs/instance/advanced_options.php"); ?>

                    </fieldset>
                </div>
            </div>
            <input type="hidden" name="sett_tabid" id="sett_tabid" value="1" />
        </form>
    </div>
    <div id="asl-side-container">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asl-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
    <?php $output = ob_get_clean(); ?>
    <?php
    if (isset($_POST['submit_asl'])) {

        $params = wpdreams_parse_params($_POST);
        $_asl_options = array_merge($sd, $params);

        wd_asl()->instances->update(0, $_asl_options);
        // Force instance data to the debug storage
        wd_asl()->debug->pushData(
            $_asl_options,
            'asl_options', true
        );

        $messages .= "<div class='infoMsg'>" . __("Ajax Search Lite settings saved!", "ajax-search-lite") . "</div>";
    }
    echo str_replace("{--messages--}", $messages, $output);
    ?>
</div>
<?php wp_enqueue_script('wd_asl_search_instance', plugin_dir_url(__FILE__) . 'settings/assets/search_instance.js', array('jquery'), ASL_CURR_VER_STRING, true); ?>