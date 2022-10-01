<?php
/**
 * Bstone Widget - WooCommerce Mini Cart
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.2
 */

if ( ! class_exists( 'bstone_light_widget_woo_mini_cart' ) && class_exists( 'WooCommerce' ) ) {
    /***
     * Latest Articles
     */
    class bstone_light_widget_woo_mini_cart extends WP_Widget {

        function __construct() {
            parent::__construct(
                
                // Base ID of widget
                'bstone_light_widget_woo_mini_cart',

                // Widget name will appear in UI
                __('Bstone Mini Cart', 'bstone-light'),

                // Widget description
                array( 'description' => __( 'Display woocommerce mini cart.', 'bstone-light' ) )
            );
        }

        // Widget Frontend
        public function widget( $args, $instance ) {

            if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
            }
            
            $cart_menu_classes = apply_filters( 'bstone_cart_in_menu_class', array( 'bst-menu-cart-with-border' ) );
            ?>
            <aside class="widget bst-mini-cart-widget">
                <div id="bst-site-header-cart" class="bst-site-header-cart <?php echo esc_html( implode( ' ', $cart_menu_classes ) ); ?>">
                    <div class="bst-site-header-cart-li <?php echo esc_attr( $class ); ?>">
                        <?php $this->bstone_get_cart_link(); ?>
                    </div>
                    <div class="bst-site-header-cart-data">
                        <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                    </div>
                </div>
            </aside>
            <?php
        }

		/**
		 * Cart Link
		 * Displayed a link to the cart including the number of items present and the cart total
		 *
		 * @return void
		 * @since  1.0.2
		 */
		public function bstone_get_cart_link() {

			$view_shopping_cart = apply_filters( 'bstone_woo_view_shopping_cart_title', __( 'View your shopping cart', 'bstone-light' ) );
			?>
			<a class="cart-container" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr( $view_shopping_cart ); ?>">

						<?php
						do_action( 'bstone_woo_header_cart_icons_before' );

						if ( apply_filters( 'bstone_woo_default_header_cart_icon', true ) ) {
						?>
							<div class="bst-cart-menu-wrap">
								<span class="count"> 
									<?php
									if ( apply_filters( 'bstone_woo_header_cart_total', true ) && null != WC()->cart ) {
										echo WC()->cart->get_cart_contents_count();
									}
									?>
								</span>
							</div>
						<?php
						}

						do_action( 'bstone_woo_header_cart_icons_after' );

						?>
			</a>
		<?php
		}

    }
}