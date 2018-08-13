<?php
/*
	Plugin Name: Pixel Cat Free
	Plugin URI: https://fatcatapps.com/pixel-cat
	Description: Provides an easy way to embed Facebook pixels
	Text Domain: pixel-cat
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 1.0.2
*/


// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



if ( !defined('FCA_PC_PLUGIN_DIR') ) {
	
	//DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_PC_PLUGIN_VER', '1.0.2' );
	define( 'FCA_PC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_PC_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_PC_PLUGINS_BASENAME', plugin_basename(__FILE__) );
	define( 'FCA_PC_PLUGIN_FILE', __FILE__ );
	define( 'FCA_PC_PLUGIN_PACKAGE', 'Free' ); //DONT CHANGE THIS - BREAKS AUTO UPDATER
	
	//LOAD CORE
	include_once( FCA_PC_PLUGIN_DIR . '/includes/api.php' );
	
	//LOAD MODULES
	include_once( FCA_PC_PLUGIN_DIR . '/includes/editor/editor.php' );

	if ( file_exists ( FCA_PC_PLUGIN_DIR . '/includes/splash/splash.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/splash/splash.php' );
	}
	
	//ACTIVATION HOOK
	function fca_pc_activation() {
		fca_pc_api_action( 'Activated Pixel Cat Free' );
	}
	register_activation_hook( FCA_PC_PLUGIN_FILE, 'fca_pc_activation' );
	
	//DEACTIVATION HOOK
	function fca_pc_deactivation() {
		fca_pc_api_action( 'Deactivated Pixel Cat Free' );
	}
	register_deactivation_hook( FCA_PC_PLUGIN_FILE, 'fca_pc_deactivation' );
	
	//INSERT PIXEL
	function fca_pc_maybe_add_pixel() {

		$roles = wp_get_current_user()->roles;
		
		$options = get_option( 'fca_pc', true );
		$id = empty ( $options['id'] ) ? '' : $options['id'];
		$exclude = empty ( $options['exclude'] ) ? array() : $options['exclude'];
		$do_pixel = count( array_intersect( array_map( 'strtolower', $roles), array_map( 'strtolower', $exclude ) ) ) == 0;
				
		if ( !empty( $options['id'] ) && $do_pixel ) {
			
			ob_start(); ?>
			
			<!-- Facebook Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo $id ?>');
			fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=<?php echo $id ?>&ev=PageView&noscript=1"
			/></noscript>
			<!-- DO NOT MODIFY -->
			<!-- End Facebook Pixel Code -->
			
			<?php
			echo ob_get_clean();
		}
	}
	add_action('wp_head', 'fca_pc_maybe_add_pixel');
	
	////////////////////////////
	// LOCALIZATION
	////////////////////////////
	
	function fca_pc_load_localization() {
		load_plugin_textdomain( 'pixel-cat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'fca_pc_load_localization' );
	
	////////////////////////////
	// FUNCTIONS
	////////////////////////////
		
	//RETURN GENERIC INPUT HTML
	function fca_pc_input ( $name, $placeholder = '', $value = '', $type = 'text' ) {
	
		$html = "<div class='fca-pc-field fca-pc-field-$type'>";
		
			switch ( $type ) {
				
				case 'checkbox':
					$checked = !empty( $value ) ? "checked='checked'" : '';
					
					$html .= "<div class='onoffswitch'>";
						$html .= "<input style='display:none;' type='checkbox' id='fca_pc[$name]' class='onoffswitch-checkbox fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' $checked>"; 
						$html .= "<label class='onoffswitch-label' for='fca_pc[$name]'><span class='onoffswitch-inner' data-content-on='ON' data-content-off='OFF'><span class='onoffswitch-switch'></span></span></label>";
					$html .= "</div>";
					break;
					
				case 'textarea':
					$html .= "<textarea placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]'>$value</textarea>";
					break;
					
				case 'image':
					$html .= "<input type='hidden' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					$html .= "<button type='button' class='button-secondary fca_pc_image_upload_btn'>" . __('Add Image', 'pixel-cat') . "</button>";
					$html .= "<img class='fca_pc_image' style='max-width: 252px' src='$value'>";
			
					$html .= "<div class='fca_pc_image_hover_controls'>";
						$html .= "<button type='button' class='button-secondary fca_pc_image_change_btn'>" . __('Change', 'pixel-cat') . "</button>";
						$html .= "<button type='button' class='button-secondary fca_pc_image_revert_btn'>" . __('Remove', 'pixel-cat') . "</button>";
					$html .=  '</div>';
					break;
				case 'color':
					$html .= "<input type='hidden' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					break;
				case 'editor':
					ob_start();
					wp_editor( $value, $name, array() );
					$html .= ob_get_clean();
					break;
				case 'datepicker':
					$html .= "<input type='text' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					break;
				case 'roles':
					$roles = get_editable_roles();
					forEach ( $roles as $role ) {
						$options[] = $role['name'];
					}

					$html = "<select name='fca_pc[$name][]' data-placeholder='$placeholder' multiple='multiple' style='width: 100%; border: 1px solid #ddd; border-radius: 0;' class='fca_pc_multiselect'>";
						forEach ( $options as $role ) {
							if ( in_array($role, $value) ) {
								$html .= "<option value='$role' selected='selected'>$role</option>";
							} else {
								$html .= "<option value='$role'>$role</option>";
							}
						}
					
					$html .= "</select>";
					break;
					
				default: 
					$html .= "<input type='$type' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
			}
		
		$html .= '</div>';
		
		return $html;
	}
	
	function fca_pc_tooltip( $text = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
		return "<span class='$icon fca_pc_tooltip' title='" . htmlentities( $text ) . "'></span>";
	}
	
	function fca_pc_convert_entities ( $array ) {
		$array = is_array($array) ? array_map('fca_pc_convert_entities', $array) : html_entity_decode( $array, ENT_QUOTES );
		return $array;
	}

	function fca_pc_escape_input ($data) {
		
		if ( is_array ( $data ) ) {
			forEach ( $data as $k => $v ) {
				$data[$k] = fca_pc_escape_input($v);
			}
			return $data;
		}
		
		$data = wp_kses_post( $data );
			
		return $data;

	}
	
	function fca_pc_add_plugin_action_links( $links ) {
		
		$url = admin_url('options-general.php?page=fca_pc_settings_page');
		
		$new_links = array(
			'configure' => "<a href='$url' >" . __('Configure Pixel', 'pixel-cat' ) . '</a>'
		);
		
		$links = array_merge( $new_links, $links );
	
		return $links;
		
	}
	add_filter( 'plugin_action_links_' . FCA_PC_PLUGINS_BASENAME, 'fca_pc_add_plugin_action_links' );
	
	//ADD NAG IF NO PIXEL IS SET
	function fca_pc_admin_notice() {
		$options = get_option( 'fca_pc', true );

		if ( empty( $options['id'] ) ) {
			$url = admin_url( 'options-general.php?page=fca_pc_settings_page' );
		
			echo '<div id="fca-pc-setup-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
				echo '<img style="float:left; margin-right: 16px;" height="120" width="120" src="' . FCA_PC_PLUGINS_URL . '/assets/pixelcat_icon_128_128_360.png' . '">';
				echo '<p><strong>' . __( "Thank you for installing Pixel Cat.", 'pixel-cat' ) . '</strong></p>';
				echo '<p>' . __( " You haven't configured your Facebook Pixel yet. Ready to get started?", 'pixel-cat' ) . '</p>';
				echo "<a href='$url' type='button' class='button button-primary' style='margin-top: 25px;'>" . __( 'Set up my Pixel', 'pixel-cat') . "</a> ";
				echo '<br style="clear:both">';
			echo '</div>';
		}
	
	}
	add_action( 'admin_notices', 'fca_pc_admin_notice' );
	
	//DEACTIVATION SURVEY
	function fca_pc_admin_deactivation_survey( $hook ) {
		if ( $hook === 'plugins.php' ) {
			
			ob_start(); ?>
			
			<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
				<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php _e( 'Sorry to see you go', 'pixel-cat' ) ?></h3>
				<p><?php _e( 'Hi, this is David, the creator of Pixel Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'pixel-cat' ) ?>
				</p>
				<p><?php _e( 'I have a quick question that I hope you’ll answer to help us make Pixel Cat better: what made you deactivate?', 'pixel-cat' ) ?>
				</p>
				<p><?php _e( 'You can leave me a message below. I’d really appreciate it.', 'pixel-cat' ) ?>
				</p>
				
				<p><textarea style='width: 100%;' id='fca-deactivate-textarea' placeholder='<?php _e( 'What made you deactivate?', 'pixel-cat' ) ?>'></textarea></p>
				
				<div style='float: right;' id='fca-deactivate-nav'>
					<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-deactivate-skip'><?php _e( 'Skip', 'pixel-cat' ) ?></button>
					<button type='button' class='button button-primary' id='fca-deactivate-send'><?php _e( 'Send Feedback', 'pixel-cat' ) ?></button>
				</div>
			
			</div>
			
			<?php
				
			$html = ob_get_clean();
			
			$data = array(
				'html' => $html,
				'nonce' => wp_create_nonce( 'fca_pc_uninstall_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);
						
			wp_enqueue_script('fca_pc_deactivation_js', FCA_PC_PLUGINS_URL . '/includes/deactivation.min.js', false, FCA_PC_PLUGIN_VER, true );
			wp_localize_script( 'fca_pc_deactivation_js', "fca_pc", $data );
		}
		
		
	}	
	add_action( 'admin_enqueue_scripts', 'fca_pc_admin_deactivation_survey' );
	
}