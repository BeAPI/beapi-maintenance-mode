<?php

namespace BEAPI\Maintenance_Mode;

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
	 * @return bool
	 * @author Maxime CULEA
	 *
	 */
	public static function is_maintenance_mode() {
		$is_maintenance_mode = true;

		if ( self::is_user_authenticated() ) {
			$is_maintenance_mode = false;
		}

		if ( self::is_allowed_ip() ) {
			$is_maintenance_mode = false;
		}

		if ( self::is_ms_activate() ) {
			$is_maintenance_mode = false;
		}

		return apply_filters( 'beapi.maintenance_mode.is_maintenance_mode', $is_maintenance_mode );
	}

	/**
	 * Check if the current user is authenticated.
	 * This method handles both regular requests and REST API requests.
	 *
	 * @return bool
	 * @since  2.1.1
	 */
	public static function is_user_authenticated() {
		// For regular requests, check if user is logged in.
		if ( is_user_logged_in() ) {
			return true;
		}

		// For REST API requests, we need to check authentication differently
		// because is_user_logged_in() may not work correctly at this point.
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			return false;
		}

		// Try to get current user (this works even for REST API).
		$user = wp_get_current_user();
		if ( $user && $user->ID > 0 ) {
			return true;
		}

		// Check if there's a valid authentication cookie.
		// This is useful when cookies are sent but not yet processed.
		if ( ! defined( 'LOGGED_IN_COOKIE' ) || empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
			return false;
		}

		$cookie = wp_parse_auth_cookie( $_COOKIE[ LOGGED_IN_COOKIE ], 'logged_in' );
		if ( empty( $cookie['username'] ) || empty( $cookie['expiration'] ) ) {
			return false;
		}

		// Verify the cookie is still valid by checking expiration.
		if ( $cookie['expiration'] <= time() ) {
			return false;
		}

		// Verify the user exists.
		$user = get_user_by( 'login', $cookie['username'] );
		if ( ! $user || $user->ID <= 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the current IP is in whitelist
	 *
	 * @return bool
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 */
	public static function is_allowed_ip() {
		/**
		 * Allow to add/remove custom ips
		 *
		 * @params array $whitelist_ips : Array of allowed ips
		 *
		 * @return array
		 * @author Maxime CULEA
		 * @since  1.0.0
		 *
		 */
		$whitelist_ips = apply_filters( 'beapi.maintenance_mode.whitelist_ips', [] );
		if ( empty( $whitelist_ips ) ) { // No whitelist, then nobody is allowed
			return false;
		}

		// Get user IP
		$current_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		if ( empty( $current_ip ) ) {
			// No current ip set to check against
			return false;
		}
		$current_ip = preg_replace_callback( '/(\d+)/', [ __CLASS__, 'maintenance_replace_ip' ], $current_ip );

		// Loop on each whitelist IP
		foreach ( $whitelist_ips as $allowed_ip ) {
			$allowed_ip = preg_replace_callback( '/(\d+)/', [ __CLASS__, 'maintenance_replace_ip' ], $allowed_ip );
			// Not strict mode check because user ip and whitelist ips could not be the same type
			if ( $current_ip === $allowed_ip ) {
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
	 * @return bool
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
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
	 * @return string
	 * @author Nicolas Juen
	 * @since  1.0.0
	 *
	 */
	private static function maintenance_replace_ip( $matches ) {
		return sprintf( '%03d', $matches[1] );
	}

	/**
	 * Get the maintenance template path
	 *
	 * @return string
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 */
	public static function get_template_path() {
		$default = BEAPI_MAINTENANCE_MODE_DIR . 'templates/maintenance.php';

		/**
		 * Filter maintenance template path to add a custom one
		 *
		 * @params string $default : The path to the custom template
		 *
		 * @return array
		 * @author Maxime CULEA
		 * @since  1.0.0
		 *
		 */
		$template = apply_filters( 'beapi.maintenance_mode.template.path', $default );
		if ( empty( $template ) || ! is_file( $template ) ) {
			$template = $default;
		}

		return $template;
	}
}
