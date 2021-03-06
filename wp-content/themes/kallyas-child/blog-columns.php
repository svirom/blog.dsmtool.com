<?php if(! defined('ABSPATH')){ return; }
	global $columns, $zn_config, $current_post;
	
	/*
		* Load resources in footer
	*/
	//wp_enqueue_script('isotope'); //ilya disable animation of rearrangement
	
	$columns = !empty( $zn_config['blog_columns'] ) ? $zn_config['blog_columns'] : zget_option( 'blog_style_layout', 'blog_options', false, '1' );
	
	if(is_search()){
		if($zn_config['sidebar']){
			$columns = 2;
		}
		else {
			$columns = 3;
		}
	}
	
	$columns = apply_filters('zn_blogcolumns_cols', $columns);
	
	$columns_size = str_replace('.', '', (12 / ($columns ? $columns : 6))); // prevent division by 0 & remove dots in 12/5=2.4
	
	// Check if PB Element has style selected, if not use Blog style option. If no blog style option, use Global site skin.
	$blog_style = zget_option( 'blog_style', 'blog_options', false, '' ) != '' ? zget_option( 'blog_style', 'blog_options', false, '' ) : zget_option( 'zn_main_style', 'color_options', false, 'light' );
	if( isset( $zn_config['blog_style'] ) ){
		$blog_style = $zn_config['blog_style'] != '' ? $zn_config['blog_style'] : $blog_style;
	}
?>
<div class="itemListView clearfix eBlog kl-blog kl-blog-list-wrapper kl-blog--style-<?php echo $blog_style; ?>" <?php echo WpkPageHelper::zn_schema_markup('blog'); ?>>
	
	<?php
		the_archive_description( '<div class="kl-blog-taxonomy-description">', '</div>' );
	?>
	
	<?php
		if ( have_posts() ) :
		
		echo '<div class="itemList zn_blog_columns kl-blog--columns kl-cols-3 row">';
		
		
		while ( have_posts() ) {
			the_post();
			//ilya dont show updates cat start
			//global $post;
			/*$terms = wp_get_post_terms( $post->ID, 'category' );
			$categories = [];
			foreach ( $terms as $term ) $categories[] = $term->slug;
			if ( in_array( 'dropshipping-marketing', $categories ) ) {
				//continue;
			} 
			if ( in_array( 'drop-shipping-arbitrage-for-beginners', $categories ) ) {
				//continue;
			} 
			if ( in_array( 'advanced-dropshipping-arbitrage', $categories ) ) {
				//continue;
			}*/ 
			
			//ilya dont show updates cat end
			
			$image = '';
			
			$post_format    = get_post_format() ? get_post_format() : 'standard';
			$current_post   = zn_setup_post_data( $post_format, 'excerpt' );
			
			
			// Hide Body & bottomn links side of the articles, for Links, Quote (post type articles)
			$hide_body = ($post_format == 'link' || $post_format == 'quote');
			
			
		?>
		<div class="col-sm-6 col-lg-4 blog-isotope-item kl-blog-column">
			<div class="itemContainer kl-blog-item-container zn_columns zn_columns3 <?php echo implode ( ' ' , get_post_class('blog-post' ) ); ?>">
				
				<?php 
					
					
				echo $current_post['before_head']; ?>
				
				<?php
					
					
					// Blgo media
					include(locate_template( 'components/blog/multicolumns/blog-media.php' ));
					// Load item header
					include(locate_template( 'components/blog/blog-meta.php' ));
				?>
				
				<?php if(!$hide_body): ?>
				
				<?php
					
					// Load item content
					include(locate_template( 'components/blog/multicolumns/blog-content.php' ));
					
					
					// Load item links
					include(locate_template( 'components/blog/blog-links.php' ));
				?>
				<!-- item links -->
				<div class="clearfix"></div>
				
				<?php
					// Load item tags
					include(locate_template( 'components/blog/multicolumns/blog-tags.php' ));
				?>
				
				<?php endif; ?>
				
			</div><!-- end Blog Item -->
		</div>
		<?php
		}
		
		echo '</div>';
		
		else:
		/**
			* No posts message
			* @since v4.0.12
		*/
		include(locate_template( 'components/blog/blog-noposts.php' ));
		endif;
	?>
	
	<!-- end .itemList -->
	
	<?php include(locate_template( 'components/blog/blog-pagination.php' )); ?>
	
</div>


