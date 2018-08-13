<?php if(! defined('ABSPATH')){ return; }
	/**
		* Multi-columns image
	*/
	
if( ! empty( $current_post['media'] ) ) : ?>
<div class="itemThumbnail kl-blog-item-thumbnail">
	<?php echo $current_post['media']; ?>
	<div class="overlay kl-blog-item-overlay">
		<div class="overlay__inner kl-blog-item-overlay-inner">
			<a href="<?php the_permalink(); ?>" class="readMore kl-blog-item-overlay-more" title="<?php the_title(); ?>" data-readmore="<?php echo esc_attr(__('Read More', 'zn_framework')); ?>"></a>
		</div>
		
	</div>
	<?php $custom_line = get_field('line_appear_on_archive_page'); if ($custom_line) : ?>
	<div class="line-appear-on-archive-page"> <?php the_field('line_appear_on_archive_page'); ?> </div>
	<?php endif; ?>
	<?php if( function_exists('pvc_get_post_views')): ?>
	<div class="blog-post-count-on-cat-page"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo pvc_get_post_views( $current_post['id'] ); ?></div>
	<?php endif; ?>
</div>
<?php
	endif;
