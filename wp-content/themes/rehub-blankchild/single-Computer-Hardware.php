<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/*
 * Template Name: Computer-Hardware Post Template
 * Template Post Type: Computer-Hardware
 */
 ?>
<?php get_header(); ?>
<?php global $post;?>
<?php /* $rh_post_layout_style = get_post_meta($post->ID, '_post_layout', true);?>
<?php if ($rh_post_layout_style == '') {$rh_post_layout_style = rehub_option('post_layout_style');} ?>
<?php if ($rh_post_layout_style == '') :?>
    <?php       
    if (REHUB_NAME_ACTIVE_THEME == 'RECASH') {
        $rh_post_layout_style = 'meta_compact'; 
    }
    elseif (REHUB_NAME_ACTIVE_THEME == 'REPICK') {
        $rh_post_layout_style = 'corner_offer';
    }
    elseif (REHUB_NAME_ACTIVE_THEME == 'RETHING') {
        $rh_post_layout_style = 'meta_center';
    }
    elseif (REHUB_NAME_ACTIVE_THEME == 'REVENDOR') {
        $rh_post_layout_style = 'meta_outside';
    }   
    elseif (REHUB_NAME_ACTIVE_THEME == 'REDIRECT') {
        $rh_post_layout_style = 'meta_compact_dir';
    }           
    elseif (REHUB_NAME_ACTIVE_THEME == 'REWISE') {
        $rh_post_layout_style = 'default';
    }                           
    else{
        $rh_post_layout_style = 'default';       
    }?>
<?php endif;
?>


<?php if(true) : ?>
    <?php include('post_layout/computer-hardware-layout.php'); ?>
<?php  ;?>    
    <?php include(rh_locate_template('inc/post_layout/single-default.php')); ?>
<?php elseif($rh_post_layout_style == 'default_text_opt') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-default-readopt.php')); ?>    
<?php elseif($rh_post_layout_style == 'meta_outside') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-meta-outside.php')); ?> 
<?php elseif($rh_post_layout_style == 'meta_center') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-meta-center.php')); ?> 
<?php elseif($rh_post_layout_style == 'meta_compact') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-meta-compact.php')); ?>
<?php elseif($rh_post_layout_style == 'meta_compact_dir') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-meta-compact-dir.php')); ?>   
<?php elseif($rh_post_layout_style == 'corner_offer') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-corner-offer.php')); ?>
<?php elseif($rh_post_layout_style == 'meta_in_image') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-inimage.php')); ?>
<?php elseif($rh_post_layout_style == 'meta_in_imagefull') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-inimagefull.php')); ?>
<?php elseif($rh_post_layout_style == 'meta_ce_compare') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-ce-compare.php')); ?>  
<?php elseif($rh_post_layout_style == 'meta_ce_compare_full') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-ce-compare-full.php')); ?>  
<?php elseif($rh_post_layout_style == 'meta_ce_compare_auto') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-ce-compare-fullauto.php')); ?>
<?php elseif($rh_post_layout_style == 'big_post_offer') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-big-offer.php')); ?>    
<?php elseif($rh_post_layout_style == 'meta_ce_compare_auto_sec') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-ce-compare-autocontent.php')); ?>      
<?php elseif($rh_post_layout_style == 'offer_and_review') : ?>
    <?php include(rh_locate_template('inc/post_layout/single-offer-reviewscore.php')); ?>       
<?php else:?>
    <?php include(rh_locate_template('inc/post_layout/single-default.php')); ?>                               
<?php  ;?>    
<?php endif; */?>
<?php //########################################## ?>
<?php //########################################## ?>
<?php //########################################## ?>
<?php //########################################## ?>
<!-- CONTENT -->
<div class="rh-container"> 
    <div class="rh-content-wrap clearfix">
        <!-- Main Side -->
        <div class="main-side single post-readopt full_width clearfix">            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="post <?php $category = get_the_category($post->ID); if ($category) {$first_cat = $category[0]->term_id; echo 'category-'.$first_cat.'';} ?>" id="post-<?php the_ID(); ?>">               
                    <!-- Title area -->
                    <div class="rh_post_layout_metabig">
                        <div class="title_single_area">
                            <?php if(rehub_option('compare_btn_single') !='' && is_singular('post')) :?>
                                <?php $cmp_btn_args = array();?>
                                <?php if(rehub_option('compare_btn_cats') != '') {
                                    $cmp_btn_args['cats'] = esc_html(rehub_option('compare_btn_cats'));
                                }?>
                                <?php echo wpsm_comparison_button($cmp_btn_args); ?> 
                            <?php endif;?>
                            <?php 
                                $crumb = '';
                                if( function_exists( 'yoast_breadcrumb' ) ) {
                                    $crumb = yoast_breadcrumb('<div class="breadcrumb">','</div>', false);
                                }
                                if( ! is_string( $crumb ) || $crumb === '' ) {
                                    if(rehub_option('rehub_disable_breadcrumbs') == '1' || vp_metabox('rehub_post_side.disable_parts') == '1') {echo '';}
                                    elseif (function_exists('dimox_breadcrumbs')) {
                                        dimox_breadcrumbs(); 
                                    }
                                }
                                echo $crumb;  
                            ?> 
                            <?php echo re_badge_create('labelsmall'); ?><?php rh_post_header_cat('post', true);?>                        
                            <h1><?php the_title(); ?></h1>                                                           

 
                                                       
                        </div>
                    </div>
                    <?php if(rehub_option('rehub_single_after_title')) : ?><div class="mediad mediad_top"><?php echo do_shortcode(rehub_option('rehub_single_after_title')); ?></div><div class="clearfix"></div><?php endif; ?>                         
                    <div class="feature-post-section">
                        <?php /* ?>
                        <div class="post-meta-left">
                            <?php $author_id=$post->post_author; ?>
                            <a href="<?php echo get_author_posts_url( $author_id ) ?>" class="redopt-aut-picture">
                                <?php echo get_avatar( $author_id, '100' ); ?>                   
                            </a>
                            <a href="<?php echo get_author_posts_url( $author_id ) ?>" class="redopt-aut-link">             
                                <?php the_author_meta( 'display_name', $author_id ); ?>         
                            </a>
                            <div class="date_time_post"><?php the_time(get_option( 'date_format' )); ?></div>
                        <?php */ ?>
                            <?php if(rehub_option('rehub_disable_share_top') =='1' || vp_metabox('rehub_post_side.disable_parts') == '1')  : ?>
                            <?php else :?>
                                <div class="top_share">
                                    <?php include(rh_locate_template('inc/parts/post_share.php')); ?>
                                </div>
                                <div class="clearfix"></div> 
                            <?php endif; ?>                                     
                    <!--    </div> -->
                        <?php include(rh_locate_template('inc/parts/top_image.php')); ?>  
                    </div>                                                            
                    <?php if(rehub_option('rehub_single_before_post') && vp_metabox('rehub_post_side.show_banner_ads') != '1') : ?><div class="mediad mediad_before_content"><?php echo do_shortcode(rehub_option('rehub_single_before_post')); ?></div><?php endif; ?>

                    <div class="post-inner"><?php the_content(); ?></div>


                </article>
                <div class="clearfix"></div>
                <?php include(rh_locate_template('inc/post_layout/single-common-footer.php')); ?>                    
            <?php endwhile; endif; ?>
            <?php comments_template(); ?>
        </div>  
        <!-- /Main Side -->  
    </div>
</div>
<!-- /CONTENT -->         


<!-- FOOTER -->
<?php  get_footer(); ?>
