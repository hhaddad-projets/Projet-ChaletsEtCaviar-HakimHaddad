<?php
/**
 * Add extra features like widgets and import demo content.
 *
 * @package Bstone Light
 * 
 * @since   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Bstone Light Classes
require BSTONE_LIGHT_DIR . 'inc/classes/class-bstone-light.php';
require BSTONE_LIGHT_DIR . 'inc/classes/class-bstone-light-customizer-options.php';

// Plugin Page Hooks
require BSTONE_LIGHT_DIR . 'inc/bstone-light-hooks.php';

// Text Strings
require BSTONE_LIGHT_DIR . 'inc/bstone-light-strings.php';

// Plugin Functions
require BSTONE_LIGHT_DIR . 'inc/bstone-light-functions.php';

// Enqueue Scripts
require BSTONE_LIGHT_DIR . 'inc/enqueue-admin-scripts.php';
require BSTONE_LIGHT_DIR . 'inc/classes/class-enqueue-scripts.php';

// Demo Import Settings
require BSTONE_LIGHT_DIR . 'inc/bstone-import-demo-sites.php';

// Options Page Content
require BSTONE_LIGHT_DIR . 'inc/bstone-markup.php';
require BSTONE_LIGHT_DIR . 'inc/bstone-options.php';

// Bstone Widgets
require BSTONE_LIGHT_DIR . 'inc/bstone-widgets-init.php';
require BSTONE_LIGHT_DIR . 'inc/classes/widgets/class-bstone-latest-articles-widget.php';
require BSTONE_LIGHT_DIR . 'inc/classes/widgets/class-bstone-search-widget.php';
require BSTONE_LIGHT_DIR . 'inc/classes/widgets/social-icons/class-bstone-social-icons-widget.php';
require BSTONE_LIGHT_DIR . 'inc/classes/widgets/class-bstone-woo-mini-cart-widget.php';