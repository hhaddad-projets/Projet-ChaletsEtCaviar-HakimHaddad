<div class="item"><?php
    $o = new wpdreamsCustomSelect("orderby_primary", __("Primary result ordering", "ajax-search-lite"),
        array(
            'selects' => array(
                array('option' => 'Relevance', 'value' => 'relevance DESC'),
                array('option' => 'Title descending', 'value' => 'title DESC'),
                array('option' => 'Title ascending', 'value' => 'title ASC'),
                array('option' => 'Date descending', 'value' => 'date DESC'),
                array('option' => 'Date ascending', 'value' => 'date ASC')
            ),
            'value' => $sd['orderby_primary']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php __("This is the primary ordering. If two elements match the primary ordering criteria, the Secondary ordering is used below.", "ajax-search-lite"); ?></p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("orderby_secondary", __("Secondary result ordering", "ajax-search-lite"),
        array(
            'selects' => array(
                array('option' => 'Relevance', 'value' => 'relevance DESC'),
                array('option' => 'Title descending', 'value' => 'title DESC'),
                array('option' => 'Title ascending', 'value' => 'title ASC'),
                array('option' => 'Date descending', 'value' => 'date DESC'),
                array('option' => 'Date ascending', 'value' => 'date ASC')
            ),
            'value' => $sd['orderby_secondary']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php __("For matching elements by primary ordering, this ordering is used.", "ajax-search-lite"); ?></p>
</div>