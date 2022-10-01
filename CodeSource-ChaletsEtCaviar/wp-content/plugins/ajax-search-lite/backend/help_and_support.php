<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=470596109688127&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div id="wpdreams" class='asl_updates_help<?php echo isset($_COOKIE['asl-accessibility']) ? ' wd-accessible' : ''; ?>'>
    <div class="wpdreams-box" style='vertical-align: middle;'>
        <a class='gopro' href='http://demo.wp-dreams.com/?product=ajax_search_pro' target='_blank'>Get the pro version!</a>
        or leave a like :)
        <div style='display: inline-block;' class="fb-like" data-href="https://www.facebook.com/pages/WPDreams/383702515034741" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
        or you can follow me
        <a href="https://twitter.com/ernest_marcinko" class="twitter-follow-button" data-show-count="false">Follow @ernest_marcinko</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>

    <div class="wpdreams-box" style="float:left;">

        <div class='wpdreams-slider'>

            <div class="wpd-half">
                <h3>Support</h3>
                <div class="item">
                    <p>For support please visit the <a target='_blank' href="https://wordpress.org/support/plugin/ajax-search-lite">Ajax Search Lite support forums</a> on wordpress.org</p>
                    <p>
                        Before opening a ticket please:
                        <ul>
                            <li>Search through the threads, the problem might have been solved before</li>
                            <li>Make sure your search configuration is indeed correct</li>
                            <li>Upload the debug data to <a href="https://paste.ee" target="_blank">paste.ee</a> (or to any text paste provider) and <strong>share the url to the paste</strong> in the support message.
                            <br>Please <strong>do not paste this directly to the support forums</strong>, it is lots of data!
                            </li>
                        </ul>
                    </p>
                </div>
            </div>

            <div class="wpd-half-last">
                <div class="item">
                    <h3>Debug Data</h3>
                    <textarea><?php echo wd_asl()->debug->getSerializedStorage(); ?></textarea>

                    <p class="descMsg" style="text-align: left;">
                        This is basic debugging information, mainly for support purposes. In case of contacting
                        the support forums, please copy and paste this data to <a href="https://paste.ee" target="_blank">paste.ee</a> (or to any text paste provider) and <strong>share the url to the paste</strong> in the support message.
                        <br>This data contains the configuration and the last 5 search queries executed, it can be extremely helpful for support.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div id="asl-side-container">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asl-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>