<?php namespace BEAPI\Maintenance_Mode;

/**
 * The purpose of the main class is to init all the plugin base code like :
 *  - Taxonomies
 *  - Post types
 *  - Shortcodes
 *  - Posts to posts relations etc.
 *  - Loading the text domain
 *
 * Class Main
 * @package BEA\Maintenance_Mode
 */
class Main {
	use Singleton;

	protected function init() {
		if ( function_exists( 'add_filter' ) ):
			add_action( 'get_header', 'maintenance_content' );
			add_feed_actions();
		else:
			// Prevent direct invocation by user agents.
			die( 'Get off my lawn!' );
		endif;

		if ( ! function_exists( 'maintenance_feed' ) ):
			function maintenance_feed() {
				if ( ! is_user_logged_in() ) {
					status_header( 410 );
					die( '<?xml version="1.0" encoding="UTF-8"?>' . '<status>Access Denied/Forbidden.</status>' );
				}
			}
		endif;

		if ( ! function_exists( 'add_feed_actions' ) ):
			function add_feed_actions() {
				$feeds = array( 'rdf', 'rss', 'rss2', 'atom' );
				foreach ( $feeds as $feed ) {
					add_action( 'do_feed_' . $feed, 'maintenance_feed', 1, 1 );
				}
			}
		endif;

		if ( true === is_allowed_ip() ) {
			return false;
		}

		if ( ! function_exists( 'maintenance_content' ) ):
			function maintenance_content() {
				if ( ! is_user_logged_in() ) {
					$current_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
					status_header( 410 );
					$page = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>Service unavailable.</title>
<style>
body {
    background-color: #e0dcdc;
    color: #444;
    font-family: "Open Sans",sans-serif;
    font-size: 13px;
    line-height: 1.4em;
}
body > div {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 0 5px #888;
    margin: 3em auto;
    padding: 2em;
    text-align: center;
    width: 20%;
    min-width: 20em;
}
</style>
</head>
<body>
<div>
<h1>Access Denied/Forbidden.</h1>
<p>Your IP Address : {$current_ip}</p>
<p>Please contact your webmaster&hellip;</p>
</div>
</body>
</html>
EOT;
					die( $page );
				}
			}
		endif;
	}

	function is_allowed_ip() {
		// Get user IP
		$current_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

		// Set flag to false
		$is_allowed_ip = false;

		// No IP file
		if ( ! is_file( dirname( __FILE__ ) . '/whitelist.txt' ) ) {
			return true;
		}

		// Get IP from conf webserver
		$allowed_ips = file( dirname( __FILE__ ) . '/whitelist.txt' );

		// No allowed IP ? Allow all IP !
		if ( empty( $allowed_ips ) ) {
			return false;
		}

		// Trim array
		$allowed_ips = array_map( 'trim', $allowed_ips );

		// Loop on each IP
		foreach ( $allowed_ips as $allowed_ip ) {
			// Skip lines starting with # ! Comments !
			if ( '#' === substr( $allowed_ip, 0, 1 ) || 0 === strlen( $allowed_ip ) ) {
				continue;
			}

			// IP is allowed ? Make sure we don't depend on the representation by justifying numbers with 3 decimals.
			$current_ip = preg_replace_callback( '/(\d+)/', 'maintenance_replace_ip', $current_ip );
			$allowed_ip = preg_replace_callback( '/(\d+)/', 'maintenance_replace_ip', $allowed_ip );

			if ( 0 === strpos( $current_ip, $allowed_ip ) ) {
				$is_allowed_ip = true;
			}
		}

		return $is_allowed_ip;
	}

	function maintenance_replace_ip( $matches ) {
		return sprintf( "%03d", $matches[1] );
	}
}