<div class="item"><?php
    $o = new wpdreamsYesNo("autocomplete", "Turn on google search autocomplete?", $sd['autocomplete']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Autocomplete feature will try to help the user finish what is being typed into the search box.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsYesNo("kw_suggestions", "Turn on google search keyword suggestions?", $sd['kw_suggestions']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Keyword suggestions appear when no results match the keyword.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("kw_length", "Max. keyword length",
        $sd['kw_length']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The length of each suggestion in characters. 30-60 is a good number to avoid too long suggestions.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("kw_count", "Max. keywords count",
        $sd['kw_count']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsLanguageSelect("kw_google_lang", "Google suggestions language",
        $sd['kw_google_lang']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("kw_exceptions", "Keyword exceptions (comma separated)", $sd['kw_exceptions']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>