<p class='infoMsg'>
    <?php echo __('This css will be added before the plugin as inline CSS so it has a precedence
    over plugin CSS. (you can override existing rules)', 'ajax-search-lite'); ?>
</p>
<div class="item">
    <?php
    $o = new wd_Textarea_B64('custom_css', __('Custom CSS', 'ajax-search-lite'), $sd['custom_css']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>