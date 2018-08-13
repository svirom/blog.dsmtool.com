<?php if(! defined('ABSPATH')){ return; }
/**
 * Template layout for author's page
 * @package  Kallyas
 * @author   Team Hogash
 */
get_header();

$args = array();
$override_page_title = zget_option( 'zn_override_single_title', 'blog_options' );
if( 'yes' === $override_page_title ){
	$args['title'] = zget_option( 'single_page_title', 'blog_options' );
}

/*** USE THE NEW HEADER FUNCTION **/
WpkPageHelper::zn_get_subheader( $args );


// Check to see if the page has a sidebar or not
$main_class = zn_get_sidebar_class('single_sidebar');
if( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false ) { $zn_config['sidebar'] = true; } else { $zn_config['sidebar'] = false; }
$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
$content_size = 12 - (int)$sidebar_size;
$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-'.$content_size : 'col-sm-12';
?>

	<section id="content" class="site-content">
		<div class="container">
			<div class="row">

				<!--// Main Content: page content from WP_EDITOR along with the appropriate sidebar if one specified. -->
				<div class="<?php echo $main_class;?> author_page" <?php echo WpkPageHelper::zn_schema_markup('main'); ?>>
					<div id="th-content-post">
						 <?php
					    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
					    ?>
					    <div class="author_info">
						    <div class="author_photo">
						    <?php echo get_avatar( $curauth->user_email , '120 '); ?>
						    </div>
						    <h3><?php echo $curauth->pad_caption; ?></h3>
						    <?php if ( $curauth->facebook ) { ?>
						    	<a href="<?php echo $curauth->facebook; ?>" class="author_fb"><i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i></a>
						    <?php } ?>
						    <?php if ( $curauth->pad_twitter ) { ?>
						    	<a href="<?php echo $curauth->pad_twitter; ?>" class="author_twitter"><i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i></a>
						    <?php } ?>
						    <?php if ( $curauth->pad_instagram ) { ?>
						    	<a href="<?php echo $curauth->pad_instagram; ?>" class="author_instagram"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a>
						    <?php } ?>
						    <?php if ( $curauth->pad_linkedin ) { ?>
						    	<a href="<?php echo $curauth->pad_linkedin; ?>" class="author_linkedin"><i class="fa fa-linkedin-square fa-2x" aria-hidden="true"></i></a>
						    <?php } ?>
						    <?php if ( $curauth->pad_youtube ) { ?>
						    	<a href="<?php echo $curauth->pad_youtube; ?>" class="author_youtube"><i class="fa fa-youtube-play fa-2x" aria-hidden="true"></i></a>
						    <?php } ?>
						    <?php if ( $curauth->user_url ) { ?>	
						    <p class="author_website">Website: <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></p>
						    <?php } ?>
					    </div>
					    <h3>Author's biography:</h3> 
					    <p><?php echo $curauth->description; ?></p>
					        
					    <h3 class="authors_latestPosts">Latest Posts by <?php echo $curauth->display_name; ?>:</h3>

					    <div class="row">
					<!-- The Loop -->

					    <?php
					    $author_query = new WP_Query( 
					    	array(
					    		'posts_per_page' => 9,
					    		'author' => $curauth->ID,
					    	)
					    );
					    if ( $author_query->have_posts() ) : while ( $author_query->have_posts() ) : $author_query->the_post(); ?>
					        <div class="col-sm-12 col-md-4 author_post">
					        	<?php echo the_post_thumbnail('full'); ?>
					            <h4>
					            	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a>
					            </h4>
					            <?php the_time('d M Y'); ?> in <?php the_category('&');?>
					            <?php the_excerpt(); ?>
					        </div>

					    <?php endwhile; else: ?>
					        <p><?php _e('No posts by this author.'); ?></p>

					    <?php endif; ?>

					<!-- End Loop -->

					    </div>
					    <h3 class="authors_latestPosts">More DSMagazine Authors:</h3>
					    <ul class="authors_list">
						<?php 
					    $blogusers = get_users_of_blog();
					    $excluded_users = array(11);
			            if ($blogusers) {
			              foreach ($blogusers as $bloguser) {
			              	$post_count = count_user_posts($bloguser->user_id);
			              	
			              	if ( (!in_array($bloguser->user_id, $excluded_users)) && ($post_count) ) {
			                /*if (($bloguser->user_id) && ($post_count)) {*/
			                  	$user = get_userdata($bloguser->user_id);
			                  	if ($user != $curauth) { ?>
				                  	<li class="author_item">
			                  			<a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo get_avatar($user->ID, '24'); ?><?php echo $user->user_firstname; ?> <?php echo $user->user_lastname; ?></a>            
				                  	</li>
				                  	<?php
			              		}
			                }
			              }
			            }
						?>
						</ul>
					</div><!--// #th-content-post -->
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section><!--// #content -->
<?php
get_footer();
