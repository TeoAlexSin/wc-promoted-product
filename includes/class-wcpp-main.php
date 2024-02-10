<?php

namespace wcpp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class WooCommerce_Promoted_Product
 */
class WooCommerce_Promoted_Product {
	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Check for WooCommerce.
		if( class_exists( 'WooCommerce' ) ){
			// Load required files for admin.
			$this->load_admin_files();
			// Load required files for frontend.
			$this->load_frontend_files();
		}else{
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 7 );
		}
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The WooCommerce_Promoted_Product object.
	 * @since 1.0.0
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WooCommerce_Promoted_Product ) ) {
			self::$instance = new WooCommerce_Promoted_Product();
		}

		return self::$instance;
	}

	/**
	 * Load admin files.
	 *
	 * @since 1.0.0
	 */
	private function load_admin_files() {
		// Load admin files.
		if ( is_admin() ) {
			// Load settings class.
			new \wcpp\admin\WooCommerce_Promoted_Product_Settings();
		}
	}

	/**
	 * Load frontend files.
	 *
	 * @since 1.0.0
	 */
	private function load_frontend_files() {
		// Load frontend files.
		if ( ! is_admin() ) {
			// Load the frontend class.
			new \wcpp\front\WooCommerce_Promoted_Product_Front();
		}
	}

		/**
	 * Display admin notices
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {
		$current_screen = get_current_screen();

		// Only show notices to admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p>
				<?php
				printf(
					/* translators: %1$s: WooCommerce - Promoted Product, %2$s: WooCommerce */
					esc_html__( '%1$s requires %2$s plugin to be installed and activated.', 'wc-promoted-product' ),
					'<strong>' . esc_html__( 'WooCommerce - Promoted Product', 'wc-promoted-product' ) . '</strong>',
					'<strong>' . esc_html__( 'WooCommerce', 'wc-promoted-product' ) . '</strong>'
				);
				?>
			</p>
		</div>
		<?php
	}
}
