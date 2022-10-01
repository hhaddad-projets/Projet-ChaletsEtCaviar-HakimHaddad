<div class="item">
    <?php
    $o = new wpdreamsYesNo("results_click_blank", __("Open the results in a new window?", "ajax-search-lite"), $sd['results_click_blank']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("scroll_to_results", __("Sroll the window to the result list?", "ajax-search-lite"), $sd['scroll_to_results']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("resultareaclickable", __("Make the whole result area clickable?", "ajax-search-lite"), $sd['resultareaclickable']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("close_on_document_click", __("Close result list on document click?", "ajax-search-lite"), $sd['close_on_document_click']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("show_close_icon", __("Show the close icon?", "ajax-search-lite"), $sd['show_close_icon']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsText("noresultstext", __("No results text", "ajax-search-lite"), $sd['noresultstext']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsText("didyoumeantext", __("Did you mean text", "ajax-search-lite"), $sd['didyoumeantext']);
    $params[$o->getName()] = $o->getData();
    ?></div>