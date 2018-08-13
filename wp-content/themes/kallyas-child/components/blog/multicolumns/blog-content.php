<?php if(! defined('ABSPATH')){ return; }
	/**
		* Multi-columns content
	*/
	
	if( !empty($current_post['content']) ) { ?>
    <div class="itemBody kl-blog-item-body" <?php echo WpkPageHelper::zn_schema_markup('post_content'); ?>>
        <div class="itemIntroText kl-blog-item-content">
            <?php echo $current_post['content']; ?>
		</div>
        <!-- end Item Intro Text -->
        <div class="clearfix"></div>
		<div class="blog-item-descr-readmore">
			<a href="<?php the_permalink(); ?>" class="blog-item-descr-readmore-a" title="<?php the_title(); ?>"><?php echo esc_attr(__('More', 'zn_framework')); ?></a>
		</div>
	</div>
    <!-- end Item BODY -->
	<?php
	}
