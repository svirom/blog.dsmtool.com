<?php

/**
 * Plugin Name: Minimal Coming Soon & Maintenance Mode
 * Plugin URI: https://wordpress.org/plugins/minimal-coming-soon-maintenance-mode/
 * Description: Simply awesome coming soon & maintenance mode plugin for WordPress. Super-simple to use. MailChimp support built-in.
 * Version: 1.45
 * Author: WebFactory
 * Author URI: http://www.webfactoryltd.com
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: signals
 *
 *
 * Minimal Coming Soon & Maintenance Mode Plugin
 * Copyright (C) 2016 - 2017, Web Factory Ltd - support@webfactoryltd.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Defining constants and activation hook.
 * If this file is called directly, abort.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


/* Constants we will be using throughout the plugin. */
define( 'SIGNALS_CSMM_URL', plugins_url('', __FILE__ ) );
define( 'SIGNALS_CSMM_PATH', plugin_dir_path( __FILE__ ) );


function csmm_default_options() {
  $default_options = array(
    'status'        => '2',
    'title'         => 'Site is under maintenance',
    'header_text'       => 'Sorry, we\'re doing some maintenance',
    'secondary_text'     => 'We are doing some maintenance on our site. It won\'t take long, we promise. Come back and visit us again in a few days. Thank you for your patience!',
    'antispam_text'     => 'And yes, we hate spam too!',
    'custom_login_url'     => '',
    'show_logged_in'     => '1',
    'exclude_se'      => '1',
    'arrange'         => 'logo,header,secondary,form,html',
    'analytics'       => '',

    'mailchimp_api'      => '',
    'mailchimp_list'     => '',
    'message_noemail'     => 'Please provide a valid email address.',
    'message_subscribed'   => 'You are already subscribed!',
    'message_wrong'     => 'Oops! Something went wrong.',
    'message_done'       => 'Thank you! We\'ll be in touch!',

    'logo'          => SIGNALS_CSMM_URL . '/framework/public/img/mm-logo.jpg',
    'favicon'        => SIGNALS_CSMM_URL . '/framework/public/img/mm-favicon.png',
    'bg_cover'         => SIGNALS_CSMM_URL . '/framework/public/img/mountain-bg.jpg',
    'content_overlay'     => 1,
    'content_width'      => '600',
    'bg_color'         => 'ffffff',
    'content_position'    => 'center',
    'content_alignment'    => 'left',
    'header_font'       => 'Karla',
    'secondary_font'     => 'Karla',
    'header_font_size'     => '28',
    'secondary_font_size'   => '14',
    'header_font_color'   => 'FFFFFF',
    'secondary_font_color'   => 'FFFFFF',
    'antispam_font_size'   => '13',
    'antispam_font_color'   => 'bbbbbb',

    'input_text'       => 'Enter your best email address',
    'button_text'       => 'Subscribe',
    'ignore_form_styles'   => 1,
    'input_font_size'    => '13',
    'button_font_size'    => '12',
    'input_font_color'    => 'ffffff',
    'button_font_color'    => 'ffffff',
    'input_bg'        => '',
    'button_bg'        => '0f0f0f',
    'input_bg_hover'    => '',
    'button_bg_hover'    => '0a0a0a',
    'input_border'      => 'eeeeee',
    'button_border'      => '0f0f0f',
    'input_border_hover'  => 'bbbbbb',
    'button_border_hover'  => '0a0a0a',
    'success_background'   => '90c695',
    'success_color'     => 'ffffff',
    'error_background'     => 'e08283',
    'error_color'       => 'ffffff',

    'disable_settings'     => '2',
    'custom_html'      => '',
    'custom_css'      => ''
  );
  
  return $default_options;
} // csmm_default_options

function csmm_get_options() {
  $signals_csmm_options = get_option('signals_csmm_options', array());
  $signals_csmm_options = array_merge(csmm_default_options(), $signals_csmm_options);
  
  return $signals_csmm_options;
} // csmm_get_options


/**
 * For the plugin activation & de-activation.
 * We are doing nothing over here.
 */

function csmm_plugin_activation() {

	// Checking if the options exist in the database
	$signals_csmm_options = get_option( 'signals_csmm_options' );

	// Default options for the plugin on activation
	$default_options = csmm_default_options();

	// If the options are not there in the database, then create the default options for the plugin
	if ( ! $signals_csmm_options ) {
		update_option( 'signals_csmm_options', $default_options );
	} else {
		// If present in the database, merge with the default ones
		// This is to provide compatibility with earlier versions. Although it doesn't serve the purpose completely
		$default_options = array_merge( $default_options, $signals_csmm_options );
		update_option( 'signals_csmm_options', $default_options );
	}
  
  // set some meta data
  $meta = get_option('signals_csmm_meta', array());
  if (!isset($meta['first_version']) || !isset($meta['first_install'])) {
    $meta['first_version'] = csmm_get_plugin_version();
    $meta['first_install_gmt'] = time();
    $meta['first_install'] = current_time('timestamp');
    update_option('signals_csmm_meta', $meta);
  }
} // csmm_plugin_activation
register_activation_hook( __FILE__, 'csmm_plugin_activation');


/* Hook for the plugin deactivation. */
function csmm_plugin_deactivation() {

	// Silence is golden
	// We might use this in future versions

}
register_deactivation_hook( __FILE__, 'csmm_plugin_deactivation' );


/**
 * Including files necessary for the management panel of the plugin.
 * Basically, support panel and option to insert custom .css is provided.
 */

if ( is_admin() ) {
	require SIGNALS_CSMM_PATH . 'framework/admin/init.php';
}


/**
 * Let's start the plugin now.
 * All the widgets are included and registered using the right hook.
 */

require SIGNALS_CSMM_PATH . 'framework/public/init.php';


function csmm_get_plugin_version() {
  $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');

  return $plugin_data['version'];
} // csmm_get_plugin_version
