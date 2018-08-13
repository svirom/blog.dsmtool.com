<?php

	// THIS WILL ALLOW ADDING CUSTOM CSS TO THE style.css FILE and JS code to /js/zn_script_child.js

	add_action( 'wp_enqueue_scripts', 'kl_child_scripts',11 );
	function kl_child_scripts() {

		wp_deregister_style( 'kallyas-styles' );
		wp_enqueue_style( 'kallyas-styles', get_template_directory_uri().'/style.css', '' , ZN_FW_VERSION );
		wp_enqueue_style( 'kallyas-child', get_stylesheet_uri(), array('kallyas-styles') , ZN_FW_VERSION );

		/**
			**** Uncomment this line if you want to add custom javascript file
		*/
		// wp_enqueue_script( 'zn_script_child', get_stylesheet_directory_uri() .'/js/zn_script_child.js' , '' , ZN_FW_VERSION , true );

	}

	/* ======================================================== */

	/**
		* Load child theme's textdomain.
	*/
	add_action( 'after_setup_theme', 'kallyasChildLoadTextDomain' );
	function kallyasChildLoadTextDomain(){
		load_child_theme_textdomain( 'zn_framework', get_stylesheet_directory().'/languages' );
	}

	/* ======================================================== */

	/**
		* Example code loading JS in Header. Uncomment to use.
	*/

	/* ====== REMOVE COMMENT

		add_action('wp_head', 'KallyasChild_loadHeadScript' );
		function KallyasChild_loadHeadScript(){

		echo '
		<script type="text/javascript">

		// Your JS code here

		</script>';

		}
	====== REMOVE COMMENT */

	/* ======================================================== */

	/**
		* Example code loading JS in footer. Uncomment to use.
	*/

	/* ====== REMOVE COMMENT

		add_action('wp_footer', 'KallyasChild_loadFooterScript' );
		function KallyasChild_loadFooterScript(){

		echo '
		<script type="text/javascript">

		// Your JS code here

		</script>';

		}
	====== REMOVE COMMENT */

	/* ======================================================== */


	/*Add functions to Woocommerce*/
require_once 'functions/woocommerce_f.php';


	/*
		* exclude categories from blog feed. Here we exclude updats category
	*/
	add_action( 'pre_get_posts', 'exclude_category' );
	function exclude_category( $query ) {
		if ( /*$query->is_home() &&*/ !is_admin() && $query->is_main_query() && !is_category(9) && !is_category(1005) && !is_tag() ) {
			//$query->set( 'cat', '-9,-10,-34' );
			$query->set( 'cat', '-9, -1005' );
		}
		/*if (is_page( array( 'ilya-news-events' )))
			{
			$query->set( 'cat', '-9,-10,-34' );
		}*/

	}
	/*
		* filter posts within recent posts widget to show only posts from current cat
	*/

	//add_filter( 'rpwe_default_query_arguments', 'your_custom_function' );
	function your_custom_function( $args ) {
		//$args['posts_per_page'] = 10; // Changing the number of posts to show.

		/*if (strpos($args['cat'], '510') !== false) {
			$args['cat'] = '9';
		}*/
		//$args['cat'] = 9;
		//if ($args['cat'] != '510')
		//$args['cat'] = '9';
	if ((9 != $args['cat']))
	$args['cat'] [] = -9;
	return $args;
	}


	//Admin menu Featured Articles
	add_action('admin_menu', 'my_admin_menu');

	function my_admin_menu() {
		add_menu_page('Featured Article', 'Featured Article', 1, 'catalog.php', 'print_page_function', 'dashicons-awards', 5);

	}
function print_page_function() {
	$selected_page_id = get_option( 'dsm_featured_article' );
	if ( isset( $_POST['featured_submit'] ) && ! empty( $_POST['featured_article'] ) ) {
		$selected_page_id = absint( $_POST['featured_article'] );
		update_option( 'dsm_featured_article', $selected_page_id );
	} ?>
	<div class="wrapper_featured" style="padding:40px 20px;">
		<h2 style="font-size:1.8em;">Choose the post to establish Featured Article</h2>
		<form method="post" action="" style="margin-bottom:40px;">
			<?php $posts = get_posts( array( 'posts_per_page' => -1 ) ); ?>
			<select name="featured_article">
				<option value="0" <?php selected( empty( $selected_page_id ) ); ?>>Select post ID</option>
				<?php foreach ( $posts as $post ) {
					printf(
						'<option value="%s" %s>%s</option>',
						$post->ID,
						selected( $selected_page_id == $post->ID, true, false ),
						get_the_title( $post->ID )
					);
				} ?>
			</select>
			<input type="submit" name="featured_submit" value="OK">
		</form>
		<?php
		$the_permalink = get_the_permalink($selected_page_id);
		$the_title = get_the_title($selected_page_id);
		echo
		'<p style="font-weight:600;">Current Featured Article:&nbsp;&nbsp;&nbsp;  <a href="' .$the_permalink. '" style="font-weight:400;">' .$the_title. '</a><p>';

			?>
	</div>

<?php }


	/*
		* add Featured posts on top of category/archive pages
	*/
	add_action( 'ilya-featured-blog-items', 'ilya_featured_blog_items_handler', 10 );
	function ilya_featured_blog_items_handler() {

	if ( is_home() ) {
		$featured_post_id = get_option( 'dsm_featured_article' );
		if ( $featured_post_id ) {
			$featured_post = get_post( $featured_post_id ); ?>
			<div class="featured-blog-items-section">
				<div class="featured-blog-items-custom-html">
					<div class="featured-container-left featured_article" style="background: url(<?php echo get_the_post_thumbnail_url( $featured_post_id ); ?>);" >
						<div class="itemContainer kl-blog-item-container zn_columns">

							<div class="itemHeader kl-blog-item-header">
								<h3 class="itemTitle kl-blog-item-title" itemprop="headline"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo get_the_title( $featured_post_id ); ?></a></h3>
								<!-- end post details -->
							</div>

							<div class="itemBody kl-blog-item-body" itemprop="text">
								<div class="blog-item-descr-readmore">
									<a href="<?php the_permalink( $featured_post ); ?>" class="blog-item-descr-readmore-a" title="<?php echo get_the_title( $featured_post ); ?>">READ MORE</a>
								</div>
							</div>
							<!-- end Item BODY -->

							<div class="clearfix"></div>

							<!-- end tags blocks -->

						</div>

					</div>

					<?php
					}
					?>
				</div>
			</div>
		<?php
		// Restore original Post Data
		}
	}



/**
 * Display the breadcrumb menu
 */
if ( !function_exists( 'zn_breadcrumbs' ) )
{
	/**
	 * Display the breadcrumb menu
	 */
	function zn_breadcrumbs( $args = array() )
	{
		global $post, $wp_query;

		$defaults = array(
			'delimiter' => '&raquo;',
			'show_home' => true,
			'home_text' => __( 'Home', 'zn_framework' ),
			'home_link' => home_url(),
			'show_current' => true, // show current post/page title in breadcrumbs
			'style' => zget_option( 'def_subh_bread_stl', 'general_options', false, 'black' ),
		);

		$args = wp_parse_args($args, $defaults);

		$before = '<span class="current">'; // tag before the current crumb
		$after = '</span>'; // tag after the current crumb

		$prepend = '';

		$breadcrumb_style = 'bread-style--' . $args['style'];

		if ( znfw_is_woocommerce_active() )
		{

			$shop_page_id = wc_get_page_id( 'shop' );
			$shop_page = get_post( $shop_page_id );


			if ( $shop_page_id && get_option( 'page_on_front' ) !== $shop_page_id )
			{
				$prepend = '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . get_the_title( wc_get_page_id( 'shop' ) );
				$prepend .= '</a></li>';
			}

		}


		if ( is_front_page() && $args['show_home'] )
		{
			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList" class="breadcrumbs fixclear ' . $breadcrumb_style . '"><li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '">' . $args['home_text'] . '</a></li></ul>';
		}
		elseif ( is_home() && $args['show_home'] )
		{

			$title = zget_option( 'archive_page_title', 'blog_options' );
			$title = do_shortcode( $title );

			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList" class="breadcrumbs fixclear ' . $breadcrumb_style . '"><li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '">' . $args['home_text'] . '</a></li><li>' . $title . '</li></ul>';
		}

		else
		{
			$bClass = 'breadcrumbs fixclear ' . $breadcrumb_style;
			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList"';
			if ( is_search() )
			{
				$bClass .= ' th-search-page-mtop';
			}

			echo ' class="' . $bClass . '">';

			if( $args['show_home'] ){
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '">' . $args['home_text'] . '</a></li>';
			}

			if ( is_category() )
			{
				$thisCat = get_category( get_query_var( 'cat' ), false );

				if ( $thisCat->parent != 0 )
				{
					$cats = get_category_parents( $thisCat->parent, true, '|zn_preg|' );
				}
				else
				{
					$cats = get_category_parents( $thisCat->term_id, true, '|zn_preg|' );
				}

				if ( !empty( $cats ) && !is_wp_error( $cats ) )
				{
					$cats = explode( '|zn_preg|', $cats );
					foreach ( $cats as $s_cat )
					{
						if ( !empty ( $s_cat ) )
						{
							$s_cat = str_replace( '<a', '<a property="item" typeof="WebPage" ', $s_cat );
							echo '<li property="itemListElement" typeof="ListItem">' . $s_cat . '</li>';
						}
					}
				}
				//hide text Archive from category ...
				/*echo '<li>' . __( "Archive from category ", 'zn_framework' ) . '"' . single_cat_title( '', false ) . '"</li>';*/
			}
			elseif ( is_tax( 'product_cat' ) )
			{
				echo $prepend;

				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$parents = array();
				$parent = $term->parent;

				while ( $parent )
				{
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
					$parent = $new_parent->parent;
				}

				if ( !empty( $parents ) )
				{
					$parents = array_reverse( $parents );

					foreach ( $parents as $parent )
					{
						$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
							get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a></li>';
					}
				}
				$queried_object = $wp_query->get_queried_object();
				echo '<li>' . $queried_object->name . '</li>';
			}
			elseif ( is_tax( 'project_category' ) || is_post_type_archive( 'portfolio' ) )
			{
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

				if ( !empty( $term->parent ) )
				{
					$parents = array();
					$parent = $term->parent;

					while ( $parent )
					{
						$parents[] = $parent;
						$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						$parent = $new_parent->parent;
					}

					if ( !empty( $parents ) )
					{
						$parents = array_reverse( $parents );

						foreach ( $parents as $parent )
						{
							$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
							echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
								get_term_link( $item->slug, 'project_category' ) . '">' . $item->name . '</a></li>';
						}
					}
				}
				$queried_object = $wp_query->get_queried_object();
				$menuItem = $queried_object->name;
				//@wpk: #68 - Replace "portfolio" with the one set by the user in the permalinks page
				if ( strcasecmp( 'portfolio', $queried_object->name ) == 0 )
				{
					$menuItem = $queried_object->rewrite[ 'slug' ];
				}
				echo '<li>' . $menuItem . '</li>';
			}
			elseif ( is_tax( 'documentation_category' ) )
			{
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$parents = array();
				$parent = $term->parent;

				while ( $parent )
				{
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
					$parent = $new_parent->parent;
				}

				if ( !empty( $parents ) )
				{
					$parents = array_reverse( $parents );

					foreach ( $parents as $parent )
					{
						$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
							get_term_link( $item->slug, 'documentation_category' ) . '">' . $item->name . '</a></li>';
					}
				}
				$queried_object = $wp_query->get_queried_object();
				echo '<li>' . $queried_object->name . '</li>';
			}
			elseif ( is_search() )
			{
				echo '<li>' . __( "Search results for ", 'zn_framework' ) . '"' . get_search_query() . '"</li>';
			}
			elseif ( is_day() )
			{
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a></li>';
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a></li>';
				echo '<li>' . get_the_time( 'd' ) . '</li>';
			}
			elseif ( is_month() )
			{
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a></li>';
				echo '<li>' . get_the_time( 'F' ) . '</li>';
			}
			elseif ( is_year() )
			{
				echo '<li>' . get_the_time( 'Y' ) . '</li>';
			}
			elseif ( is_post_type_archive( 'product' ) && get_option( 'page_on_front' ) !== wc_get_page_id( 'shop' ) )
			{
				$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : ucwords( get_option( 'woocommerce_shop_slug' ) );

				if ( is_search() )
				{
					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_post_type_archive_link( 'product' ) . '">' . $_name . '</a></li><li>' .
						__( 'Search results for &ldquo;', 'zn_framework' ) . get_search_query() . '</li>';
				}
				elseif ( is_paged() )
				{
					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_post_type_archive_link( 'product' ) . '">' . $_name . '</a></li>';
				}
				else
				{
					echo '<li>' . $_name . '</li>';
				}
			}
			elseif ( is_single() && !is_attachment() )
			{
				if ( get_post_type() == 'portfolio' )
				{
					// Show category name
					$cats = get_the_term_list( $post->ID, 'project_category', ' ', '|zn_preg|', '|zn_preg|' );
					$cats = explode( '|zn_preg|', $cats );
					if ( !empty ( $cats[ 0 ] ) )
					{
						$s_cat = str_replace( '<a', '<a property="item" typeof="WebPage" ', $cats[ 0 ] );
						echo '<li property="itemListElement" typeof="ListItem">' . $s_cat . '</li>';
					}
					if ( $args['show_current'] )
					{
						// Show post name
						echo '<li>' . get_the_title() . '</li>';
					}
				}
				elseif ( get_post_type() == 'product' )
				{
					echo $prepend;

					// 'orderby' => 'term_id': Fixes empty category when category and parent are not listed in the correct order
					if ( $terms = wp_get_object_terms( $post->ID, 'product_cat', array( 'orderby' => 'term_id' ) ) )
					{

						$term = end( $terms );
						$parents = array();
						$parent = $term->parent;

						while ( $parent )
						{
							$parents[] = $parent;
							$new_parent = get_term_by( 'id', $parent, 'product_cat' );
							$parent = $new_parent->parent;
						}

						if ( !empty( $parents ) )
						{
							$parents = array_reverse( $parents );

							foreach ( $parents as $parent )
							{
								$item = get_term_by( 'id', $parent, 'product_cat' );
								echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
									get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a></li>';
							}
						}
						//hide category in shop breadcrumbs
						//echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						//	get_term_link( $term->slug, 'product_cat' ) . '">' . $term->name . '</a></li>';
						
					}
					if ( $args['show_current'] )
					{
						echo '<li>' . get_the_title() . '</li>';
					}
				}

				elseif ( get_post_type() != 'post' )
				{
					$post_type = get_post_type_object( get_post_type() );
					$slug = $post_type->rewrite;

					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '/' . $slug[ 'slug' ] . '/">' . $post_type->labels->singular_name . '</a></li>';

					if ( $args['show_current'] )
					{
						echo '<li>' . get_the_title() . '</li>';
					}
				}
				else
				{
					if ( 'post' == get_post_type() )
					{

						// If we are on the posts page and static page is set for blog, add the Post page name
						if ( 'page' == get_option( 'show_on_front' ) )
						{

							$posts_page = get_option( 'page_for_posts' );
							if ( $posts_page && $posts_page != '' && is_numeric( $posts_page ) )
							{
								$page = get_page( $posts_page );

								echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="' . esc_attr( get_the_title( $posts_page ) ) . '" href="' . esc_url( get_permalink( $posts_page ) ) . '">' . get_the_title( $posts_page ) . '</a></li>';
							}
						}
					}


					// Show category name
					$cat = get_the_category();
					$cat = $cat[ 0 ];
					$cats = get_category_parents( $cat, true, '|zn_preg|' );
					if ( !empty( $cats ) && !is_wp_error( $cats ) )
					{
						$cats = explode( '|zn_preg|', $cats );
						foreach ( $cats as $s_cat )
						{
							if ( !empty ( $s_cat ) )
							{
								$s_cat = str_replace( '<a', '<a property="item" typeof="WebPage" ', $s_cat );
								echo '<li property="itemListElement" typeof="ListItem">' . $s_cat . '</li>';
							}
						}
					}
					if ( $args['show_current'] )
					{
						// Show post name
						echo '<li>' . get_the_title() . '</li>';
					}
				}
			}
			elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() )
			{
				$post_type = get_post_type_object( get_post_type() );
				if ( !empty ( $post_type->labels->singular_name ) )
				{
					echo '<li>' . $post_type->labels->singular_name . '</li>';
				}
			}
			elseif ( is_attachment() )
			{
				$parent = get_post( $post->post_parent );
				$cat = get_the_category( $parent->ID );
				if ( !empty( $cat ) )
				{
					$cat = $cat[ 0 ];
					$cats = get_category_parents( $cat, true, ' ' . $args['delimiter'] . ' ' );
					if ( !empty( $cats ) && !is_wp_error( $cats ) )
					{
						echo $cats;
					}
					echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>';
					echo '<li>' . get_the_title() . '</li>';
				}
				else
				{
					echo '<li>' . get_the_title() . '</li>';
				}
			}
			elseif ( is_page() && !is_subpage() )
			{
				if ( $args['show_current'] )
				{
					echo '<li>' . get_the_title() . '</li>';
				}
			}
			elseif ( is_page() && is_subpage() )
			{
				$parent_id = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id )
				{
					$page = get_post( $parent_id );
					$breadcrumbs[] = '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a></li>';
					$parent_id = $page->post_parent;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );

				for ( $i = 0; $i < count( $breadcrumbs ); $i++ )
				{
					echo $breadcrumbs[ $i ];
				}

				if ( $args['show_current'] )
				{
					echo '<li>' . get_the_title() . '</li>';
				}
			}
			elseif ( is_tag() )
			{
				echo '<li>' . __( "Posts tagged ", 'zn_framework' ) . '"' . single_tag_title( '', false ) . '"</li>';
			}
			elseif ( is_author() )
			{
				global $author;
				$userdata = get_userdata( $author );
				echo '<li>' . __( "Author ", 'zn_framework' ) . ( isset( $userdata->display_name ) ? $userdata->display_name : '' ) . '</li>';
			}
			elseif ( is_404() )
			{
				echo '<li>' . __( "Error 404 ", 'zn_framework' ) . '</li>';
			}
			if ( get_query_var( 'paged' ) )
			{
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				{
					echo ' (';
				}
				echo '<li>' . __( 'Page', 'zn_framework' ) . ' ' . get_query_var( 'paged' ) . '</li>';
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				{
					echo ')';
				}
			}
			echo '</ul>';
		}
	}
}



