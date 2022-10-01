<div class="item">
    <?php
    $o = new wpdreamsYesNo("override_search_form", __("Try to replace the theme search with Ajax Search Lite form?", "ajax-search-lite"),
        $sd["override_search_form"]);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Works with most themes, which use the searchform.php theme file to display their search forms.", "ajax-search-lite"); ?></p>
</div>
<?php if ( class_exists("WooCommerce") ): ?>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("override_woo_search_form", __("Try to replace the WooCommerce search with Ajax Search Lite form?", "ajax-search-lite"),
        $sd["override_woo_search_form"]);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Works with most themes, which use the searchform.php theme file to display their search forms.", "ajax-search-lite"); ?></p>
</div>
<?php endif; ?>
<div class="item"><?php
    $o = new wpdreamsCustomPostTypes("customtypes", __("Search in custom post types", "ajax-search-lite"),
        $sd['customtypes']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchintitle", __("Search in title?", "ajax-search-lite"),
        $sd['searchintitle']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchincontent", __("Search in content?", "ajax-search-lite"),
        $sd['searchincontent']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchinexcerpt", __("Search in post excerpts?", "ajax-search-lite"),
        $sd['searchinexcerpt']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_permalinks", __("Search in permalinks?", "ajax-search-lite"),
        $sd['search_in_permalinks']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_ids", __('Search in post (and CPT) IDs?', 'ajax-search-lite'),
        $sd['search_in_ids']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_all_cf", __("Search all custom fields?", "ajax-search-lite"),
        $sd['search_all_cf']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomFields("customfields", __("..or search in selected custom fields?", "ajax-search-lite"),
        $sd['customfields']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?>
</div>
<div class="item">
    <?php $o = new wpdreamsText("post_status", __('Post statuses to search', 'ajax-search-lite'), $sd['post_status']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        <?php echo __('Comma separated list. WP Defaults: publish, future, draft, pending, private, trash, auto-draft', 'ajax-search-lite'); ?>
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchinterms", __("Search in terms? (categories, tags)", "ajax-search-lite"),
        $sd['searchinterms']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>