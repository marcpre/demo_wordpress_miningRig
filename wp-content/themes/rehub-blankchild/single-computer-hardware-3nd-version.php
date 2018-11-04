<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<?php
/*
 * Template Name: DO NOT USE 3rd Version Computer Hardware Post Template
 * Template Post Type: Computer-Hardware
 */ ?>
<?php// global $product, $post;?>
<?php // $itemsync = $syncitem = $youtubecontent = '';?>
<?php if (rh_is_plugin_active('content-egg/content-egg.php')):?>
    <?php $module_id = get_post_meta($post->ID, '_rehub_module_ce_id', true); ?>
    <?php $unique_id = get_post_meta($post->ID, '_rehub_product_unique_id', true); ?>
    <?php if ($unique_id && $module_id): ?>
        <?php $itemsync = \ContentEgg\application\components\ContentManager::getProductbyUniqueId($unique_id, $module_id, $post->ID); ?>
    <?php endif; ?>



    <?php // $itemsync = \ContentEgg\application\WooIntegrator::getSyncItem($post->ID);?>
    <?php $unique_id = $module_id = $domain = $merchant = $syncitem = '';?>
    <?php if(!empty($itemsync)):?>
        <?php
        $unique_id = $itemsync['unique_id'];
        $module_id = $itemsync['module_id'];
        $domain = $itemsync['domain'];
        $merchant = $itemsync['merchant'];
        $syncitem = $itemsync;
        ?>
    <?php endif;?>
    <?php $postid = $post->ID;?>
    <?php $youtubecontent = \ContentEgg\application\components\ContentManager::getViewData('Youtube', $postid);?>
<?php endif;?>
    <!-- CONTENT -->
    <div class="rh-container">
        <div class="rh-content-wrap clearfix">
            <div id="contents-section-woo-area" class="rh-stickysidebar-wrapper">
                <div class="ce_woo_auto_sections ce_woo_blocks ce_woo_list main-side rh-sticky-container clearfix <?php echo (is_active_sidebar( 'sidebarwooinner' )) ? 'woo_default_w_sidebar' : 'full_width woo_default_no_sidebar'; ?>" id="content">
                    <div class="post">
                        <?php do_action( 'woocommerce_before_main_content' );?>
                        <?php if (rh_is_plugin_active('content-egg/content-egg.php')):?>
                            <?php $amazonupdate = get_post_meta($postid, \ContentEgg\application\components\ContentManager::META_PREFIX_LAST_ITEMS_UPDATE.'Amazon', true);?>
                            <div class="floatright pl20">
                                <?php $product_update = \ContentEgg\application\helpers\TemplateHelper::getLastUpdateFormatted('Amazon', $postid);?>
                                <?php if($amazonupdate && $product_update):?>
                                    <div class="font60 lineheight20"><?php _e('Last price update was:', 'rehub_framework');?> <?php echo $product_update;?> <span class="csspopuptrigger" data-popup="ceblocks-amazon-disclaimer"><i class="fa fa-question-circle-o greycolor font90"></i></span></div>
                                    <div class="csspopup" id="ceblocks-amazon-disclaimer">
                                        <div class="csspopupinner">
                                            <span class="cpopupclose">×</span>
                                            <?php _e('Product prices and availability are accurate as of the date/time indicated and are subject to change. Any price and availability information displayed on Amazon at the time of purchase will apply to the purchase of this product.', 'rehub_framework');?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            </div>
                        <?php endif;?>
                        <?php // woocommerce_breadcrumb();?>

                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php
                            do_action( 'woocommerce_before_single_product' );
                            if ( post_password_required() ) {
                                echo get_the_password_form();
                                return;
                            }
                            ?>
                            <div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <div class="ce_woo_block_top_holder">
                                    <div class="woo_bl_title flowhidden mb10">
                                        <div class="floatleft tabletblockdisplay pr20 rtlpr20">
                                            <h1 class="<?php if(rehub_option('wishlist_disable') !='1') :?><?php echo getHotIconclass($post->ID, true); ?><?php endif ;?>"><?php the_title(); ?></h1>
                                            <?php echo wpsm_reviewbox(array('compact'=>'text', 'id'=> $post->ID, 'scrollid'=>'tab-title-description'));?>
                                        </div>
                                        <div class="woo-top-actions tabletblockdisplay floatright">
                                            <div class="woo-button-actions-area pl5 pb5 pr5">
                                                <?php $wishlistadd = __('Add to wishlist', 'rehub_framework');?>
                                                <?php $wishlistadded = __('Added to wishlist', 'rehub_framework');?>
                                                <?php $wishlistremoved = __('Removed from wishlist', 'rehub_framework');?>
                                                <?php echo RH_get_wishlist($post->ID, $wishlistadd, $wishlistadded, $wishlistremoved);?>
                                                <?php if(rehub_option('woo_rhcompare') == 1) {echo wpsm_comparison_button(array('class'=>'rhwoosinglecompare'));} ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-grey-bottom clearfix"></div>

                                    <div class="wpsm-one-third wpsm-column-first pt20 tabletblockdisplay compare-full-images modulo-lightbox mb30">
                                        <?php
                                        wp_enqueue_script('modulobox');
                                        wp_enqueue_style('modulobox');
                                        ?>
                                        <figure class="text-center">
                                            <?php  $badge = get_post_meta($post->ID, 'is_editor_choice', true); ?>
                                            <?php if ($badge !='' && $badge !='0') :?>
                                                <?php echo re_badge_create('ribbon'); ?>
                                            <?php else:?>
                                                <?php // woocommerce_show_product_sale_flash();?>
                                            <?php endif;?>
                                            <?php
                                            $image_id = get_post_thumbnail_id($post->ID);
                                            $image_url = wp_get_attachment_image_src($image_id,'full');
                                            $image_url = $image_url[0];
                                            ?>
                                            <a data-rel="rh_top_gallery" href="<?php echo $image_url;?>" target="_blank" data-thumb="<?php echo $image_url;?>">
                                                <?php WPSM_image_resizer::show_static_resized_image(array('lazy'=>true, 'thumb'=> true, 'crop'=> false, 'height'=> 350, 'no_thumb_url' => get_template_directory_uri() . '/images/default/noimage_500_500.png'));?>
                                            </a>
                                        </figure>
                                        <?php $post_image_gallery = $product->get_gallery_image_ids();?>
                                        <?php if(!empty($post_image_gallery)) :?>
                                            <div class="rh-flex-eq-height rh_mini_thumbs compare-full-thumbnails mt15 mb15">
                                                <?php foreach($post_image_gallery as $key=>$image_gallery):?>
                                                    <?php if(!$image_gallery) continue;?>
                                                    <a data-rel="rh_top_gallery" data-thumb="<?php echo wp_get_attachment_url($image_gallery);?>" href="<?php echo wp_get_attachment_url($image_gallery);?>" target="_blank" class="rh-flex-center-align mb10" data-title="<?php echo esc_attr(get_post_field( 'post_excerpt', $image_gallery));?>">
                                                        <?php WPSM_image_resizer::show_static_resized_image(array('lazy'=>false, 'src'=> wp_get_attachment_url($image_gallery), 'crop'=> false, 'height'=> 60));?>
                                                    </a>
                                                <?php endforeach;?>
                                                <?php if(!empty($youtubecontent)):?>
                                                    <?php foreach($youtubecontent as $videoitem):?>
                                                        <a href="<?php echo $videoitem['url'];?>" data-rel="rh_top_gallery" target="_blank" class="rh-flex-center-align mb10 rh_videothumb_link" data-poster="<?php echo parse_video_url($videoitem['url'], 'hqthumb'); ?>" data-thumb="<?php echo $videoitem['img']?>">
                                                            <img src="<?php echo $videoitem['img']?>" alt="<?php echo $videoitem['title']?>" />
                                                        </a>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            </div>
                                        <?php else :?>
                                            <?php if (rh_is_plugin_active('content-egg/content-egg.php')):?>
                                                <?php if (!empty($itemsync['extra']['imageSet'])){
                                                    $ceimages = $itemsync['extra']['imageSet'];
                                                }elseif (!empty($itemsync['extra']['images'])){
                                                    $ceimages = $itemsync['extra']['images'];
                                                }
                                                else {
                                                    $qwantimages = \ContentEgg\application\components\ContentManager::getViewData('GoogleImages', $post->ID);
                                                    if(!empty($qwantimages)) {
                                                        $ceimages = wp_list_pluck( $qwantimages, 'img' );
                                                    }else{
                                                        $ceimages = '';
                                                    }
                                                } ?>
                                                <?php if(!empty($ceimages)):?>
                                                    <div class="rh_mini_thumbs compare-full-thumbnails limited-thumb-number mt15 mb15">
                                                        <?php foreach ($ceimages as $key => $gallery_img) :?>
                                                            <?php if (isset($gallery_img['LargeImage'])){
                                                                $image = $gallery_img['LargeImage'];
                                                            }else{
                                                                $image = $gallery_img;
                                                            }?>
                                                            <a data-thumb="<?php echo $image?>" data-rel="rh_top_gallery" href="<?php echo $image; ?>" data-title="<?php echo  $itemsync['title'];?>" class="rh-flex-center-align mb10">
                                                                <?php WPSM_image_resizer::show_static_resized_image(array('src'=> $image, 'height'=> 65, 'title' => $itemsync['title'], 'no_thumb_url' => get_template_directory_uri().'/images/default/noimage_100_70.png'));?>
                                                            </a>
                                                        <?php endforeach;?>
                                                        <?php if(!empty($youtubecontent)):?>
                                                            <?php foreach($youtubecontent as $videoitem):?>
                                                                <a href="<?php echo $videoitem['url'];?>" data-rel="rh_top_gallery" target="_blank" class="mb10 rh-flex-center-align rh_videothumb_link" data-poster="<?php echo parse_video_url($videoitem['url'], 'hqthumb'); ?>" data-thumb="<?php echo $videoitem['img']?>">
                                                                    <img src="<?php echo $videoitem['img']?>" alt="<?php echo $videoitem['title']?>" />
                                                                </a>
                                                            <?php endforeach;?>
                                                        <?php endif;?>
                                                    </div>
                                                <?php endif;?>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                    <div class="wpsm-two-third rh-line-left pl20 rtlpr20 pt15 tabletblockdisplay wpsm-column-last mb30 disablemobileborder disablemobilepadding">

                                        <div class="rh-flex-center-align floatleft">
                                            <?php if ( 'no' !== get_option( 'woocommerce_enable_review_rating' ) ):?>
                                                <div class="floatleft mr15">
                                                    <?php $rating_count = $product->get_rating_count();?>
                                                    <?php if ($rating_count < 1):?>
                                                        <span data-scrollto="#reviews" class="rehub_scroll cursorpointer font80 greycolor"><?php _e("Add your review", "rehub_framework");?></span>
                                                    <?php else:?>
                                                        <?php woocommerce_template_single_rating();?>
                                                    <?php endif;?>
                                                </div>
                                            <?php endif;?>
                                            <span class="floatleft meta post-meta mt0 mb0">
                                            <?php
                                            if(rehub_option('post_view_disable') != 1){
                                                $rehub_views = get_post_meta ($post->ID,'rehub_views',true);
                                                echo '<span class="greycolor postview_meta mr10">'.$rehub_views.'</span>';
                                            }
                                            $categories = wp_get_post_terms($post->ID, 'product_cat', array("fields" => "all"));
                                            $separator = '';
                                            $output = '';
                                            if ( ! empty( $categories ) ) {
                                                foreach( $categories as $category ) {
                                                    $output .= '<a class="mr5 ml5 rh-cat-'.$category->term_id.'" href="' . esc_url( get_term_link( $category->term_id, 'product_cat' ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'rehub_framework' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
                                                }
                                                echo trim( $output, $separator );
                                            }
                                            ?>
                                        </span>

                                        </div>
                                        <div class="floatright ml20">
                                            <?php if ($unique_id && $module_id && !empty($syncitem)) :?>
                                                <?php include(rh_locate_template( 'inc/parts/pricealertpopup.php' ) ); ?>
                                            <?php endif;?>
                                        </div>
                                        <div class="rh-line mb15 mt15"></div>
                                        <div class="rh_post_layout_rev_price_holder">
                                            <?php if($syncitem == ''){echo 'This Post layout works only with Content Egg';}?>
                                            <?php echo do_shortcode('[content-egg-block template=custom/all_simple_list]');?>
                                            <span class="rehub-main-font font80 mb20 rehub_scroll blockstyle rehub-main-color text-right-align cursorpointer" data-scrollto="#section-woo-ce-pricelist"><?php _e('Check all prices', 'rehub_framework');?></span>
                                            <?php rh_woo_code_zone('button');?>
                                        </div>
                                        <div class="mt10">
                                            <div class="font90 lineheight20 woo_desc_part">
                                                <?php rh_woo_code_zone('content');?>
                                                <?php if(has_excerpt($post->ID)):?>
                                                    <?php woocommerce_template_single_excerpt();?>
                                                <?php else :?>
                                                    <?php if(!empty($itemsync['extra']['itemAttributes']['Feature'])){
                                                        $features = $itemsync['extra']['itemAttributes']['Feature'];
                                                    }
                                                    elseif(!empty($itemsync['extra']['keySpecs'])){
                                                        $features = $itemsync['extra']['keySpecs'];
                                                    }
                                                    ?>
                                                    <?php if (!empty ($features)) :?>
                                                        <ul class="featured_list mt0">
                                                            <?php $length = $maxlength = 0;?>
                                                            <?php foreach ($features as $k => $feature): ?>
                                                                <?php if(is_array($feature)){continue;}?>
                                                                <?php $length = strlen($feature); $maxlength += $length; ?>
                                                                <li><?php echo $feature; ?></li>
                                                                <?php if($k >= 5 || $maxlength > 200) break; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else:?>
                                                        <?php echo do_shortcode('[content-egg-block template=price_statistics]');?>
                                                    <?php endif ;?>
                                                    <div class="clearfix"></div>
                                                <?php endif;?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="woo-single-meta font80">
                                            <?php do_action( 'woocommerce_product_meta_start' ); ?>
                                            <?php $term_ids =  wp_get_post_terms(get_the_ID(), 'store', array("fields" => "ids")); ?>
                                            <?php if (!empty($term_ids) && ! is_wp_error($term_ids)) :?>
                                                <div class="woostorewrap mb10">
                                                    <div class="store_tax">
                                                        <?php WPSM_Woohelper::re_show_brand_tax(); //show brand taxonomy?>
                                                    </div>
                                                </div>
                                            <?php endif;?>
                                            <?php do_action( 'woocommerce_product_meta_end' ); ?>
                                        </div>
                                        <div class="top_share notextshare">
                                            <?php woocommerce_template_single_sharing();?>
                                        </div>
                                        <?php
                                        /**
                                         * woocommerce_single_product_summary hook. was removed in theme and added as functions directly in layout
                                         *
                                         * @dehooked woocommerce_template_single_title - 5
                                         * @dehooked woocommerce_template_single_rating - 10
                                         * @dehooked woocommerce_template_single_price - 10
                                         * @dehooked woocommerce_template_single_excerpt - 20
                                         * @dehooked woocommerce_template_single_add_to_cart - 30
                                         * @dehooked woocommerce_template_single_meta - 40
                                         * @dehooked woocommerce_template_single_sharing - 50
                                         * @hooked WC_Structured_Data::generate_product_data() - 60
                                         */
                                        do_action( 'woocommerce_single_product_summary' );
                                        ?>
                                    </div>
                                </div>

                                <?php $tabs = apply_filters( 'woocommerce_product_tabs', array() );

                                if ( ! empty( $tabs ) ) : ?>

                                    <?php if (rh_is_plugin_active('content-egg/content-egg.php')):?>

                                        <?php
                                        $replacetitle = apply_filters('woo_product_section_title', get_the_title().' ');
                                        $tabs['woo-ce-pricelist'] = array(
                                            'title' => $replacetitle.__('Prices', 'rehub_framework'),
                                            'priority' => '12',
                                            'callback' => 'woo_ce_pricelist_output'
                                        );
                                        $tabs['woo-ce-pricehistory'] = array(
                                            'title' => __('Price History', 'rehub_framework'),
                                            'priority' => '13',
                                            'callback' => 'woo_ce_history_output'
                                        );
                                        if(!empty($youtubecontent)){
                                            $tabs['woo-ce-videos'] = array(
                                                'title' => $replacetitle.__('Video Reviews', 'rehub_framework'),
                                                'priority' => '21',
                                                'callback' => 'woo_ce_video_output'
                                            );
                                        }
                                        $googlenews = get_post_meta($post->ID, '_cegg_data_GoogleNews', true);
                                        if(!empty($googlenews)){
                                            $tabs['woo-ce-news'] = array(
                                                'title' => __('World News', 'rehub_framework'),
                                                'priority' => '23',
                                                'callback' => 'woo_ce_news_output'
                                            );
                                        }
                                        uasort( $tabs, '_sort_priority_callback' );
                                        ?>

                                    <?php endif;?>

                                    <?php wp_enqueue_script('customfloatpanel');?>
                                    <div class="flowhidden rh-float-panel" id="float-panel-woo-area">
                                        <div class="rh-container rh-flex-center-align pt10 pb10">
                                            <div class="float-panel-woo-image">
                                                <?php WPSM_image_resizer::show_static_resized_image(array('lazy'=>false, 'thumb'=> true, 'width'=> 50, 'height'=> 50));?>
                                            </div>
                                            <div class="float-panel-woo-info wpsm_pretty_colored rh-line-left pl15 ml15">
                                                <div class="float-panel-woo-title rehub-main-font mb5 font110">
                                                    <?php the_title();?>
                                                </div>
                                                <ul class="float-panel-woo-links list-unstyled list-line-style font80 fontbold lineheight15">
                                                    <?php foreach ( $tabs as $key => $tab ) : ?>
                                                        <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>">
                                                            <?php $tab_title = str_replace($replacetitle, '', $tab['title']);?>
                                                            <a href="#section-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html($tab_title), $key ); ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="float-panel-woo-btn rh-flex-columns rh-flex-right-align">
                                                <div class="float-panel-woo-price rh-flex-center-align font120 rh-flex-right-align">
                                                    <?php woocommerce_template_single_price();?>
                                                </div>
                                                <div class="float-panel-woo-button rh-flex-center-align rh-flex-right-align">
                                                    <a href="#section-woo-ce-pricelist" class="single_add_to_cart_button rehub_scroll">
                                                        <?php if(rehub_option('rehub_btn_text_aff_links') !='') :?>
                                                            <?php echo rehub_option('rehub_btn_text_aff_links') ; ?>
                                                        <?php else :?>
                                                            <?php _e('Choose offer', 'rehub_framework') ?>
                                                        <?php endif ;?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="content-woo-area">
                                        <?php foreach ( $tabs as $key => $tab ) : ?>
                                            <div class="rh-tabletext-block rh-tabletext-wooblock" id="section-<?php echo esc_attr( $key ); ?>">
                                                <div class="rh-tabletext-block-heading">
                                                    <span class="toggle-this-table"></span>
                                                    <h4 class="rh-heading-icon"><?php echo $tab['title'];?></h4>
                                                </div>
                                                <div class="rh-tabletext-block-wrapper">
                                                    <?php call_user_func( $tab['callback'], $key, $tab ); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                <?php endif; ?>

                                <!-- Related -->
                                <?php $sidebar = (is_active_sidebar( 'sidebarwooinner' ) ) ? true : false; ?>
                                <?php include(rh_locate_template( 'woocommerce/single-product/related-compact.php' ) ); ?>
                                <!-- /Related -->
                                <!-- Upsell -->
                                <?php include(rh_locate_template( 'woocommerce/single-product/upsell-compact.php' ) ); ?>
                                <!-- /Upsell -->

                                <div class="other-woo-area">
                                    <div class="rh-container mt30">
                                        <?php
                                        /**
                                         * woocommerce_after_single_product_summary hook.
                                         *
                                         * @hooked woocommerce_output_product_data_tabs - 10
                                         * @hooked woocommerce_upsell_display - 15
                                         * @hooked woocommerce_output_related_products - 20
                                         */
                                        do_action( 'woocommerce_after_single_product_summary' );
                                        ?>
                                    </div>
                                </div>

                            </div><!-- #product-<?php the_ID(); ?> -->
                            <?php do_action( 'woocommerce_after_single_product' ); ?>
                        <?php endwhile; // end of the loop. ?>
                        <?php do_action( 'woocommerce_after_main_content' ); ?>

                    </div>

                </div>
                <?php if ( is_active_sidebar( 'sidebarwooinner' ) ) : ?>
                    <?php wp_enqueue_script('stickysidebar');?>
                    <aside class="sidebar rh-sticky-container">
                        <?php dynamic_sidebar( 'sidebarwooinner' ); ?>
                    </aside>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- /CONTENT -->
    <div class="rh-container">
    <div class="rh-content-wrap clearfix">
        <div class="main-side single full_width clearfix">
            <!-- Main Side -->
            <!-- Stats START -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="pr-1">
                            <h2>Specifications</h2>
                            <table class="table stats">
                                <tbody>
                                <?php if (get_field('hash_rate')): ?>
                                    <tr>
                                        <th>Hash Rate :</th>
                                        <td class=" text-right">
                                            <?php echo number_format(get_field('hash_rate')); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (get_field('manufacturer')): ?>
                                    <tr>
                                        <th>Manufacturer:</th>
                                        <td class=" text-right">
                                            <?php the_field('manufacturer'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (get_field('watt_estimate')): ?>
                                    <tr>
                                        <th>Wattage:</th>
                                        <td class=" text-right">
                                            <?php the_field('watt_estimate'); ?>W
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (get_field('watt_estimate')): ?>
                                    <tr>
                                        <th>Algorithm:</th>
                                        <td class=" text-right">
                                            <?php the_field('algorithm'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="pr-1">
                            <?php
                            global $wpdb;

                            // show db errors
                            $wpdb->show_errors(false);
                            $wpdb->print_error();

                            $mainQuery = $wpdb->get_results("SELECT *
      FROM 
          {$wpdb->prefix}miningprofitability m
      INNER JOIN {$wpdb->prefix}whattomine_api wh ON
          m.whatToMine_id = wh.id
      INNER JOIN {$wpdb->prefix}coins c ON
          m.coin_id = c.id
      INNER JOIN {$wpdb->prefix}ticker t ON
          c.id = t.id
      WHERE 
          m.post_id = \"" . $post->ID . "\"
      ORDER BY m.created_at DESC
      LIMIT 1;");

                            $daily_netProfit = (float)$mainQuery[0]->daily_netProfit;
                            $daily_grossProfit = (float)$mainQuery[0]->daily_grossProfit;
                            $daily_costs = (float)$mainQuery[0]->daily_costs;
                            $hourly_netProfit = $daily_netProfit / 60;
                            $hourly_grossProfit = $daily_grossProfit / 60;
                            $hourly_costs = $daily_costs / 60;
                            $weekly_netProfit = $daily_netProfit * 7;
                            $weekly_grossProfit = $daily_grossProfit * 7;
                            $weekly_costs = $daily_costs * 7;
                            $monthly_netProfit = $daily_netProfit * 7 * 4;
                            $monthly_grossProfit = $daily_grossProfit * 7 * 4;
                            $monthly_costs = $daily_costs * 7 * 4;
                            $yearly_netProfit = $daily_netProfit * 7 * 4 * 12;
                            $yearly_grossProfit = $daily_grossProfit * 7 * 4 * 12;
                            $yearly_costs = $daily_costs * 7 * 4 * 12;
                            ?>
                            <h2>Estimate Earning</h2>
                            <table class="table stats">
                                <tbody>
                                <thead>
                                <th>Period</th>
                                <th class="text-right">Rev</th>
                                <th class="text-right">Cost</th>
                                <th class="text-right">Profit</th>
                                </thead>
                                <tr>
                                    <td>Hour</td>
                                    <td class="text-right text-info">$
                                        <span id="rev-hour">
                              <?php echo number_format($hourly_grossProfit, 3); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-danger">$
                                        <span id="cost-hour">
                              <?php echo number_format($hourly_costs, 3); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-success">$
                                        <span id="earning-hour">
                              <?php echo number_format($hourly_netProfit, 3); ?>
                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Day</td>
                                    <td class="text-right text-info">$
                                        <span id="rev-day">
                              <?php echo number_format($daily_grossProfit, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-danger">$
                                        <span id="cost-day">
                              <?php echo number_format($daily_costs, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-success">$
                                        <span id="earning-day">
                              <?php echo number_format($daily_netProfit, 2); ?>
                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Week</td>
                                    <td class="text-right text-info">$
                                        <span id="rev-week">
                              <?php echo number_format($weekly_grossProfit, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-danger">$
                                        <span id="cost-week">
                              <?php echo number_format($weekly_costs, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-success">$
                                        <span id="earning-week">
                              <?php echo number_format($weekly_netProfit, 2); ?>
                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Month</td>
                                    <td class="text-right text-info">$
                                        <span id="rev-month">
                              <?php echo number_format($monthly_grossProfit, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-danger">$
                                        <span id="cost-month">
                              <?php echo number_format($monthly_costs, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-success">$
                                        <span id="earning-month">
                              <?php echo number_format($monthly_netProfit, 2); ?>
                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Year</td>
                                    <td class="text-right text-info">$
                                        <span id="rev-year">
                              <?php echo number_format($yearly_grossProfit, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-danger">$
                                        <span id="cost-year">
                              <?php echo number_format($yearly_costs, 2); ?>
                            </span>
                                    </td>
                                    <td class="text-right text-success">$
                                        <span id="earning-year">
                              <?php echo number_format($yearly_netProfit, 2); ?>
                            </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <?php
                            global $wpdb;

                            $btcPriceQuery = $wpdb->get_results("SELECT *
      FROM 
          {$wpdb->prefix}ticker t
      WHERE 
        t.coin_id = 1
      ORDER BY t.created_at DESC
      LIMIT 1;");
                            ?>
                            The calculations are based on real time prices, where 1
                            <?php /* echo $mainQuery[0]->tag; */ ?>BTC =
                            $<?php echo number_format((float)$btcPriceQuery[0]->price, 2, '.', ''); ?>
                        </div>
                    </div>
                </div>
                <!-- Stats END -->


                <div class="col-sm-12">
                    <h2>Mining Profitability</h2>
                    <div id="miningProfChart" style="height: 250px;" class="<?php echo $post->ID ?>">
                    </div>
                </div>

                <!-- START -->
                <?php
                $posts = get_field('related_coins');
                if ($posts): ?>
                    <div class="col-sm-12">
                        <h2>Minable coins</h2>
                        <div>
                            <div style="padding:4px;float:left;">
                                <?php foreach ($posts as $post): // variable must be called $post (IMPORTANT) ?>
                                    <?php setup_postdata($post); ?>
                                    <?php if (has_post_thumbnail($post->ID)): ?>
                                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                                        <div style="padding:4px;float:left;">
                                            <div class="image-wrap">
                                                <img style="width: 60px;height: 60px;" class="img-responsive"
                                                     src="<?php echo $image[0]; ?>;" data-toggle="tooltip"
                                                     data-placement="bottom"
                                                     data-html="true" title="<?php the_title(); ?>"
                                                     data-original-title="<?php the_title(); ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
                            </div>
                            <div class="clearfix"></div>
                            <br>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- END -->

                <!-- START -->
                <?php
                $posts = get_field('related_miningpools');
                if ($posts): ?>
                    <div class="col-sm-12">
                        <h2>Mining pools</h2>
                        <table class="table table-striped table-small">
                            <tbody>
                            <?php foreach ($posts as $post): // variable must be called $post (IMPORTANT) ?>
                                <?php setup_postdata($post); ?>
                                <?php if (has_post_thumbnail($post->ID)): ?>
                                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                                    <tr>
                                        <td>
                                            <div class="image-wrap">
                                                <img class="img-responsive" src="<?php echo $image[0]; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <b style="font-size:1.2em;">
                                                <a href="<?php the_field('miningpoollink'); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </b>
                                            <br>
                                            <?php the_field('miningpoollink'); ?>
                                        </td>
                                        <td class="hidden-xs hidden-sm"
                                            style="text-align:center; vertical-align:middle; ;">
                                            <b>PPLNS</b>
                                            <br>
                                            <?php the_field('pay_per_last_n_shares'); ?>
                                        </td>
                                        <td class="text-right" style="vertical-align: middle; width:40px;">
                                            <a class="btn btn-primary" href="<?php the_field('miningpoollink'); ?>"
                                               target="_blank">
                                                <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true"
                                                     data-prefix="fas" data-icon="chevron-right" role="img"
                                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                                                     data-fa-i2svg="">
                                                    <path fill="currentColor"
                                                          d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                                                </svg>
                                                <!-- <i class="fas fa-chevron-right"> </i> -->
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <!-- END -->
            </div>
            <!-- Stats END -->
        </div>
    </div>


    <!-- /Main Side -->
    <!-- Sidebar -->
    <?php if (get_post_meta($post->ID, 'post_size', true) == 'full_post') : ?><?php else : ?><?php get_sidebar(); ?><?php endif; ?>
    <!-- /Sidebar -->
</div>
</div>
    <!-- /CONTENT -->


<!-- FOOTER -->
<?php get_footer(); ?>