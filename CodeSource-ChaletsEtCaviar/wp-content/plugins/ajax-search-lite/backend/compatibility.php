<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$com_options = wd_asl()->o['asl_compatibility'];

if (ASL_DEMO) $_POST = null;
?>

<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asl-accessibility']) ? ' wd-accessible' : ''; ?>'>
    <div class="wpdreams-box" style="float:left;">

        <?php ob_start(); ?>


        <fieldset>
            <legend>CSS and JS compatibility</legend>

            <div class="item">
                <?php
                $o = new wpdreamsCustomSelect("js_source", "Javascript source", array(
                        'selects'   => array(
                            array('option' => 'Non minified', 'value' => 'nomin'),
                            array('option' => 'Minified', 'value' => 'min'),
                            array('option' => 'Non-minified scoped', 'value' => 'nomin-scoped'),
                            array('option' => 'Minified scoped', 'value' => 'min-scoped'),
                        ),
                        'value'     => $com_options['js_source']
                    )
                );
                $params[$o->getName()] = $o->getData();
                ?>
                <p class="descMsg">
                <ul style="float:right;text-align:left;width:50%;">
                    <li><b>Non minified</b> - Low Compatibility, Medium space</li>
                    <li><b>Minified</b> - Low Compatibility, Low space</li>
                    <li><b>Non minified Scoped</b> - High Compatibility, High space</li>
                    <li><b>Minified Scoped</b> - High Compatibility, Medium space</li>
                </ul>
                <div class="clear"></div>
                </p>
            </div>
            <div class="item">
                <?php
                $o = new wpdreamsCustomSelect("js_init", "Javascript init method", array(
                        'selects'=>array(
                            array('option'=>'Dynamic (default)', 'value'=>'dynamic'),
                            array('option'=>'Blocking', 'value'=>'blocking')
                        ),
                        'value'=>$com_options['js_init']
                    )
                );
                $params[$o->getName()] = $o->getData();
                ?>
                <p class="descMsg">
                    Try to choose <strong>Blocking</strong> if the search bar is not responding to anything.
                </p>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("detect_ajax", "Try to re-initialize if the page was loaded via ajax?",
                    $com_options['detect_ajax']
                ); ?>
                <p class='descMsg'>Will try to re-initialize the plugin in case an AJAX page loader is used, like Polylang language switcher etc..</p>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("js_retain_popstate", "Remember search phrase and options when using the Browser Back button?",
                    $com_options['js_retain_popstate']
                ); ?>
                <p class='descMsg'>Whenever the user clicks on a live search result, and decides to navigate back, the search will re-trigger and reset the previous options.</p>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("load_google_fonts", "Load the <strong>google fonts</strong> used in the search options?",
                    $com_options['load_google_fonts']
                ); ?>
                <p class='descMsg'>When <strong>turned off</strong>, the google fonts <strong>will not be loaded</strong> via this plugin at all.<br>Useful if you already have them loaded, to avoid mutliple loading times.</p>
            </div>
            <div class="item">
                <?php
                $o = new wpdreamsCustomSelect("load_mcustom_js", "Load the scrollbar script?", array(
                        'selects'=>array(
                            array('option'=>'Yes', 'value'=>'yes'),
                            array('option'=>'No', 'value'=>'no')
                        ),
                        'value'=>$com_options['load_mcustom_js']
                    )
                );
                $params[$o->getName()] = $o->getData();
                ?>
                <p class='descMsg'>
                <ul>
                    <li>When set to <strong>No</strong>, the custom scrollbar will <strong>not be used at all</strong>.</li>
                </ul>
                </p>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("old_browser_compatibility", "Display the default search box on old browsers? (IE<=8)",
                    $com_options['old_browser_compatibility']
                ); ?>
            </div>
        </fieldset>
        <fieldset>
            <legend>Query Compatibility</legend>

            <div class="item">
                <?php $o = new wpdreamsYesNo("use_acf_getfield", "<strong>Advacned Custom Fields</strong>: use the ACF get_field() function to get the metadata?",
                    $com_options['use_acf_getfield']
                ); ?>
                <p class='descMsg'>Will use the get_field() Advanced Custom Fields function instead of the core get_post_meta()</p>
            </div>

            <p class='infoMsg'>
                If you are experiencing issues with accent(diacritic) or case sensitiveness, you can force the search to try these tweaks.<br>
                <i>The search works according to your database collation settings</i>, so please be aware that <b>this is not an effective way</b> of fixing database collation issues.<br>
                If you have case/diacritic issues then please read the <a href="http://dev.mysql.com/doc/refman/5.0/en/charset-syntax.html" target="_blank">MySql manual on collations</a> or consult a <b>database expert</b> - those issues should be treated on database level!
            </p>
            <div class="item">
                <?php
                $o = new wpdreamsCustomSelect("db_force_case", "Force case", array(
                        'selects'=> array(
                            array('option' => 'None', 'value' => 'none'),
                            array('option' => 'Sensitivity', 'value' => 'sensitivity'),
                            array('option' => 'InSensitivity', 'value' => 'insensitivity')
                        ),
                        'value'=>$com_options['db_force_case']
                    )
                );
                $params[$o->getName()] = $o->getData();
                ?>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("db_force_unicode", "Force unicode search",
                    $com_options['db_force_unicode']
                ); ?>
                <p class='descMsg'>Will try to force unicode character conversion on the search phrase.</p>
            </div>
            <div class="item">
                <?php $o = new wpdreamsYesNo("db_force_utf8_like", "Force utf8 on LIKE operations",
                    $com_options['db_force_utf8_like']
                ); ?>
                <p class='descMsg'>Will try to force utf8 conversion on all LIKE operations in the WHERE and HAVING clauses.</p>
            </div>

        </fieldset>

        <?php $_r = ob_get_clean(); ?>


        <?php

        // Compatibility stuff
        $updated = false;
        if ( isset($_POST) && isset($_POST['asl_compatibility']) ) {
            $values = array(
                // CSS and JS
                "js_source" => $_POST['js_source'],
                "js_init" => $_POST['js_init'],
                "load_mcustom_js" => $_POST['load_mcustom_js'],
                "detect_ajax" => $_POST['detect_ajax'],
                "js_retain_popstate" => $_POST['js_retain_popstate'],
                'load_google_fonts' => $_POST['load_google_fonts'],
                'old_browser_compatibility' => $_POST['old_browser_compatibility'],
                // Query options
                'use_acf_getfield' => $_POST['use_acf_getfield'],
                "db_force_case" => $_POST['db_force_case'],
                "db_force_unicode" => $_POST['db_force_unicode'],
                "db_force_utf8_like" => $_POST['db_force_utf8_like']
            );
            update_option('asl_compatibility', $values);
            $updated = true;
        }

        ?>

        <div class='wpdreams-slider'>

            <?php if ($updated): ?>
                <div class='successMsg'>Search compatibility settings successfuly updated!</div><?php endif; ?>

            <?php if (ASL_DEMO): ?>
                <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only</p>
            <?php endif; ?>

            <div id="content" class='tabscontent'>

                <!-- Compatibility form -->
                <form name='compatibility' method='post'>

                    <?php print $_r; ?>

                    <div class="item">
                        <input type='submit' class='submit' value='Save options'/>
                    </div>
                    <input type='hidden' name='asl_compatibility' value='1'/>
                </form>

            </div>
        </div>
    </div>
    <div id="asl-side-container">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asl-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>