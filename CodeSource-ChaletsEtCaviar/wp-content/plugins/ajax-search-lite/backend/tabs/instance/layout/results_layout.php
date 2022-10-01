<div class="item">
    <?php
    $o = new wpdreamsYesNo("description_context", __("Display the description context?", "ajax-search-lite"),
        $sd['description_context']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php __("Will display the description from around the search phrase, not from the beginning.", "ajax-search-lite"); ?></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("v_res_max_height", __("Result box maximum height", "ajax-search-lite"), $sd['v_res_max_height']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("If this value is reached, the scrollbar will definitely trigger. none or pixel units, like 800px. Default: none", "ajax-search-lite"); ?></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("itemscount", __("Results box viewport (in item numbers)", "ajax-search-lite"), $sd['itemscount'], array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Used to calculate the box height. Result box height = (this option) x (average item height)", "ajax-search-lite"); ?></p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showmoreresults", __("Show 'More results..' text in the bottom of the search box?", "ajax-search-lite"), $sd['showmoreresults']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("showmoreresultstext", __("' Show more results..' text", "ajax-search-lite"), $sd['showmoreresultstext']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showauthor", __("Show author in results?", "ajax-search-lite"), $sd['showauthor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdate", __("Show date in results?", "ajax-search-lite"), $sd['showdate']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdescription", __("Show description in results?", "ajax-search-lite"), $sd['showdescription']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("description_context", __("Display the description context?", "ajax-search-lite"), $sd['description_context']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        <?php echo __("Will display the description from around the search phrase, not from the beginning.", "ajax-search-lite"); ?>
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("descriptionlength", __("Description length", "ajax-search-lite"), $sd['descriptionlength']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>