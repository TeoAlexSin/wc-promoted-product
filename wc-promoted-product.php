<?php
/*
	* Plugin Name:  WooCommerce - Promoted Product
	* Description:  Displays a promoted product on every page.
	* Version:      1.0.0
	* Author:       Teodorescu Alexandru
	* Requires PHP: 5.6
	* Text Domain:  wc-promoted-product
	* Domain Path:  /languages
*/

define( 'WC_PROMOTED_PRODUCT_VERSION', '1.0.0' );
define( 'WC_PROMOTED_PRODUCT_FILE', __FILE__ );
define( 'WC_PROMOTED_PRODUCT_PATH', plugin_dir_path( WC_PROMOTED_PRODUCT_FILE ) );
define( 'WC_PROMOTED_PRODUCT_URL', plugin_dir_url( WC_PROMOTED_PRODUCT_FILE ) );

add_action( 'plugins_loaded', 'wc_promo_product_load' );

function wc_promo_product_load() {
	// require autoloader
    require_once dirname( WC_PROMOTED_PRODUCT_FILE ) . '/vendor/autoload.php';
	\wcpp\WooCommerce_Promoted_Product::get_instance();
}
