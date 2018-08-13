<?php

/**
 * WordPress management panel.
 *
 * @link       http://www.webfactoryltd.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

function csmm_meta_links( $links, $file ) {

	if ( strpos( $file, 'minimal-coming-soon-maintenance-mode.php' ) !== false ) {
		$new_links = array(
			'<a href="https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode" target="_blank">' . __( 'Support', 'signals' ) . '</a>'
		);

		$links = array_merge( $links, $new_links );
	}

	return $links;

}
add_filter( 'plugin_row_meta', 'csmm_meta_links', 10, 2 ); // Add plugin meta links



// Menu for the support and about panel
function csmm_add_menu() {

	if( is_admin() && current_user_can( 'manage_options' ) ) {
		// Adding to the plugin panel link to the settings menu
		$signals_csmm_menu = add_options_page (
			__( 'Coming Soon & Maintenance Mode', 'signals' ),
			__( 'Maintenance Mode', 'signals' ),
			'manage_options',
			'maintenance_mode_options',
			'csmm_admin_settings'
		);

		// Loading the JS conditionally
		add_action( 'load-' . $signals_csmm_menu, 'csmm_load_scripts' );
	}

}
add_action( 'admin_menu', 'csmm_add_menu' );



// Registering JS and CSS files over here
function csmm_admin_scripts() {

	wp_register_style( 'csmm-admin-base', SIGNALS_CSMM_URL . '/framework/admin/css/admin.css', false, csmm_get_plugin_version() );

	wp_register_script( 'csmm-webfonts', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', false );
	wp_register_script( 'csmm-admin-editor', SIGNALS_CSMM_URL . '/framework/admin/js/editor/ace.js', false, csmm_get_plugin_version(), true );
	wp_register_script( 'csmm-admin-color', SIGNALS_CSMM_URL . '/framework/admin/js/colorpicker/jscolor.js', false, csmm_get_plugin_version(), true );
	wp_register_script( 'csmm-admin-base', SIGNALS_CSMM_URL . '/framework/admin/js/admin.js', 'jquery', csmm_get_plugin_version(), true );

	// Calling the files
	wp_enqueue_style( 'csmm-admin-base' );

	wp_enqueue_script( 'csmm-webfonts' );
	wp_enqueue_script( 'csmm-admin-editor' );
	wp_enqueue_script( 'csmm-admin-color' );
	wp_enqueue_script( 'csmm-admin-base' );

	// For the upload option using media uploader
	wp_enqueue_media();
}


// Scripts & styles for the plugin
function csmm_load_scripts() {
	add_action( 'admin_enqueue_scripts', 'csmm_admin_scripts' );
}

function csmm_plugin_admin_init() {
  if (!is_admin()) {
    return;
  }
  
  $meta = get_option('signals_csmm_meta', array());
  if (!isset($meta['first_version']) || !isset($meta['first_install'])) {
    $meta['first_version'] = csmm_get_plugin_version();
    $meta['first_install_gmt'] = time();
    $meta['first_install'] = current_time('timestamp');
    update_option('signals_csmm_meta', $meta);
  }
} // csmm_plugin_admin_init
add_action( 'init', 'csmm_plugin_admin_init' );

// Including file for the management panel
require SIGNALS_CSMM_PATH . 'framework/admin/settings.php';
