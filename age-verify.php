<?php
/*
Plugin Name: AgeVerify
Plugin URI: https://ageverify.co
Description: Add age verification to your WordPress site, via AgeVerify
Version: 1.3
Author: AgeVerify
Author URI: https://ageverify.co
Text Domain: ageverify
*/

/*  Copyright 2015 AgeVerify
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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// ------------------------------------------------------------------------
// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               
// ------------------------------------------------------------------------


function ageverify_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.8", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.8 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'ageverify_requires_wordpress_version' );

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'ageverify_add_defaults');
register_uninstall_hook(__FILE__, 'ageverify_delete_plugin_options');
add_action('admin_init', 'ageverify_init' );
add_action('admin_menu', 'ageverify_add_options_page');

// Require options 
require_once( plugin_dir_path( __FILE__ ) . 'options.php' );


// Initialize language so it can be translated
function ageverify_language_init() {
  load_plugin_textdomain( 'ageverify', false, dirname( plugin_basename( __FILE__ ) ) . 'languages' );
}
add_action('init', 'ageverify_language_init');

// Enqueue CSS on settings page
function enqueue_ageverify_options_css($hook) {
    if ( 'toplevel_page_age-verify-options' != $hook ) {
        return;
    }

    wp_register_style( 'ageverify_options_css', plugins_url() . '/ageverify/css/ageverify.css', false, '1.0.0' );
    wp_enqueue_style( 'ageverify_options_css' );
    wp_enqueue_script( 'ageverify_gallery', plugin_dir_url( __FILE__ ) . 'js/gallery.js' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_ageverify_options_css' );


// ------------------------------------------------------------------------
// ADD JAVASCRIPT TO HEADER
// ------------------------------------------------------------------------

add_action( 'wp_head', 'ageverify_print_script' );

function ageverify_print_script() {
	$options = get_option( 'ageverify_settings' );

	if( !isset( $options['ageverify_on'] ) || "1" !== $options['ageverify_on'] ) {
		// AgeVerify isnt turned on, so abort immediately
		return;
	}

	if( isset( $options['ageverify_template'] ) ) {
		$template = $options['ageverify_template'];
	} else {
		$template = 'opaque';
	}
	
	if( isset( $options['ageverify_language'] ) && 'en' !== $options['ageverify_language'] ) {
		$language = $options['ageverify_language'];
	} else {
		$language = '';
	}

	if( isset( $options['ageverify_age'] ) ) {
		$age = $options['ageverify_age'];
	} else {
		$age = '18';
	}

	if( isset( $options['ageverify_method'] ) && 'mdy' == $options['ageverify_method'] && 'en' == $options['ageverify_language'] ) {
		// this is cheating.  English is the only language with the mdy method available, and the method is appended to "js" just like the language is, so just pretend mdy is the language
		$language = 'mdy';
	}

	$script = '<script type="text/javascript" src="https://av.ageverify.co/WPjs' . $language . '/' . $template . '.' . $age . '.js"></script>';

	echo $script;
}


?>
