<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class WooCommerce_Promoted_Product_Settings
 *
 * Class used to add settings to the WooCommerce settings page & Product post type.
 *
 * @since 1.0.0
 */
class WooCommerce_Promoted_Product_Settings {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

        // Hook to add a new WC section
		add_filter( 'woocommerce_get_sections_products', array( $this, 'add_section' ), 20 );
        // Hook to add settings to the new section
        add_filter( 'woocommerce_get_settings_products', array( $this, 'add_settings' ), 10, 2 );

        // Hook for custom Promoted Product title & edit link display
        add_action( 'woocommerce_admin_field_promoted_product_display', array( $this, 'promoted_product_display' ), 10, 2 );

        // Product cpt
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_custom_meta_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_fields_data' ) );

        // The CRON callback to delete the option of active product.
        add_action( 'unset_active_product_schedule', array( $this, 'unset_active_product' ) );

        // Enqueue edit js.
        wp_enqueue_script( 'wc-promoted-product-admin', WC_PROMOTED_PRODUCT_URL . 'assets/js/admin-edit.js', array( 'jquery' ), WC_PROMOTED_PRODUCT_VERSION, true );
	}


	/**
	 * Add section to WooCommerce->Products sections
	 *
	 * @param $sections
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add_section( $sections ): array {

        $sections['promoted_product'] = __( 'Promoted Product', 'wc-promoted-product' );

        return $sections;
	}

	/**
	 * Add settings to WooCommerce->Products settings section.
	 *
	 * @param $settings
	 *
	 * @param $current_section
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add_settings( $settings, $current_section ): array {

        if( 'promoted_product' == $current_section ) {
            $settings =  array(
                array(
                    'name' => __( 'Promoted product settings', 'wc-promoted-product' ),
                    'type' => 'title',
                    'desc' => __( 'Set the display settings for the promoted product.', 'wc-promoted-product' ),
                    'id' => 'wc_promo_product_head'
                ),
                array(
                    'name' => __( 'Product title', 'wc-promoted-product' ),
                    'type' => 'text',
                    'desc' => __( 'The title of the promoted product (e.g. "FLASH SALE:")', 'wc-promoted-product'),
                    'desc_tip' => true,
                    'id' => 'wc_promo_product_title'
                ),
                array(
                    'name' => __( 'Background color', 'wc-promoted-product' ),
                    'type' => 'color',
                    'desc' => __( 'Background color of the promoted product display.', 'wc-promoted-product'),
                    'desc_tip' => true,
                    'id'    => 'wc_promo_product_bg_color',
                    'default' => '#dedede',
                ),
                array(
                    'name' => __( 'Text color', 'wc-promoted-product' ),
                    'type' => 'color',
                    'desc' => __( 'Color of the text in the notice', 'wc-promoted-product'),
                    'desc_tip' => true,
                    'id'    => 'wc_promo_product_txt_color',
                    'default' => '#000',
                ),
                array(
                    'name' => __( 'Active promoted product', 'wc-promoted-product' ),
                    'type' => 'promoted_product_display',
                    'id'   => 'wc_promo_product_display',
                ),
                
                array( 'type' => 'sectionend', 'id' => 'wc_promo_product_head' ),
            );
        }
        return $settings;
	}


	/**
	 * Render product title display custom field.
	 *
	 * @param $type
	 *
	 * @param $value
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function promoted_product_display( $value ) {

        if( isset( $value['field_name'] ) && 'wc_promo_product_display' == $value['field_name'] ) {
            $active = get_option( 'wc_promo_product_active', false );

            ?><tr valign="top"<?php echo $value['row_class'] ? ' class="' . esc_attr( $value['row_class'] ) . '"' : '' ?>">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                    <?php 
                        if( $active && 0 != absint( $active ) ){
                            $product = wc_get_product( absint( $active ) );
                            echo '<p>' . esc_html( $product->get_title() ) . '</p><a href="' . esc_url( add_query_arg( array( 'post' => $product->get_id(), 'action' => 'edit' ), admin_url( 'post.php' ) ) ) . '">' . esc_html__( 'Edit Product', 'wc-promoted-product' ) . '</a>';
                        }else{
                            echo '<p>' . esc_html__( 'No active promoted product.', 'wc-promoted-product' ) . '</p>';
                        }
                    ?>
                </td>
            </tr>
            <?php
        }
	}

	/**
	 * Adds custom meta fields to the WooCommerce product editor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
    public function add_custom_meta_fields() {
        global $post;
        
        //Getting custom meta title.
        $title = get_post_meta( $post->ID, 'wc_promo_product_custom_title', true );
        if( ! $title || '' === $title ){
            // There must always be a title.
            $title = get_the_title();
        }

        $active = absint( get_option( 'wc_promo_product_active', 0 ) ) === $post->ID ? 'yes' : 'no';

        woocommerce_wp_checkbox(
            array(
                'id'          => 'wc_promo_product_active',
                'label'       => __('Promote this product', 'wc-promoted-product'),
                'description' => __('Activate this product as promoted when checked.', 'wc-promoted-product'),
                'desc_tip'    => 'true',
                'value'       => $active,
            )
        );
        woocommerce_wp_text_input(
            array(
                'id'          => 'wc_promo_product_custom_title',
                'label'       => __('Custom Title', 'wc-promoted-product'),
                'placeholder' => __('Enter custom title', 'wc-promoted-product'),
                'description' => __('Enter a custom title to be shown instead of the product title.', 'wc-promoted-product'),
                'desc_tip'    => 'true',
                'value'       => esc_attr( $title ),
            )
        );

        woocommerce_wp_checkbox(
            array(
                'id'          => 'wc_promo_product_expiration',
                'label'       => __('Enable promotion expiration', 'wc-promoted-product'),
                'description' => __('Check to set an expiration date and time for this product.', 'wc-promoted-product'),
                'desc_tip'    => 'true',
            )
        );
    
        echo '<div class="wc_promo_product_expiration_date_time">';
            woocommerce_wp_text_input(
                array(
                    'id'          => 'wc_promo_product_expiration_date_time',
                    'label'       => __('Expiration Date and Time', 'wc-promoted-product'),
                    'placeholder' => __('Enter expiration date and time', 'wc-promoted-product'),
                    'type'        => 'datetime-local',
                    'custom_attributes' => array(
                        'min'  => '0',
                    ),
                )
            );
        echo '</div>';
    }

    /**
     * Saves custom fields data when the WooCommerce product is saved.
     *
     * @param int $product_id The product ID.
     * 
     * @return void
	 * @since 1.0.0
     */
    public function save_custom_fields_data( $product_id ) {

        // Verify nonce
        if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) {
            // Nonce verification failed
            return;
        }

        // Check if product promotion is set to active.
        if( isset( $_POST['wc_promo_product_active'] ) ){
            // Save this as WP option as it is site-unique.
            update_option( 'wc_promo_product_active', $product_id );

            // Schedule the deletion event if needed.
            if( isset( $_POST['wc_promo_product_expiration'] ) && isset( $_POST['wc_promo_product_expiration_date_time'] ) ){

                // Delete previous scheduled event.
                WC()->queue()->cancel( 'unset_active_product_schedule' );

                // Transform date in timestamp.
                $timestamp  = strtotime( sanitize_text_field( wp_unslash ( $_POST['wc_promo_product_expiration_date_time'] ) ) );

                // Offset universal time difference.
                $gmt_offset = get_option( 'gmt_offset' );

                $timestamp = $timestamp - ( (int)$gmt_offset * 60 * 60 );

                // Schedule deletion event.
                WC()->queue()->schedule_single( $timestamp, 'unset_active_product_schedule' );
            }else{
                // The user may have changed it's mind. Let's unschedule to be sure.
                WC()->queue()->cancel( 'unset_active_product_schedule' );
            }
        }else{
            // Delete the option, the user may have unchecked & saved.
            delete_option( 'wc_promo_product_active' );

            // Unschedule as it is not needed. The option was deleted.
            WC()->queue()->cancel( 'unset_active_product_schedule' );
        }
    
        $custom_title = isset( $_POST['wc_promo_product_custom_title'] ) ? sanitize_text_field( wp_unslash( $_POST['wc_promo_product_custom_title'] ) ) : '';
        update_post_meta( $product_id, 'wc_promo_product_custom_title', $custom_title );

        $set_expiration = isset( $_POST['wc_promo_product_expiration'] ) ? 'yes' : 'no';
        update_post_meta( $product_id, 'wc_promo_product_expiration', $set_expiration );
    
        $expiration_date_time = isset( $_POST['wc_promo_product_expiration_date_time'] ) ? sanitize_text_field( wp_unslash ( $_POST['wc_promo_product_expiration_date_time'] ) ) : '';
        update_post_meta( $product_id, 'wc_promo_product_expiration_date_time', $expiration_date_time );
    }

    /**
     * Unsets the active product promotion.
     * 
     * @return void
	 * @since 1.0.0
     */
    public function unset_active_product() {
        delete_option( 'wc_promo_product_active' );
    }
}
