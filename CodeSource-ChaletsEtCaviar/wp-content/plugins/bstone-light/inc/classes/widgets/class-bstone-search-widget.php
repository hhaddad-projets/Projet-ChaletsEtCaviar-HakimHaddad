<?php
/**
 * Bstone Widget - Search
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if ( ! class_exists( 'bstone_light_widget_search' ) ) {
/***
     * Search Widget
     */
    class bstone_light_widget_search extends WP_Widget {

        function __construct() {
            parent::__construct(
                
                // Base ID of widget
                'bstone_light_widget_search',

                // Widget name will appear in UI
                __('Bstone Search', 'bstone-light'),

                // Widget description
                array( 'description' => __( 'Display search form.', 'bstone-light' ) )
            );

            // Enqueue the color picker script and style
            add_action( 'load-widgets.php', array( $this, 'load_wp_colorpicker' ) );
        }

        // Enqueue the color picker script and style
        function load_wp_colorpicker() {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        }

        // Widget Frontend
        public function widget( $args, $instance ) {

            $title              = apply_filters( 'widget_title', $instance['title'] );
            $type               = empty( $instance['type'] )               ? ''   : $instance['type'];
            $icon_color         = empty( $instance['icon_color'] )         ? ''   : $instance['icon_color'];
            $icon_color_hover   = empty( $instance['icon_color_hover'] )   ? ''   : $instance['icon_color_hover'];
            $border_color       = empty( $instance['border_color'] )       ? ''   : $instance['border_color'];
            $bg_color           = empty( $instance['bg_color'] )           ? ''   : $instance['bg_color'];
            $text_color         = empty( $instance['text_color'] )         ? ''   : $instance['text_color'];
            $icon_font_size     = empty( $instance['icon_font_size'] )     ? '14' : $instance['icon_font_size'];

            // Widget ID
            $bst_widget_id = 'bst-'.uniqid();

            ?>
            <aside class="widget bst-search-widget bst-search-<?php echo $type; ?>" id="<?php echo $bst_widget_id; ?>">
            <?php
            
                // before and after widget arguments are defined by themes
                echo ( isset( $before_widget ) ? $before_widget : '' );

                if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];
                
                // This is where you run the code and display the output
                ?>
                

                <div class="bst-search-icon">
                    <a class="slide-search bstone-search-icon" href="#" data-widgetid="<?php echo $bst_widget_id; ?>">
                        <span class="screen-reader-text"><?php bstone_light_default_strings('search-form-screen-reader'); ?></span>
                    </a>
                </div>

                <?php
                if( 'default' == $type ) {
                    echo '<div class="bst-default-search-cnt">';
                    
                } else if( 'animated' == $type ) {
                    echo '<div class="bst-animated-search-cnt">';

                } else if( 'fullscreen' == $type ) {
                    echo '<div class="bst-fullscreen-search-cnt" title="'.bstone_light_default_strings('search-form-scrren-title', false).'">';
                    echo '<span class="bst-closebtn" title="'.bstone_light_default_strings('search-form-close-button', false).'"><i class="bst-close-icon"></i></span>';

                } else {
                    echo '<div class="bst-search-form-cnt">';
                }
                ?>
                <div class="bst-search-inner-cnt">
                <?php get_search_form(); ?>

                </div></div>

                
                <?php
                echo ( isset( $after_widget ) ? $after_widget : '' );
                ?>

                <?php
                    if( function_exists( 'bstone_header_break_point' ) ) {

                        $bst_break_point = bstone_header_break_point();

                    } else {
                        $bst_break_point = '921';
                    }
                ?>

                <style type="text/css">
                    #<?php echo $bst_widget_id; ?> .bst-search-icon .bstone-search-icon {
                        color: <?php echo $icon_color; ?>;
                        font-size: <?php echo $icon_font_size; ?>px;
                    }
                    
                    #<?php echo $bst_widget_id; ?> .bst-search-icon .bstone-search-icon:hover {
                        color: <?php echo $icon_color_hover; ?>;
                    }

                    #<?php echo $bst_widget_id; ?> input[type="search"] {
                        color: <?php echo $text_color; ?>;
                        border-color: <?php echo $border_color; ?>;
                        background-color: <?php echo $bg_color; ?>;
                    }

                    #<?php echo $bst_widget_id; ?> input[type="search"]:focus {
                        border-color: <?php echo $icon_color_hover; ?>;
                    }

                    #<?php echo $bst_widget_id; ?> ::placeholder {
                        color: <?php echo $text_color; ?>;
                    }

                    #<?php echo $bst_widget_id; ?> :-ms-input-placeholder {
                        color: <?php echo $text_color; ?>;
                    }

                    #<?php echo $bst_widget_id; ?> ::-ms-input-placeholder {
                        color: <?php echo $text_color; ?>;
                    }

                    @media only screen and (max-width: <?php echo $bst_break_point; ?>px) {
                        .bst-search-widget.bst-search-animated .bst-search-icon {position: absolute;}

                        .bst-search-animated .bst-search-icon .bstone-search-icon {
                            display: block;
                            height: 40px;
                            line-height: 40px;
                            width: 40px;
                            margin-right: 15px;
                        }

                        .bst-search-widget.bst-search-animated form {
                            width: 250px;
                            right: auto;
                            top: 0;
                            z-index: 9999;
                            overflow: hidden;
                            position: relative;
                            -webkit-transition: width 0.3s;
                            -moz-transition: width 0.3s;
                            transition: width 0.3s;
                        }
                    }
                </style>

            </aside>
            <?php
        }

        // Widget Backend
        public function form( $instance ) {
            $defaults = array(
                'title'              => '',
                'type'               => 'default',
                'icon_color'         => '#3a3a3a',
                'icon_color_hover'   => '#0085ba',
                'border_color'       => '#cccccc',
                'bg_color'           => '#ffffff',
                'text_color'         => '#3a3a3a',
                'icon_font_size'     => 16
            );

            $instance = wp_parse_args( ( array ) $instance, $defaults );

            $title              = esc_attr( $instance['title'] );
            $type               = esc_attr( $instance['type'] );
            $icon_color         = esc_attr( $instance['icon_color'] );
            $icon_color_hover   = esc_attr( $instance['icon_color_hover'] );
            $border_color       = esc_attr( $instance['border_color'] );
            $bg_color           = esc_attr( $instance['bg_color'] );
            $text_color         = esc_attr( $instance['text_color'] );
            $icon_font_size     = esc_attr( $instance['icon_font_size'] );

            // Widget admin form
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Style:', 'bstone-light' ); ?>
                    <select class='widefat' id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text">
                    
                        <option value='default' <?php echo ( 'default' == $type )?'selected':''; ?>>
                            <?php _e( 'Default', 'bstone-light' ); ?>
                        </option>
                        
                        <option value='animated' <?php echo ( 'animated' == $type )?'selected':''; ?>>
                            <?php _e( 'Animated', 'bstone-light' ); ?>
                        </option>
                        
                        <option value='fullscreen' <?php echo ( 'fullscreen' == $type )?'selected':''; ?>>
                            <?php _e( 'Full Screen', 'bstone-light' ); ?>
                        </option>
                        
                    </select>                
                </label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_font_size' ); ?>"><?php _e( 'Icon Font Size:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_font_size' ); ?>" name="<?php echo $this->get_field_name( 'icon_font_size' ); ?>" type="number" value="<?php echo esc_attr( $icon_font_size ); ?>" />
            </p>

            <script type='text/javascript'>
                jQuery(document).ready(function($) {
                    $('.bst-color-picker').wpColorPicker();
                });
            </script>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php _e( 'Icon Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" value="<?php echo $icon_color; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>"><?php _e( 'Icon Color Hover', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_color_hover' ); ?>" value="<?php echo $icon_color_hover; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'border_color' ); ?>"><?php _e( 'Border Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'border_color' ); ?>" name="<?php echo $this->get_field_name( 'border_color' ); ?>" value="<?php echo $border_color; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e( 'Background Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" value="<?php echo $bg_color; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'text_color' ); ?>"><?php _e( 'Text Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'text_color' ); ?>" name="<?php echo $this->get_field_name( 'text_color' ); ?>" value="<?php echo $text_color; ?>" />                            
            </p>
            <?php
        }
            
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title']              = ( ! empty( $new_instance['title'] ) )              ? strip_tags( $new_instance['title'] )              : '';
            $instance['type']               = ( ! empty( $new_instance['type'] ) )               ? strip_tags( $new_instance['type'] )               : '';
            $instance['icon_color']         = ( ! empty( $new_instance['icon_color'] ) )         ? strip_tags( $new_instance['icon_color'] )         : '';
            $instance['icon_color_hover']   = ( ! empty( $new_instance['icon_color_hover'] ) )   ? strip_tags( $new_instance['icon_color_hover'] )   : '';
            $instance['border_color']       = ( ! empty( $new_instance['border_color'] ) )       ? strip_tags( $new_instance['border_color'] )       : '';
            $instance['bg_color']           = ( ! empty( $new_instance['bg_color'] ) )           ? strip_tags( $new_instance['bg_color'] )           : '';
            $instance['text_color']         = ( ! empty( $new_instance['text_color'] ) )         ? strip_tags( $new_instance['text_color'] )         : '';
            $instance['icon_font_size']     = ( ! empty( $new_instance['icon_font_size'] ) )     ? strip_tags( $new_instance['icon_font_size'] )     : '';
            return $instance;
        }
    }
}