<?php
if (!defined('ABSPATH')) die('-1');

require_once(ASL_CLASSES_PATH . "widgets/class-search-widget.php");

function asl_register_the_widgets() {
    register_widget("AjaxSearchLiteWidget");
}

add_action( 'widgets_init', 'asl_register_the_widgets' );