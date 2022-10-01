<ul id="subtabs"  class='tabs'>
    <li><a tabid="401" class='subtheme current'><?php _e("Search Box layout", "ajax-search-lite"); ?></a></li>
    <li><a tabid="402" class='subtheme'><?php _e("Results layout", "ajax-search-lite"); ?></a></li>
    <li><a tabid="403" class='subtheme'><?php _e("Results Behaviour", "ajax-search-lite"); ?></a></li>
    <li><a tabid="404" class='subtheme'><?php _e("Keyword Highlighting", "ajax-search-lite"); ?></a></li>
    <li><a tabid="405" class='subtheme'><?php _e("Custom CSS", "ajax-search-lite"); ?></a></li>
</ul>
<div class='tabscontent'>
    <div tabid="401">
        <fieldset>
            <legend><?php _e("Search Box layout", "ajax-search-lite"); ?></legend>
            <?php include(ASL_PATH."backend/tabs/instance/layout/box_layout.php"); ?>
        </fieldset>
    </div>
    <div tabid="402">
        <fieldset>
            <legend><?php _e("Results layout", "ajax-search-lite"); ?></legend>
            <?php include(ASL_PATH."backend/tabs/instance/layout/results_layout.php"); ?>
        </fieldset>
    </div>
    <div tabid="403">
        <fieldset>
            <legend><?php _e("Results Behaviour", "ajax-search-lite"); ?></legend>
            <?php include(ASL_PATH."backend/tabs/instance/layout/results_behaviour.php"); ?>
        </fieldset>
    </div>
    <div tabid="404">
        <fieldset>
            <legend><?php _e("Keyword Highlighting", "ajax-search-lite"); ?></legend>
            <?php include(ASL_PATH."backend/tabs/instance/layout/keyword_highlight.php"); ?>
        </fieldset>
    </div>
    <div tabid="405">
        <fieldset>
            <legend><?php _e("Custom CSS", "ajax-search-lite"); ?></legend>
            <?php include(ASL_PATH."backend/tabs/instance/layout/custom_css.php"); ?>
        </fieldset>
    </div>
</div>
<div class="item">
    <input type="hidden" name='asl_submit' value=1 />
    <input name="submit_asl" type="submit" value="<?php _e("Save options!", "ajax-search-lite"); ?>" />
</div>