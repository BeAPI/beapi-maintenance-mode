<?php namespace BEAPI\Maintenance_Mode;

class Compatibility {
	/**
	 * admin_init hook callback
	 *
	 * @since 0.1
	 */
	public static function admin_init() {
		// Not on ajax
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Check activation
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// Load the textdomain
		load_plugin_textdomain( 'beapi-maintenance-mode', false, BEAPI_MAINTENANCE_MODE_PLUGIN_DIRNAME . '/languages' );

		// translators: the PHP version
		trigger_error( sprintf( esc_html__( 'Plugin Boilerplate requires PHP version %s or greater to be activated.', 'beapi-maintenance-mode' ), esc_html( BEAPI_MAINTENANCE_MODE_MIN_PHP_VERSION ) ) );

		// Deactive self
		deactivate_plugins( BEAPI_MAINTENANCE_MODE_DIR . 'beapi-maintenance-mode.php' );

		unset( $_GET['activate'] );

		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	/**
	 * Notify the user about the incompatibility issue.
	 */
	public static function admin_notices() {
		echo '<div class="notice error is-dismissible">';
		// translators: first is the PHP version needed, second is the current PHP version
		echo '<p>' . esc_html( sprintf( __( 'Plugin Boilerplate require PHP version %1$s or greater to be activated. Your server is currently running PHP version %2$s.', 'beapi-maintenance-mode' ), BEAPI_MAINTENANCE_MODE_MIN_PHP_VERSION, PHP_VERSION ) ) . '</p>';
		echo '</div>';
	}
}
