<?php
/**
 * Bstone Widget - Latest Articles
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.0
 */

if ( ! class_exists( 'bstone_light_widget_latest_articles' ) ) {
    /***
     * Latest Articles
     */
    class bstone_light_widget_latest_articles extends WP_Widget {

        function __construct() {
            parent::__construct(
                
                // Base ID of widget
                'bstone_light_widget_latest_articles',

                // Widget name will appear in UI
                __('Bstone Latest Articles', 'bstone-light'),

                // Widget description
                array( 'description' => __( 'Display latest articles.', 'bstone-light' ) )
            );
        }

        // Widget Frontend
        public function widget( $args, $instance ) {

            $title            = apply_filters( 'widget_title', $instance['title'] );
            $type             = empty( $instance['type'] ) ? 'default'       : $instance['type'];
            $title_font_size  = empty( $instance['title_font_size'] ) ? '16' : $instance['title_font_size'];

            $postsnum = ( ! empty( $instance['postsnum'] ) ) ? absint( $instance['postsnum'] ) : 5;
            if ( ! $postsnum ) {
                $postsnum = 5;
            }

            $show_date       = isset( $instance['show_date'] )       ? $instance['show_date']       : false;
            $posts_separator = isset( $instance['posts_separator'] ) ? $instance['posts_separator'] : false;

            // Separator Class
            $separator_class = '';
            if( $posts_separator ) {
                $separator_class = ' posts-separator';
            }

            // First Large Class 
            $first_large = '';
            $first_large_sep = '';

            // Thumbnail Size
            $bst_thumb_size = 'thumbnail';
            if( 'large' == $type || 'first-large' == $type ) {
                $bst_thumb_size = 'medium_large';
            }

            // Filters the arguments for the Recent Posts widget.
            $r = new WP_Query( apply_filters( 'bstone_widget_posts_args', array(
                'posts_per_page'      => $postsnum,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            ), $instance ) );
    
            if ( ! $r->have_posts() ) {
                return;
            }
            
            // Widget ID
            $bst_widget_id = 'bst-'.uniqid();
            ?>
            <aside class="widget bst-popular-posts-widget bst-popular-posts-<?php echo $type; ?>" id="<?php echo $bst_widget_id; ?>">
            <?php
            
                // before and after widget arguments are defined by themes
                echo ( isset( $before_widget ) ? $before_widget : '' );

                if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];
                ?>

                <ul class="bst-posts-widget-<?php echo $type.$separator_class; ?>">
                    <?php                    
                        $posts_count = 0;
                        foreach ( $r->posts as $recent_post ) :
                            
                            $posts_count++;

                            // Class for first large style
                            if( 1 == $posts_count && 'first-large' == $type ) {
                                $first_large_sep = 'bst-sep-fl';
                                $first_large = ' class="first-large"';
                            } else {
                                $first_large_sep = '';
                                $first_large = ' class="normal-thumbnail"';
                            }

                            if( 1 < $posts_count && 'first-large' == $type ) {
                                $bst_thumb_size = 'thumbnail';
                            }
                    ?>
                        <?php
                        $post_title = get_the_title( $recent_post->ID );
                        $title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)', 'bstone-light' );
                        ?>
                        <li<?php echo $first_large; ?>>
                            <?php
                                if ( ( 'default' == $type || 'large' == $type || 'first-large' == $type ) && ( has_post_thumbnail( $recent_post->ID ) ) ):
                                    echo '<a href="'.get_the_permalink( $recent_post->ID ).'" class="bst-widget-thumb">';
                                    echo get_the_post_thumbnail( $recent_post->ID, $bst_thumb_size );
                                    echo '</a>';
                                endif;
                            ?>
                            <span class="bst-widget-post-cnt">
                                <a href="<?php the_permalink( $recent_post->ID ); ?>" class="entry-title"><?php echo $title ; ?></a>
                                <?php if ( $show_date ) : ?>
                                    <span class="post-date entry-meta"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
                                <?php endif; ?>
                                <span class="clear"></span>
                            </span>
                        </li>

                        <?php
                          if( $posts_count < ( count( $r->posts ) ) ) {
                              echo '<li class="bst-post-list-separator '.$first_large_sep.'"> </li>';
                          }
                        ?>
                    <?php endforeach; ?>
                </ul>

                <?php
                    echo ( isset( $after_widget ) ? $after_widget : '' );
                ?>

                <style type="text/css">
                    #<?php echo $bst_widget_id; ?> li .entry-title {
                        font-size: <?php echo $title_font_size; ?>px;
                    }

                    #<?php echo $bst_widget_id; ?> li.first-large .entry-title {
                        font-size: <?php echo $title_font_size+2; ?>px;
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
                'postsnum'           => '',
                'show_date'          => false,
                'posts_separator'    => false,
                'title_font_size'    => '',
            );

            $instance = wp_parse_args( ( array ) $instance, $defaults );

            $title              = esc_attr( $instance['title'] );
            $type               = esc_attr( $instance['type'] );
            $postsnum           = esc_attr( $instance['postsnum'] );
            $show_date          = esc_attr( $instance['show_date'] );
            $posts_separator    = esc_attr( $instance['posts_separator'] );
            $title_font_size    = esc_attr( $instance['title_font_size'] );
            
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
                        
                        <option value='large' <?php echo ( 'large' == $type )?'selected':''; ?>>
                            <?php _e( 'Large Thumbnail', 'bstone-light' ); ?>
                        </option>
                        
                        <option value='first-large' <?php echo ( 'first-large' == $type )?'selected':''; ?>>
                            <?php _e( 'Only 1st Large', 'bstone-light' ); ?>
                        </option>
                        
                        <option value='list-no-image' <?php echo ( 'list-no-image' == $type )?'selected':''; ?>>
                            <?php _e( 'List Without Thumbnail', 'bstone-light' ); ?>
                        </option>
                        
                    </select>                
                </label>
            </p>

            <p><label for="<?php echo $this->get_field_id( 'postsnum' ); ?>"><?php _e( 'Number of posts to show:', 'bstone-light' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'postsnum' ); ?>" name="<?php echo $this->get_field_name( 'postsnum' ); ?>" type="number" step="1" min="1" value="<?php echo $postsnum; ?>" size="3" /></p>

            <p>
                <label for="<?php echo $this->get_field_id( 'title_font_size' ); ?>"><?php _e( 'Post Title Font Size:', 'bstone-light' ); ?></label> 
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'title_font_size' ); ?>" name="<?php echo $this->get_field_name( 'title_font_size' ); ?>" type="number" value="<?php echo esc_attr( $title_font_size ); ?>" />
            </p>

            <p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'bstone-light' ); ?></label></p>

            <p><input class="checkbox" type="checkbox"<?php checked( $posts_separator ); ?> id="<?php echo $this->get_field_id( 'posts_separator' ); ?>" name="<?php echo $this->get_field_name( 'posts_separator' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'posts_separator' ); ?>"><?php _e( 'Display separator?', 'bstone-light' ); ?></label></p>

            <?php
        }
            
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title']           = ( ! empty( $new_instance['title'] ) )           ? strip_tags( $new_instance['title'] )           : '';
            $instance['type']            = ( ! empty( $new_instance['type'] ) )            ? strip_tags( $new_instance['type'] )            : '';
            $instance['postsnum']        = ( ! empty( $new_instance['postsnum'] ) )        ? strip_tags( $new_instance['postsnum'] )        : '';
            $instance['show_date']       = isset( $new_instance['show_date'] )             ? (bool) $new_instance['show_date']              : false;
            $instance['posts_separator'] = isset( $new_instance['posts_separator'] )       ? (bool) $new_instance['posts_separator']        : false;
            $instance['title_font_size'] = ( ! empty( $new_instance['title_font_size'] ) ) ? strip_tags( $new_instance['title_font_size'] ) : '';
            return $instance;
        }
    }
}