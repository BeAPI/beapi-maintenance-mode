<?php
/*
 Plugin Name: BEAPI - Maintenance Mode
 Version: 1.0.5
 Plugin URI: https://github.com/BeAPI/beapi-maintenance-mode
 Description: Puts your WordPress site into maintenance mode.
 Author: Be API Technical team
 Author URI: https://beapi.fr
 Domain Path: languages
 Text Domain: beapi-maintenance-mode-mode
 Contributors: Maxime Culea, Nicolas Juen, Romain Lefort

 ----

 Copyright 2018 Be API Technical team (human@beapi.fr)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'BEAPI_MAINTENANCE_MODE_VERSION', '1.0.5' );
define( 'BEAPI_MAINTENANCE_MODE_MIN_PHP_VERSION', '5.6' );

// Plugin URL and PATH
define( 'BEAPI_MAINTENANCE_MODE_URL', plugin_dir_url( __FILE__ ) );
define( 'BEAPI_MAINTENANCE_MODE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BEAPI_MAINTENANCE_MODE_MAIN_FILE_DIR', __FILE__ );
define( 'BEAPI_MAINTENANCE_MODE_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );

// Check PHP min version
if ( version_compare( PHP_VERSION, BEAPI_MAINTENANCE_MODE_MIN_PHP_VERSION, '<' ) ) {
	require_once( BEAPI_MAINTENANCE_MODE_DIR . 'compat.php' );

	// possibly display a notice, trigger error
	add_action( 'admin_init', array( 'BEAPI\Maintenance_Mode\Compatibility', 'admin_init' ) );

	// stop execution of this file
	return;
}

// Autoload all the things \o/
require_once BEAPI_MAINTENANCE_MODE_DIR . 'autoload.php';

add_action( 'plugins_loaded', 'plugins_loaded_beapi_maintenance_mode_plugin' );
/** Init the plugin */
function plugins_loaded_beapi_maintenance_mode_plugin() {
	// Client
	\BEAPI\Maintenance_Mode\Main::get_instance();
}
