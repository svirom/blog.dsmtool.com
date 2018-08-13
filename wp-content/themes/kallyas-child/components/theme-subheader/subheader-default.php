<?php if(! defined('ABSPATH')){ return; } ?>

<?php if ( ( is_category(9) ) || ( is_category(1005) ) ) { ?>
<div id="page_header" class="page-subheader newstips_category <?php echo implode(' ', $extra_classes); ?>">
<?php
} elseif ( ( is_category() ) && ( !is_category(1005) ) && ( !is_category(9) ) ) { ?>
<div id="page_header" class="page-subheader regular_category <?php echo implode(' ', $extra_classes); ?>">
<?php
} elseif ( is_author() ) { ?>
<div id="page_header" class="page-subheader author_category <?php echo implode(' ', $extra_classes); ?>">
<?php
} elseif ( is_woocommerce() || is_checkout() || is_cart() ) { ?>
<div id="page_header" class="page-subheader woocommerce_subheader <?php echo implode(' ', $extra_classes); ?>">
<?php    
} else { ?> 
<div id="page_header" class="page-subheader <?php echo implode(' ', $extra_classes); ?>">
<?php
} ?>  

    <div class="bgback"></div>

    <?php
    $bg_source = $args['bg_source'];
    if ( !empty( $bg_source ) && is_array( $bg_source ) ) {
        WpkPageHelper::zn_background_source( $bg_source );
    }
    ?>

    <div class="th-sparkles"></div>

    <!-- DEFAULT HEADER STYLE -->
    <div class="ph-content-wrap">
        <div class="ph-content-v-center">
            <div>
                <div class="container">
                    <div class="row">
                        <?php

                        $args_def_header_title = $args['def_header_title'] != '' ? $args['def_header_title'] : $show_title;
                        $args_def_header_subtitle = isset( $args['show_subtitle'] ) && $args['show_subtitle'] != '' ? $args['show_subtitle'] : $show_subtitle;

                        $tit_sub = $args_def_header_title || ($args_def_header_subtitle && !empty ( $args['subtitle'] ));

                        $def_cols = (!$br_date || !$tit_sub) ? 12 : 6;

                        if($br_date){
                        ?>
                        <div class="col-sm-<?php echo $def_cols; ?>">
                            <?php

                            if ( $args_def_header_bread ) {
                                // Use the bb breadcrumb if BBPress is installed and current page is inside the forums
                                if(function_exists('is_bbpress') && is_bbpress()) {
                                    echo bbp_get_breadcrumb();
                                }
                                else {
                                    zn_breadcrumbs();
                                }
                            }
                            else {
                                echo '&nbsp;';
                            }
                            if ( $args_def_header_date) {
                                echo '<span id="current-date" class="subheader-currentdate hidden-xs">' .
                                     date_i18n( get_option( 'date_format' ), strtotime( date( "l M d, Y" ) . get_option( 'gmt_offset' ) ), false ) . '</span>';
                            }
                            else {
                                echo '&nbsp;';
                            }
                            ?>
                            <div class="clearfix"></div>
                        </div>
                        <?php } ?>

                        <?php if( $tit_sub ){  ?>
                        <div class="col-sm-<?php echo $def_cols; ?>">
                            <?php 
                            if ( ( ( is_category(9) ) || ( in_category(9) ) ) && !is_tag() ) { ?>
                            <div class="subheader-titles news_titles">
                                <?php
                                if ( $args_def_header_title ) {
                                        echo '<' . $title_heading . ' class="subheader-maintitle news_subheader" '.WpkPageHelper::zn_schema_markup('title').'>news</' . $title_heading . '>';
                                        echo '<p class="news_p">Latest News from Dropshipping World</p>';
                                }
                                ?>
                            </div>
                            <?php 
                            } elseif ( ( ( is_category(1005) ) || ( in_category(1005) ) ) && !is_tag() ) { ?>
                            <div class="subheader-titles tips_titles">
                                <?php
                                if ( $args_def_header_title ) {
                                        echo '<' . $title_heading . ' class="subheader-maintitle tips_subheader" '.WpkPageHelper::zn_schema_markup('title').'>tips</' . $title_heading . '>';
                                        echo '<p class="tips_p">Get the Latest Tips from Drop Shipping</p>';
                                }
                                ?>
                            </div>
                            <?php
                            } elseif ( is_author() ) { ?>
                            <div class="subheader-titles">
                                <?php
                                global $author;
                                $userdata = get_userdata( $author );
                                if ( $args_def_header_title ) {
                                        echo '<' . $title_heading . ' class="subheader-maintitle" '.WpkPageHelper::zn_schema_markup('title').'>'.$userdata->first_name.' '.$userdata->last_name.'</' . $title_heading . '>';
                                }
                                ?>
                            </div>
                            <?php
                            } else { ?> 
                            <div class="subheader-titles">
                                <?php
                                if ( $args_def_header_title ) {
                                    echo '<' . $title_heading . ' class="subheader-maintitle" '.WpkPageHelper::zn_schema_markup('title').'>' . $args['title'] . '</' . $title_heading . '>';
                                }

                                if ( $args_def_header_subtitle && !empty ( $args['subtitle'] ) ) {
                                    echo '<' . $subtitle_tag . ' class="subheader-subtitle" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . do_shortcode( $args['subtitle'] ) . '</' . $subtitle_tag . '>';
                                }
                                ?>
                            </div>    
                            <?php
                            } ?>  
                        </div>
                        <?php } ?>
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>
    <?php
        $bottommask_bg = (isset($args['bottommask_bg']) && !empty($args['bottommask_bg'])) ? $args['bottommask_bg'] : '';
        $bottommask_bgImage = (isset($args['bottom_mask_bg_image']) && !empty($args['bottom_mask_bg_image'])) ? $args['bottom_mask_bg_image'] : null;
        $bottommask_bgHeight = (isset($args['bottom_mask_bg_height']) && !empty($args['bottom_mask_bg_height'])) ? $args['bottom_mask_bg_height'] : 100;

        zn_bottommask_markup(
            $bottom_mask,
            $bottommask_bg,
            'bottom',
            $bottommask_bgImage,
            $bottommask_bgHeight
        );
    ?>
</div>

