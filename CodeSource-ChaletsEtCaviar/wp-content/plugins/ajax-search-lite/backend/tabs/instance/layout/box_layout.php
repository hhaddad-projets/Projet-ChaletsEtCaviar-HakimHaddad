<?php
$themes = array(
    array('option'=>'Simple Red', 'value'=>'simple-red'),
    array('option'=>'Simple Blue', 'value'=>'simple-blue'),
    array('option'=>'Simple Grey', 'value'=>'simple-grey'),
    array('option'=>'Classic Blue', 'value'=>'classic-blue'),
    array('option'=>'Curvy Black', 'value'=>'curvy-black'),
    array('option'=>'Curvy Red', 'value'=>'curvy-red'),
    array('option'=>'Curvy Blue', 'value'=>'curvy-blue'),
    array('option'=>'Underline White', 'value'=>'underline')
);
?>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <div class="asl_theme"></div>
    <?php
    $o = new wpdreamsCustomSelect("theme", __("Theme", "ajax-search-lite"), array(
        'selects'=>$themes,
        'value'=>$sd['theme']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("defaultsearchtext", __("Placeholder text", "ajax-search-lite"), $sd['defaultsearchtext']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("box_width", __("Search Box width", "ajax-search-lite"), $sd['box_width']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Include the unit as well, example: 10px or 1em or 90%", "ajax-search-lite"); ?></p>
</div>
<div class="item">
    <?php
    $option_name = "box_margin";
    $option_desc = __("Search box margin", "ajax-search-lite");
    $option_expl = __("Include the unit as well, example: 10px or 1em or 90%", "ajax-search-lite");
    $o = new wpdreamsFour($option_name, $option_desc,
        array(
            "desc" => $option_expl,
            "value" => $sd[$option_name]
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("box_font", __("Search plugin Font Family", "ajax-search-lite"), $sd['box_font']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("The Font Family used within the plugin. Default: Open Sans", "ajax-search-lite"); ?><br>
    <?php echo __("Entering multiple font family names like <strong>Helvetica, Sans-serif</strong> or <strong>inherit</strong> are also supported.", "ajax-search-lite"); ?></p>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("override_bg", __("Override background color?", "ajax-search-lite"),
        $sd['override_bg']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsColorPicker("override_bg_color", __("color:", "ajax-search-lite"),
        $sd['override_bg_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("override_icon", __("Override magnifier & icon colors?", "ajax-search-lite"),
        $sd['override_icon']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsColorPicker("override_icon_bg_color", __("icon background colors", "ajax-search-lite"),
        $sd['override_icon_bg_color']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsColorPicker("override_icon_color", __("icon colors", "ajax-search-lite"),
        $sd['override_icon_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <div style="margin: 8px 17px 16px 0;">
    <?php
    $o = new wpdreamsYesNo("override_border", __("Override search box border?", "ajax-search-lite"),
        $sd['override_border']);
    $params[$o->getName()] = $o->getData();
    ?></div><?php
    $o = new wpdreamsBorder("override_border_style", __("Border style", "ajax-search-lite"),
        $sd['override_border_style']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>