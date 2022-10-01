<div class="item">
    <?php
    $o = new wpdreamsYesNo("kw_highlight", __("Keyword highlighting", "ajax-search-lite"), $sd['kw_highlight']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("kw_highlight_whole_words", __("Highlight whole words only?", "ajax-search-lite"), $sd['kw_highlight_whole_words']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("highlight_color", "Highlight text color", $sd['highlight_color']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("highlight_bg_color", "Highlight-text background color", $sd['highlight_bg_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>