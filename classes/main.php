<?php namespace BEAPI\Maintenance_Mode;

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
		add_action( 'get_header', [ $this, 'maintenance_content' ] );
		add_filter( 'status_header', [ $this, 'maintenance_header' ], 10, 4 );

		add_action( 'do_feed_rdf', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_rss', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_rss2', [ $this, 'maintenance_feed' ], 1 );
		add_action( 'do_feed_atom', [ $this, 'maintenance_feed' ], 1 );
	}

	/**
	 * Change the header if maintenance mode
	 *
	 * @param $status_header
	 * @param $header
	 * @param $text
	 * @param $protocol
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function maintenance_header( $status_header, $header, $text, $protocol ) {
		if ( ! Helpers::is_maintenance_mode() ) {
			return $protocol;
		}

		return "$protocol 503 Service Unavailable";
	}

	/**
	 * Change headers and content for feeds if maintenance mode
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 */
	function maintenance_feed() {
		if ( ! Helpers::is_maintenance_mode() ) {
			return;
		}

		status_header( 503 );
		die( '<?xml version="1.0" encoding="UTF-8"?><status>Access Denied/Forbidden.</status>' );
	}

	/**
	 * Display the maintenance mode template if maintenance mode
	 *
	 * @author Maxime CULEA
	 * @since  1.0.0
	 */
	function maintenance_content() {
		if ( ! Helpers::is_maintenance_mode() ) {
			return;
		}

		status_header( 503 );
		$page = file_get_contents( Helpers::get_template_path() );
		die( $page );
	}
}