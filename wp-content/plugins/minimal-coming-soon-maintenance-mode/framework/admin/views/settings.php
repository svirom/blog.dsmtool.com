<?php

/**
 * Settings panel view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 */

require_once 'header.php';

?>

	<form role="form" method="post" class="signals-admin-form">
		<div class="signals-body signals-clearfix">
			<?php

				// Display the message if $signals_csmm_err is set
				if ( !empty( $signals_csmm_err ) ) {
					echo $signals_csmm_err;
				}

        $current_user = wp_get_current_user();
        $name = '';
        if (!empty($current_user->user_firstname)) {
          $name = ' ' . $current_user->user_firstname;
        }
        $meta = get_option('signals_csmm_meta', false);
    
        if (!get_transient('csmm_rate_hide')
            && (time() - $meta['first_install_gmt']) > (DAY_IN_SECONDS * 1)) {
          echo '<div id="rating-notice"><p>';
          echo '<b>Hi' . $name . '!</b><br>We saw you\'ve been using the <b>Maintenance Mode</b> plugin for a few days (that\'s awesome!) and wanted to ask for your help to <b>make the plugin better</b>.<br>It just takes a minute of your time to rate the plugin. It helps us out a lot!</p>';
          echo '<p><a href="https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode/reviews/?rate=5&filter=5#new-post" target="_blank" class="button button-flat">Rate the plugin</a>';
          echo '<a href="#" id="mm_rate_cancel">I\'ve already rated the plugin</a>';
          echo '</p></div>';
        }
			?>

			<div class="signals-float-left">
				<div class="signals-mobile-menu">
					<a href="javascript:void;">
						<img src="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/img/toggle.png" />
					</a>
				</div>

				<ul class="signals-main-menu">
					<li><a href="#basic"><?php _e( 'Basic', 'signals' ); ?></a></li>
					<li><a href="#email"><?php _e( 'Email', 'signals' ); ?></a></li>
					<li><a href="#design"><?php _e( 'Design', 'signals' ); ?></a></li>
					<li><a href="#form"><?php _e( 'Form', 'signals' ); ?></a></li>
					<li><a href="#advanced"><?php _e( 'Advanced', 'signals' ); ?></a></li>
					<li><a href="#support"><?php _e( 'Support', 'signals' ); ?></a></li>
				</ul>
			</div><!-- .signals-float-left -->

			<div class="signals-float-right">
				<?php

					// Including tabs content
					require_once 'settings-basic.php';		// basic
					require_once 'settings-email.php';		// email
					require_once 'settings-design.php';		// design
					require_once 'settings-form.php';		// form
					require_once 'settings-advanced.php';	// advanced
					require_once 'settings-support.php';	// support
				?>
			</div><!-- .signals-float-right -->

			<div class="signals-fixed-save-btn">
				<div class="signals-tile-body">
					<p class="signals-form-help-block" style="margin: 0; padding: 0 20px 0 10px;">
						<button type="submit" name="signals_csmm_submit" class="signals-btn signals-btn-red"><strong><?php _e( 'Save Changes', 'signals' ); ?></strong></button>
            <a style="margin: 0 0 0 15px;" href="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/preview.php" class="signals-btn" target="_blank"><strong><?php _e( 'Preview Maintenance Page', 'signals' ); ?></strong></a>
					</p>
				</div><!-- .signals-tile-body -->
			</div><!-- .signals-fixed-save-btn -->
		</div><!-- .signals-body -->
	</form><!-- form.signals-admin-form -->

<?php

require_once 'footer.php';
