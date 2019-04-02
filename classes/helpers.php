<?php namespace BEAPI\Maintenance_Mode;

class Helpers {

	/**
	 * Tells when maintenance mode needs to be activated or not.
	 *
	 * There are multiple ways to check if the current content is allowed or not to be displayed, if not it's maintenance mode.
	 *
	 * Reasons for not maintenance mode :
	 * - user logged in
	 * - current ip is from whitelist
	 * - it is multisite activation process
	 *
	 * @author Maxime CULEA
	 *
	 * @return bool
	 */
	public static function is_maintenance_mode() {
		$is_maintenance_mode = ! is_user_logged_in() && ! self::is_allowed_ip() && ! self::is_ms_activate();
		return apply_filters( 'beapi.maintenance_mode.is_maintenance_mode', $is_maintenance_mode );
	}

	/**
	 * Check if the current IP is in whitelist
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public static function is_allowed_ip() {
		/**
		 * Allow to add/remove custom ips
		 *
		 * @params array $whitelist_ips : Array of allowed ips
		 *
		 * @author Maxime CULEA
		 * @since  1.0.0
		 *
		 * @return array
		 */
		$whitelist_ips = apply_filters( 'beapi.maintenance_mode.whitelist_ips', [] );
		if ( empty( $whitelist_ips ) ) {
			// No whitelist, then everybody is allowed
			return true;
		}

		// Get user IP
		$current_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		if ( empty( $current_ip ) ) {
			// No current ip set to check against
			return false;
		}
		$current_ip = preg_replace_callback( '/(\d+)/', [ __SELF__, 'maintenance_replace_ip' ], $current_ip );

		// Loop on each whitelist IP
		foreach ( $whitelist_ips as $allowed_ip ) {
			$allowed_ip = preg_replace_callback( '/(\d+)/', [ __SELF__, 'maintenance_replace_ip' ], $allowed_ip );
			// Not strict mode check because user ip and whitelist ips could not be the same type
			if ( $current_ip == $allowed_ip ) {
				// We found a match into the whitelist
				return true;
			}
		}

		// No matching ip into the whitelist
		return false;
	}

	/**
	 * Check if during multisite process to avoid not maintenance mode or not
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public static function is_ms_activate() {
		if ( empty( $_SERVER['SCRIPT_NAME'] ) ) {
			return false;
		}

		return in_array( ltrim( $_SERVER['SCRIPT_NAME'], '/' ), [ 'wp-login.php', 'wp-activate.php' ] );
	}

	/**
	 * Make sure we don't depend on the representation by justifying numbers with 3 decimals.
	 *
	 * @param $matches
	 *
	 * @author Nicolas Juen
	 * @since  1.0.0
	 *
	 * @return string
	 */
	private function maintenance_replace_ip( $matches ) {
		return sprintf( "%03d", $matches[1] );
	}

	/**
	 * Get the maintenance template path
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 * @return string
	 */
	static function get_template_path() {
		$default = BEAPI_MAINTENANCE_MODE_DIR . 'templates/maintenance.php';

		/**
		 * Filter maintenance template path to add a custom one
		 *
		 * @params string $whitelist_ips : The path to the custom template
		 *
		 * @author Maxime CULEA
		 * @since  1.0.0
		 *
		 * @return array
		 */
		$template = apply_filters( 'beapi.maintenance_mode.template.path', $default );
		if ( empty( $template ) || ! file_exists( $template ) ) {
			$template = $default;
		}

		return $template;
	}
}
