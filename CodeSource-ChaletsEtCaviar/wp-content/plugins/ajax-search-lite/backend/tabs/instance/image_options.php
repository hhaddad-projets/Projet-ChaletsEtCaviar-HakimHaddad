<div class="item">
    <?php
    $option_name = "show_images";
    $option_desc = __("Show images in results?", "ajax-search-lite");
    $o = new wpdreamsYesNo($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "image_width";
    $option_desc = __("Image width", "ajax-search-lite");
    $o = new wpdreamsTextSmall($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "image_height";
    $option_desc = __("Image height", "ajax-search-lite");
    $o = new wpdreamsTextSmall($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<fieldset>
    <legend>Image source settings</legend>
    <div class="item">
        <?php
        $option_name = "image_source1";
        $option_desc = __("Primary image source", "ajax-search-lite");
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source2";
        $option_desc = __("Alternative image source 1", "ajax-search-lite");
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source3";
        $option_desc = __("Alternative image source 2", "ajax-search-lite");
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source4";
        $option_desc = __("Alternative image source 3", "ajax-search-lite");
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source5";
        $option_desc = __("Alternative image source 4", "ajax-search-lite");
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_default";
        $option_desc = __("Default image url", "ajax-search-lite");
        $o = new wpdreamsUpload($option_name, $option_desc,
            $sd[$option_name]);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source_featured";
        $option_desc = "Featured image size source";
        $_feat_image_sizes = get_intermediate_image_sizes();
        $feat_image_sizes = array(
            array(
                "option" => "Original size",
                'value' => "original"
            )
        );
        foreach ($_feat_image_sizes as $k => $v)
            $feat_image_sizes[] = array(
                "option" => $v,
                "value"  => $v
            );
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$feat_image_sizes,
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_custom_field";
        $option_desc = __("Custom field containing the image", "ajax-search-lite");
        $o = new wpdreamsText($option_name, $option_desc,
            $sd[$option_name]);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>
<div class="item">
    <input type="hidden" name='asl_submit' value=1 />
    <input name="submit_asl" type="submit" value="<?php _e("Save options!", "ajax-search-lite"); ?>" />
</div>