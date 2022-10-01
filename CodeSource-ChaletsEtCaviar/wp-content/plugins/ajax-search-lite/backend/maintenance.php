<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (ASL_DEMO) $_POST = null;
?>
    <style>
        #wpdreams .asl_maintenance ul {
            list-style-type: disc;
            margin-bottom: 10px;
        }
        #wpdreams .asl_maintenance ul li {
            list-style-type: disc;
            margin-left: 30px;
            margin-top: 10px;
        }
    </style>
    <div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asl-accessibility']) ? ' wd-accessible' : ''; ?>'>
        <div class="wpdreams-box asl_maintenance" style="float:left;">
            <?php if (ASL_DEMO): ?>
                <p class="infoMsg"><strong>DEMO MODE ENABLED</strong> - Please note, that these options are read-only!</p>
            <?php endif; ?>
            <div id='asl_i_success' class="infoMsg<?php echo isset($_POST['asl_mnt_msg']) ? '' : ' hiddend'; ?>">
                <?php echo isset($_POST['asl_mnt_msg']) ? strip_tags($_POST['asl_mnt_msg']) : ''; ?>
            </div>
            <div id='asl_i_error' class="errorMsg hiddend"></div>
            <textarea id="asl_i_error_cont" class="hiddend"></textarea>

            <form name="asl_reset_form" id="asl_reset_form" action="maintenance.php" method="POST">
                <fieldset>
                    <legend>Maintencance -  Reset</legend>
                    <p>This option will reset all the plugin options to the defaults. Use this option if you want to keep using the plugin, but you need to reset the default options.
                    <ul>
                        <li>All plugin options <strong>will</strong> reset to defaults (performance, compatibility, analytics)</li>
                        <li>The search instance options <strong>will not</strong> be changed</li>
                        <li>The database tables, contents and the files <strong>will not</strong> be deleted either.</li>
                    </ul>
                    </p>
                    <div style="text-align: center;">
                        <?php if (ASL_DEMO): ?>
                            <input type="button" name="asl_reset" id="asl_reset" class="submit wd_button_green" value="Reset all options to defaults" disabled>
                        <?php else: ?>
                            <input type="hidden" name="asl_reset_nonce" id="asl_reset_nonce" value="<?php echo wp_create_nonce( "asl_reset_nonce" ); ?>">
                            <input type="button" name="asl_reset" id="asl_reset" class="submit wd_button_green" value="Reset all options to defaults">
                            <span class="loading-small hiddend"></span>
                        <?php endif; ?>
                    </div>
                </fieldset>
            </form>
            <form name="asl_wipe_form" id="asl_wipe_form" action="maintenance.php" method="POST">
                <fieldset>
                    <legend>Maintencance -  Wipe & Deactivate</legend>
                    <p>This option will wipe everything related to Ajax Search Pro, as if it was never installed. Use this if you don't want to use the plugin anymore, or if you want to perform a clean installation.
                    <ul>
                        <li>All plugin options <strong>will be deleted</strong></li>
                        <li>The search options <strong>will be deleted</strong></li>
                        <li>The database tables and the files <strong>will be deleted</strong></li>
                        <li>The plugin <strong>will deactivate</strong> and redirect to the plugin manager screen after, where you can delete it or re-install it again.</li>
                    </ul>
                    </p>
                    <div style="text-align: center;">
                        <?php if (ASL_DEMO): ?>
                            <input type="button" name="asl_wipe" id="asl_wipe" class="submit" value="Wipe all plugin data & deactivate Ajax Search Pro" disabled>
                        <?php else: ?>
                            <input type="hidden" name="asl_wipe_nonce" id="asl_wipe_nonce" value="<?php echo wp_create_nonce( "asl_wipe_nonce" ); ?>">
                            <input type="button" name="asl_wipe" id="asl_wipe" class="submit" value="Wipe all plugin data & deactivate Ajax Search Lite">
                            <span class="loading-small hiddend"></span>
                        <?php endif; ?>
                    </div>
                </fieldset>
            </form>
            <form name="asl_empty_redirect"id="asl_empty_redirect" method="post" style="display: none;">
                <input type="hidden" name="asl_mnt_msg" value="">
            </form>
        </div>
        <div id="asl-side-container">
            <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asl-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
        </div>
        <div class="clear"></div>
    </div>
<?php
if (!ASL_DEMO) {
    wp_enqueue_script('asl-backend-maintenance', plugin_dir_url(__FILE__) . 'settings/assets/maintenance.js', array(
        'jquery'
    ), ASL_CURR_VER_STRING, true);
    wp_localize_script('asl-backend-maintenance', 'ASL_MNT', array(
        "admin_url" => admin_url()
    ));
}
