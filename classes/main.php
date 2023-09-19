<?php

namespace BEAPI\Maintenance_Mode;

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

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
		add_action( 'template_redirect', [ $this, 'maintenance_content' ] );
		add_filter( 'status_header', [ $this, 'maintenance_header' ], 10, 4 );

		/**
		 * RSS
		 */
		add_action( 'do_feed_rdf', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_rss', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_rss2', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_atom', [ $this, 'maintenance_feed' ], 1 );

		/**
		 * REST
		 */
		add_action( 'rest_authentication_errors', [ $this, 'disable_rest' ], 1 );
	}

	/**
	 * Change the header if maintenance mode
	 *
	 * @param $status_header
	 * @param $code
	 * @param $description
	 * @param $protocol
	 *
	 * @return string
	 * @since  1.0.0
	 *
	 * @author Maxime CULEA
	 */
	public function maintenance_header( $status_header, $code, $description, $protocol ) {

		if ( ! Helpers::is_maintenance_mode() ) {
			return $status_header;
		}

		return "$protocol 503 Service Unavailable";
	}

	/**
	 * Change headers and content for feeds if maintenance mode
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 */
	public function maintenance_feed() {
		if ( ! Helpers::is_maintenance_mode() ) {
			return;
		}

		status_header( 503 );
		die( '<?xml version="1.0" encoding="UTF-8"?><status>Access Denied/Forbidden.</status>' );
	}

	/**
	 * Checks for a current route being requested, and processes the allowlist
	 *
	 * @param $access
	 *
	 * @return WP_Error|null|boolean
	 */
	public function disable_rest( $access ) {
		if ( ! Helpers::is_maintenance_mode() ) {
			return $access;
		}

		return new \WP_Error( 'rest_cannot_access', __( 'Maintenance is active, REST API disabled.', 'beapi-maintenance-mode-mode' ), [ 'status' => 503 ] );
	}


	/**
	 * Display the maintenance mode template if maintenance mode
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 */
	public function maintenance_content() {
		if ( ! Helpers::is_maintenance_mode() ) {
			return;
		}

		status_header( 503 );
		$page = file_get_contents( Helpers::get_template_path() );
		die( $page ); // phpcs:ignore
	}
}
