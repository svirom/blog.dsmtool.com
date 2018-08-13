<?php
	
////////////////////////////
// SETTINGS PAGE 
////////////////////////////

function fca_pc_plugin_menu() {
	add_options_page( 
		__( 'Facebook Pixel Manager', 'pixel-cat' ),
		__( 'Facebook Pixel Manager', 'pixel-cat' ),
		'manage_options',
		'fca_pc_settings_page',
		'fca_pc_settings_page'
	);
}
add_action( 'admin_menu', 'fca_pc_plugin_menu' );

//ENQUEUE ANY SCRIPTS OR CSS FOR OUR ADMIN PAGE EDITOR
function fca_pc_admin_enqueue() {

	wp_enqueue_style('dashicons');
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'fca_pc_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array(), FCA_PC_PLUGIN_VER, true );
	wp_enqueue_style( 'fca_pc_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array(), FCA_PC_PLUGIN_VER );
	
	wp_enqueue_style( 'fca_pc_tooltipster_stylesheet', FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.css', array(), FCA_PC_PLUGIN_VER );
	wp_enqueue_style( 'fca_pc_tooltipster_borderless_css', FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster-borderless.min.css', array(), FCA_PC_PLUGIN_VER );
	wp_enqueue_script( 'fca_pc_tooltipster_js',FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.js', array('jquery'), FCA_PC_PLUGIN_VER, true );
				
	wp_enqueue_script('fca_pc_admin_js', FCA_PC_PLUGINS_URL . '/includes/editor/admin.min.js', array( 'jquery', 'fca_pc_select2' ), FCA_PC_PLUGIN_VER, true );		
	wp_enqueue_style( 'fca_pc_admin_stylesheet', FCA_PC_PLUGINS_URL . '/includes/editor/admin.min.css', array(), FCA_PC_PLUGIN_VER );
	
	$admin_data = array (
		'ajaxurl' => admin_url ( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'fca_pc_admin_nonce' ),
	);
	
	wp_localize_script( 'fca_pc_admin_js', 'adminData', $admin_data );
	
}

function fca_pc_admin_notice_save() {

	echo '<div id="fca-pc-notice-save" style="padding-bottom: 10px;" class="notice notice-success is-dismissible">';
		echo '<p><strong>' . __( "Settings saved.", 'pixel-cat' ) . '</strong></p>';
		echo '<a class="button button-primary" target="_blank" href="https://fatcatapps.com/facebook-pixel-wordpress-plugin/#pixel-helper">' . __( "Verify my pixel", 'pixel-cat' ) . '</a>';
	echo '</div>';
}


function fca_pc_settings_page() {
	
	fca_pc_admin_enqueue();
	
	if ( isSet( $_POST['fca_pc_save'] ) ) {
		fca_pc_settings_save();
		fca_pc_admin_notice_save();
	}	
	$options = get_option( 'fca_pc', true );
	$id = empty ( $options['id'] ) ? '' : $options['id'];
	$exclude = empty ( $options['exclude'] ) ? array() : $options['exclude'];
	//DEFAULT EXCLUDE TO ADMIN & EDITOR
	$exclude = empty ( $options['has_save'] ) ? array( 'Administrator', 'Editor' ) : $exclude;
	
	$html = '<form style="display: none" action="" method="post" id="fca_pc_main_form">';
		
		$html .= '<h1>' .  __('Facebook Pixel Manager - Pixel Cat', 'pixel-cat') . '</h1>';
		$html .= '<p>' . sprintf(  __('Need help? %1$sRead our quick-start guide.%2$s', 'pixel-cat'), '<a href="https://fatcatapps.com/facebook-pixel-wordpress-plugin/" target="_blank">', '</a>' ) . '</p>';
		
		//ADD A HIDDEN INPUT TO DETERMINE IF WE HAVE AN EMPTY SAVE OR NOT
		$html .= fca_pc_input ( 'has_save', '', true, 'hidden' );
		
		$html .= '<table class="fca_pc_setting_table" >';
			$html .= "<tr>";
				$html .= '<th>' . __('Facebook Pixel ID', 'pixel-cat') . '</th>';
				$html .= '<td id="fca-pc-helptext" title="' . __('Your Facebook Pixel ID should only contain numbers', 'pixel-cat') . '" >' . fca_pc_input ( 'id', 'e.g. 123456789123456', $id, 'text' );
				$html .= '<a class="fca_pc_hint" href="https://fatcatapps.com/facebook-pixel-wordpress-plugin/#pixel-id" target="_blank">' . __( 'What is my Facebook Pixel ID?', 'pixel-cat' ) . '</a>';
				$html .= '</td>';
			$html .= "</tr>";
			$html .= "<tr>";
				$html .= '<th>' . __('Exclude Users', 'pixel-cat') . '</th>';
				$html .= '<td>' . fca_pc_input ( 'exclude', '', $exclude, 'roles' );
				$html .= '<p class="fca_pc_hint">' . __( 'Logged in users selected above will not trigger your pixel.', 'pixel-cat' ) . '</p>';
				$html .= '</td>';
			$html .= "</tr>";

		$html .= '</table>';
		
		$html .= '<button type="submit" name="fca_pc_save" class="button button-primary">' . __('Save', 'pixel-cat') . '</button>';
	
	$html .= '</form>';
	
	
	echo $html;
}

function fca_pc_settings_save() {
	$data = fca_pc_escape_input ( $_POST['fca_pc'] );
	update_option( 'fca_pc', $data );
}