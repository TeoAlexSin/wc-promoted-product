<?php

namespace wcpp\front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class WooCommerce_Promoted_Product_Front
 *
 * Class used to display the promoted product to the front-end.
 *
 * @since 1.0.0
 */
class WooCommerce_Promoted_Product_Front {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        // Enqueue view js.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ) );
	}


    /**
     * Render the promoted product.
     *
     * Retrieves information about the promoted product, such as title, custom title, tags, and styling options,
     * and generates the HTML markup for displaying a full-width promotional bar.
     *
     * @since 1.0.0
     *
     * @return string Empty string if no promoted product is active; otherwise, the HTML markup for the promotional bar.
     */
    public function render_promoted_product() {
        $product_id = get_option( 'wc_promo_product_active', false );

        if( $product_id ){
            // Get the product title
            $product_title = get_the_title( $product_id );

            $custom_title = get_post_meta( $product_id, 'wc_promo_product_custom_title', true );
            $product_tag  = get_option( 'wc_promo_product_title', false );
            $bg_color     = get_option( 'wc_promo_product_bg_color', '#dedede' );;
            $text_color   = get_option( 'wc_promo_product_txt_color', '#000000' );

            ob_start();
            // Display the full-width div
            echo '<a href="'. esc_url( get_permalink( $product_id ) ) .'" />';
                echo sprintf( '<div class="full-width-promo-bar" style="background-color: %s;color: %s;">',  esc_attr( $bg_color), esc_attr( $text_color) );
                    if( $product_tag && '' !== $product_tag ){
                        echo sprintf( '<span class="promoted-title">%s:</span>', esc_html( $product_tag ) );
                    }
                    echo '<span class="product-titles">';
                        // Display either the custom title or the original product title
                        echo esc_html( $custom_title ? $custom_title : $product_title );
                    echo '</span>';
                echo '</div>';
            echo '</a>';

            return ob_get_clean();
        }
        return '';
    }

    /**
     * Enqueues the front-end needed scripts.
     * 
     * @return void
	 * @since 1.0.0
     */
    public function enqueue_front_scripts() {
        wp_enqueue_script( 'wc-promoted-product-front', WC_PROMOTED_PRODUCT_URL . 'assets/js/front-view.js', array( 'jquery' ), WC_PROMOTED_PRODUCT_VERSION, true );
        wp_add_inline_script( 'wc-promoted-product-front', "const wcpp_template = '" . addslashes( $this->render_promoted_product() ) . "'" , 'before' );
    }

}
