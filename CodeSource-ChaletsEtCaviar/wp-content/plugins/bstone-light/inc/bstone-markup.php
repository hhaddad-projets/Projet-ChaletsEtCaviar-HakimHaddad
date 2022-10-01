<?php
/**
 * Plugin page markup
 * 
 * @package Bstone Light
 * 
 * @since   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Function to get plugin page header
 */
if ( ! function_exists( 'bstone_light_header_markup' ) ) {

	/**
	 * Page Header Markup
     * 
	 * @since 1.0.0
	 */
	function bstone_light_header_markup() {
        ?>        
        <div class="bst-theme-page-header st-flex">

            <?php bstone_light_header_top(); ?>

            <?php bstone_light_header_content(); ?>

            <?php bstone_light_header_bottom(); ?>

        </div>            
		<?php
	}
}

add_action( 'bstone_light_header_main', 'bstone_light_header_markup', 10 );

/**
 * Function to get plugin page header Content
 */
if ( ! function_exists( 'bstone_light_header_inner_markup' ) ) {

	/**
	 * Page Header Inner
     * 
	 * @since 1.0.0
	 */
    function bstone_light_header_inner_markup() {
        ?>
        <div class="bst-theme-title st-flex-grow-1">
            <a href="<?php echo BSTONE_LIGHT_LINK; ?>" target="_blank">
                <img src="<?php echo BSTONE_LIGHT_URI.'inc/assets/images/logo.png'; ?>" alt="<?php echo __( 'Bstone Options', 'bstone-light' ); ?>" />
            </a>
        </div>

        <div class="bst-header-buttons">
            <a href="<?php echo BSTONE_LIGHT_LINK; ?>" class="support" target="_blank"><span class="dashicons dashicons-sos"></span> <?php echo __( 'Open A Support Ticket', 'bstone-light' ); ?></a>
            <a href="<?php echo BSTONE_LIGHT_LINK; ?>documentation/" class="kb" target="_blank"><span class="dashicons dashicons-book"></span> <?php echo __( 'Knowledge Base', 'bstone-light' ); ?></a>
        </div>
        <?php
    }
}

add_action( 'bstone_light_header_content', 'bstone_light_header_inner_markup', 10 );

/**
 * Function to get plugin page content area
 */
if ( ! function_exists( 'bstone_light_content_container_markup' ) ) {

	/**
	 * Content Container
     * 
	 * @since 1.0.0
	 */
    function bstone_light_content_container_markup() {
        ?>
        <div class="bstone-options-cnt st-flex content-top">
            
            <?php bstone_light_primary_content_top(); ?>

            <?php bstone_light_primary_content(); ?>

            <?php bstone_light_primary_content_bottom(); ?>

        </div>
        <?php
    }
}

add_action( 'bstone_light_primary_content_container', 'bstone_light_content_container_markup', 10 );

/**
 * Get Primary Content
 */
if ( ! function_exists( 'bstone_light_primary_content_markup' ) ) {

	/**
	 * Primary Content
     * 
	 * @since 1.0.0
	 */
    function bstone_light_primary_content_markup() {
        ?>
        <div class="st-flex-grow-1">

            <?php bstone_light_primary_content_sections(); ?>

        </div>
        <?php
    }
}

add_action( 'bstone_light_primary_content', 'bstone_light_primary_content_markup', 10 );

/**
 * Getting Started Section
 */
if ( ! function_exists( 'bstone_light_primary_section_getting_started_markup' ) ) {
    /**
	 * Getting Started
     * 
	 * @since 1.0.0
	 */
    function bstone_light_primary_section_getting_started_markup() {
        ?>
        <div class="bst-options-section">
            <h2 class="bst-section-title"><?php echo __( 'Getting Started', 'bstone-light' ); ?></h2>
            <p class="bst-desc"><?php echo __( 'Take a look in the options of the Customizer and see yourself how easy and quick to customize your website as you wish.', 'bstone-light' ); ?></p>

            <ul class="bst-customizer-quick-links">
                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'custom_logo', 'control' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-format-image"></span>
                        <span class="link-title"><?php echo __('Upload Logo', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Add your own logo and retina logo used for retina screens.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'panel-colors', 'panel' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-admin-customizer"></span>
                        <span class="link-title"><?php echo __('Colors & Background', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Replace the default colors & backgrounds by your own.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'panel-typography', 'panel' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-editor-textcolor"></span>
                        <span class="link-title"><?php echo __('Customize Fonts', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Choose your own typography for any parts of your website.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'section-header', 'section' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-align-center"></span>
                        <span class="link-title"><?php echo __('Header Options', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Choose the style, the height and the colors for your site header.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'section-footer', 'section' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <span class="link-title"><?php echo __('Footer Settings', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Choose the columns number, paddings and colors of footer.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'section-sidebars', 'section' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-align-left"></span>
                        <span class="link-title"><?php echo __('Sidebar Options', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Enable/Disable the side bar, add your own paddings and colors.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'section-container-layout', 'section' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-layout"></span>
                        <span class="link-title"><?php echo __('Layout Options', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Choose layouts for pages and posts, add your own paddings and borders.', 'bstone-light' ); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url( bstone_get_customizer_link( 'section-blog', 'section' ) ); ?>" target="_blank">
                        <span class="dashicons dashicons-tagcloud"></span>
                        <span class="link-title"><?php echo __('Blog Layouts', 'bstone-light'); ?></span>
                        <span class="link-desc"><?php echo __( 'Choose layout of your blog and modify blog posts display style.', 'bstone-light' ); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <?php
    }
}

add_action( 'bstone_light_primary_content_sections', 'bstone_light_primary_section_getting_started_markup', 10 );

/**
 * Disable Customizer Settings
 */
if ( ! function_exists( 'bstone_light_primary_section_disable_customizer_settings_markup' ) ) {
    /**
	 * Disable Customizer Settings
     * 
	 * @since 1.0.0
	 */
    function bstone_light_primary_section_disable_customizer_settings_markup() {
        ?>
        <div class="bst-options-section">
            <h2 class="bst-section-title"><?php echo __( 'Customizer Sections', 'bstone-light' ); ?></h2>
            <p class="bst-desc"><?php echo __( 'Disable the Customizer panels that you do not have or need anymore to load it quickly. Your settings are saved, so do not worry.', 'bstone-light' ); ?></p>

            <?php
                echo Bstone_Light_Customizer_Options::get_bst_options_form();
            ?>
        </div>
        <?php
    }
}

add_action( 'bstone_light_primary_content_sections', 'bstone_light_primary_section_disable_customizer_settings_markup', 20 );

/**
 * Get Sidebar
 */
if ( ! function_exists( 'bstone_light_sidebar_main_markup' ) ) {

	/**
	 * Sidebar
     * 
	 * @since 1.0.0
	 */
    function bstone_light_sidebar_main_markup() {
        ?>
        <div class="bst-options-sidebar">
            
            <?php bstone_light_sidebar_top(); ?>

            <?php bstone_light_sidebar_content(); ?>

            <?php bstone_light_sidebar_bottom(); ?>

        </div>
        <?php
    }
}

add_action( 'bstone_light_primary_content', 'bstone_light_sidebar_main_markup', 20 );

/**
 * Sidebar Widget - Are you a helpful person?
 */
if ( ! function_exists( 'bstone_light_widget_markup_review' ) ) {

	/**
	 * Are you a helpful person?
     * 
	 * @since 1.0.0
	 */
    function bstone_light_widget_markup_review() {
        ?>
        <div class="bst-options-widget bst-review">
            <div class="bst-widget-title bst-title-bg"><?php echo __( 'Are you a helpful person?', 'bstone-light' ); ?></div>
            <div class="bst-widget-desc">
                <p><?php echo __( 'I’m grateful that you’ve decided to join the Bstone family. If I could take 2 min of your time, I’d really appreciate if you could leave a review. By spreading the love, we can create even greater free stuff in the future!', 'bstone-light' ); ?></p>

                <div class="bst-spacer"></div><div class="bst-spacer-2"></div>
                <a class="bst-widget-button blue" href="https://wordpress.org/support/theme/bstone/reviews/#new-post" target="_blank">
                    <?php echo __( 'Leave my review', 'bstone-light' ); ?>
                </a>
                <div class="bst-spacer"></div>

                <p class="bst-thanks"><?php echo __( 'Thank you very much!', 'bstone-light' ); ?></p>
            </div>
            <i class="dashicons dashicons-wordpress"></i>
        </div>
        <?php
    }
}

add_action( 'bstone_light_sidebar_content', 'bstone_light_widget_markup_review', 10 );

/**
 * Sidebar Widget - Import Bstone Demos
 */
if ( ! function_exists( 'bstone_light_widget_markup_import_demos' ) ) {

	/**
	 * Import Bstone Demos
     * 
	 * @since 1.0.0
	 */
    function bstone_light_widget_markup_import_demos() {
        ?>
        <div class="bst-options-widget">
            <div class="bst-widget-title bst-title-bg"><?php echo __( 'Import Bstone Demos', 'bstone-light' ); ?></div>
            <div class="bst-widget-desc">
                <p><?php echo __( 'Did you know Bstone offers a free library of Demo Sites? Import your favorite site with one click and start your project in style!', 'bstone-light' ); ?></p>

                <div class="bst-spacer"></div><div class="bst-spacer-2"></div>

                <?php
                    if( ! file_exists( WP_PLUGIN_DIR . '/one-click-demo-import/one-click-demo-import.php' ) ) {

                        $class       = 'bst-widget-button bst-sites-notinstalled';
                        $button_text = __( 'Install Importer Plugin', 'bstone-light' );
                        $data_slug   = 'one-click-demo-import';
                        $data_init   = '/one-click-demo-import/one-click-demo-import.php';

                    } else if( is_plugin_inactive( 'one-click-demo-import/one-click-demo-import.php' ) ) {

                        $class       = 'bst-widget-button bst-sites-inactive';
                        $button_text = __( 'Activate Importer Plugin', 'bstone-light' );
                        $data_slug   = 'one-click-demo-import';
                        $data_init   = '/one-click-demo-import/one-click-demo-import.php';

                    } else {

                        $class       = 'bst-widget-button bst-view-sites';
                        $button_text = __( 'See Demo Sites', 'bstone-light' );
                        $link        = admin_url( 'themes.php?page=bstone-demo-import' );
                    }

                    printf(
                        '<a class="%1$s" %2$s %3$s %4$s> %5$s </a>',
                        esc_attr( $class ),
                        isset( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
                        isset( $data_slug ) ? 'data-slug="' . esc_attr( $data_slug ) . '"' : '',
                        isset( $data_init ) ? 'data-init="' . esc_attr( $data_init ) . '"' : '',
                        esc_html( $button_text )
                    );
                ?>
                <div class="bst-spacer"></div>
            </div>
            <i class="dashicons dashicons-admin-site"></i>
        </div>
        <?php
    }
}

add_action( 'bstone_light_sidebar_content', 'bstone_light_widget_markup_import_demos', 20 );

/**
 * Sidebar Widget - Bstone Community
 */
if ( ! function_exists( 'bstone_light_widget_markup_community' ) ) {

	/**
	 * Bstone Community
     * 
	 * @since 1.0.0
	 */
    function bstone_light_widget_markup_community() {
        ?>
        <div class="bst-options-widget">
            <div class="bst-widget-title bst-title-bg"><?php echo __( 'Bstone Community', 'bstone-light' ); ?></div>
            <div class="bst-widget-desc">
                <p><?php echo __( 'Join the community of super helpful Bstone users. Say hello, ask questions, give feedback and help each other!', 'bstone-light' ); ?></p>

                <div class="bst-spacer"></div><div class="bst-spacer-2"></div>
                <a class="bst-widget-button blue" href="https://www.facebook.com/groups/wpbstone/" target="_blank">
                    <?php echo __( 'Join our facebook group', 'bstone-light' ); ?>
                </a>
                <div class="bst-spacer"></div>
            </div>
            <i class="dashicons dashicons-groups"></i>
        </div>
        <?php
    }
}

add_action( 'bstone_light_sidebar_content', 'bstone_light_widget_markup_community', 30 );