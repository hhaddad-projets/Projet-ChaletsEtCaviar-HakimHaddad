<fieldset class="<?php echo class_exists('WooCommerce') ? "" : "hiddend"; ?>">
    <legend>WooCommerce related</legend>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("exclude_woo_hidden", __("Exclude hidden WooCommerce products from search?", "ajax-search-lite"), $sd['exclude_woo_hidden']);
        $params[$o->getName()] = $o->getData();
        ?>
        <p class="descMsg">'Hidden' in this case means either hidden from search, hidden from catalog or both.</p>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("woo_exclude_outofstock", __("Exclude WooCommerce out of stock products?", "ajax-search-lite"), $sd['woo_exclude_outofstock']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("shortcode_op", __("What to do with shortcodes in results content?", "ajax-search-lite"), array(
        'selects'=>array(
            array("option"=>"Remove them, keep the content", "value" => "remove"),
            array("option"=>"Execute them (can by really slow)", "value" => "execute")
        ),
        'value'=>$sd['shortcode_op']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        <?php echo __("Removing shortcode is usually much faster, especially if you have many of them within posts.", "ajax-search-lite"); ?>
    </p>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("titlefield",  __("Title Field", "ajax-search-lite"), array(
        'selects'=>array(
            array('option' => 'Post Title', 'value' => 0),
            array('option' => 'Post Excerpt', 'value' => 1),
            array('option' => 'Custom Field', 'value' => 'c__f')
        ),
        'value'=>$sd['titlefield']
    ));
    $params[$o->getName()] = $o->getData();
    $o = new wd_CFSearchCallBack('titlefield_cf', '', array(
        'value'=>$sd['titlefield_cf'],
        'args'=> array(
            'controls_position' => 'left',
            'class'=>'wpd-text-right'
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("descriptionfield",  __("Description Field", "ajax-search-lite"), array(
        'selects'=>array(
            array('option' => 'Post Content', 'value' => 0),
            array('option' => 'Post Excerpt', 'value' => 1),
            array('option' => 'Post Title', 'value' => 2),
            array('option' => 'Custom Field', 'value' => 'c__f')
        ),
        'value'=>$sd['descriptionfield']
    ));
    $params[$o->getName()] = $o->getData();
    $o = new wd_CFSearchCallBack('descriptionfield_cf', '', array(
        'value'=>$sd['descriptionfield_cf'],
        'args'=> array(
            'controls_position' => 'left',
            'class'=>'wpd-text-right'
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCategories("excludecategories", __("Exclude categories", "ajax-search-lite"), $sd['excludecategories']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("excludeposts", __("Exclude Posts by ID's (comma separated post ID-s)", "ajax-search-lite"), $sd['excludeposts']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item<?php echo class_exists('SitePress') ? "" : " hiddend"; ?>">
    <?php
    $o = new wpdreamsYesNo("wpml_compatibility", __("WPML compatibility", "ajax-search-lite"), $sd['wpml_compatibility']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item<?php echo function_exists("pll_current_language") ? "" : " hiddend"; ?>">
    <?php
    $o = new wpdreamsYesNo("polylang_compatibility", __("Polylang compatibility", "ajax-search-lite"), $sd['polylang_compatibility']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <input type="hidden" name='asl_submit' value=1 />
    <input name="submit_asl" type="submit" value="Save options!" />
</div>