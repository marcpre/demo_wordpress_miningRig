<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<?php /* * Template Name: 2nd Version Computer Hardware Post Template * Template Post Type: Computer-Hardware */ ?>
<?php get_header(); ?>
<?php global $post; ?>
<?php if (rh_is_plugin_active('content-egg/content-egg.php')): ?>
    <?php $module_id = get_post_meta($post->ID, '_rehub_module_ce_id', true); ?>
    <?php $unique_id = get_post_meta($post->ID, '_rehub_product_unique_id', true); ?>
    <?php if ($unique_id && $module_id): ?>
        <?php $itemsync = \ContentEgg\application\components\ContentManager::getProductbyUniqueId($unique_id, $module_id, $post->ID); ?>
    <?php endif; ?>
<?php endif; ?>
<!-- CONTENT -->
<!-- Title area -->
<div class="rh-container rh_post_layouxt_compare_full clearfix">
    <div class="main-side single full_width clearfix">
        <?php $crumb = '';
        if (function_exists('yoast_breadcrumb')) {
            $crumb = yoast_breadcrumb('<div class="breadcrumb">', '</div>', false);
        }
        if (!is_string($crumb) || $crumb === '') {
            if (rehub_option('rehub_disable_breadcrumbs') == '1' || vp_metabox('rehub_post_side.disable_parts') == '1') {
                echo '';
            } elseif (function_exists('dimox_breadcrumbs')) {
                dimox_breadcrumbs();
            }
        }
        echo $crumb; ?>
        <?php echo re_badge_create('labelsmall'); ?>
        <?php rh_post_header_cat('post', true); ?>
        <div class="rh_post_layout_compare_full_holder">
            <div class="wpsm-one-third wpsm-column-first compare-full-images modulo-lightbox">
                <?php wp_enqueue_script('modulobox');
                wp_enqueue_style('modulobox'); ?>
                <figure><?php echo re_badge_create('tablelabel'); ?>
                    <?php
                    $image_id = get_post_thumbnail_id($post->ID);
                    $image_url = wp_get_attachment_image_src($image_id, 'full');
                    $image_url = $image_url[0];
                    ?>
                    <a data-rel="rh_top_gallery" href="<?php echo $image_url; ?>" target="_blank"
                       data-thumb="<?php echo $image_url; ?>">
                        <?php WPSM_image_resizer::show_static_resized_image(array('lazy' => true, 'thumb' => true, 'crop' => false, 'width' => 350, 'no_thumb_url' => get_template_directory_uri() . '/images/default/noimage_500_500.png')); ?>
                    </a>
                </figure>
                <?php $post_image_gallery = get_post_meta($post->ID, 'rh_post_image_gallery', true); ?>
                <?php if (!empty($post_image_gallery)) : ?>
                    <?php echo rh_get_post_thumbnails(array('video' => 1, 'columns' => 4, 'height' => 60)); ?>
                <?php else : ?>
                    <?php if (!empty($itemsync['extra']['imageSet'])) {
                        $ceimages = $itemsync['extra']['imageSet'];
                    } elseif (!empty($itemsync['extra']['images'])) {
                        $ceimages = $itemsync['extra']['images'];
                    } else {
                        $qwantimages = \ContentEgg\application\components\ContentManager::getViewData('GoogleImages', $post->ID);
                        if (!empty($qwantimages)) {
                            $ceimages = wp_list_pluck($qwantimages, 'img');
                        } else {
                            $ceimages = '';
                        }
                    } ?>
                    <?php if (!empty($ceimages)): ?>
                        <div class="compare-full-thumbnails rh_mini_thumbs limited-thumb-number mt15 mb15">
                            <?php foreach ($ceimages as $key => $gallery_img) : ?>
                                <?php if (isset($gallery_img['LargeImage'])) {
                                    $image = $gallery_img['LargeImage'];
                                } else {
                                    $image = $gallery_img;
                                } ?>
                                <a data-thumb="<?php echo $image ?>" data-rel="rh_top_gallery"
                                   href="<?php echo $image; ?>" data-title="<?php echo $itemsync['title']; ?>">
                                    <?php WPSM_image_resizer::show_static_resized_image(array('src' => $image, 'height' => 65, 'title' => $itemsync['title'], 'no_thumb_url' => get_template_directory_uri() . '/images/default/noimage_100_70.png')); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="wpsm-two-third wpsm-column-last">
                <div class="title_single_area">
                    <h1 class="<?php if (rehub_option('hotmeter_disable') != '1') : ?><?php echo getHotIconclass($post->ID); ?><?php endif; ?>"><?php the_title(); ?></h1>
                </div>
                <?php /* ?>
                <div class="meta-in-compare-full rh-flex-center-align">
                    <?php $overall_review = rehub_get_overall_score(); ?>
                    <?php if ($overall_review): ?>
                        <?php $starscoreadmin = $overall_review * 10; ?>
                        <div class="star-big floatleft mr15">
                                <span class="stars-rate unix-star">
                                    <span style="width: <?php echo (int)$starscoreadmin; ?>%;"></span>
                                </span>
                        </div>
                    <?php endif; ?>
                    <span class="floatleft meta post-meta mt0 mb0">
                            <?php rh_post_header_meta(false, false, true, true, true); ?>
                        </span>
                    <span class="rh-flex-right-align">
                            <?php if (rehub_option('compare_btn_single') != '' && is_singular('post')) : ?>
                                <?php $cmp_btn_args = array(); ?>
                                <?php if (rehub_option('compare_btn_cats') != '') {
                                    $cmp_btn_args['cats'] = esc_html(rehub_option('compare_btn_cats'));
                                } ?>
                                <?php echo wpsm_comparison_button($cmp_btn_args); ?>
                            <?php endif; ?>
                        </span>
                </div>
                <?php */ ?>
                <?php /* ?>
                <div class="wpsm-one-half wpsm-column-first">
                    <?php if (rehub_option('hotmeter_disable') != '1') {
                        echo '<div class="mb20">';
                        echo getHotThumb($post->ID, false, true);
                        echo '</div>';
                    } ?>
                    <?php $prosvalues = vp_metabox('rehub_post.review_post.0.review_post_pros_text'); ?>
                    <?php if (!empty($prosvalues)): ?>
                        <ul class="featured_list">
                            <?php $prosvalues = explode(PHP_EOL, $prosvalues); ?>
                            <?php foreach ($prosvalues as $prosvalue) {
                                echo '<li>' . $prosvalue . '</li>';
                            } ?>
                        </ul>
                    <?php else: ?>
                        <?php if (!empty($itemsync['extra']['itemAttributes']['Feature'])) {
                            $features = $itemsync['extra']['itemAttributes']['Feature'];
                        } elseif (!empty($itemsync['extra']['keySpecs'])) {
                            $features = $itemsync['extra']['keySpecs'];
                        }
                        ?>
                        <?php if (!empty ($features)) : ?>
                            <ul class="featured_list">
                                <?php $length = $maxlength = 0; ?>
                                <?php foreach ($features as $k => $feature): ?>
                                    <?php if (is_array($feature)) {
                                        continue;
                                    } ?>
                                    <?php $length = strlen($feature);
                                    $maxlength += $length; ?>
                                    <li><?php echo $feature; ?></li>
                                    <?php if ($k >= 3 || $maxlength > 150) break; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="compare-button-holder">
                        <?php
                        $offer_post_url = $itemsync['url'];
                        $offer_url = apply_filters('rh_post_offer_url_filter', $offer_post_url);
                        ?>
                        <?php $offer_price = (!empty($itemsync['price'])) ? $itemsync['price'] : ''; ?>
                        <?php $offer_price_old = (!empty($itemsync['priceOld'])) ? $itemsync['priceOld'] : ''; ?>
                        <?php $currency_code = (!empty($itemsync['currencyCode'])) ? $itemsync['currencyCode'] : ''; ?>
                        <?php $domain = (!empty($itemsync['domain'])) ? $itemsync['domain'] : ''; ?>
                        <?php $merchant = (!empty($itemsync['merchant'])) ? $itemsync['merchant'] : ''; ?>
                        <?php if ($offer_url): ?>
                            <?php if ($offer_price): ?>
                                <div>
                                    <p class="price">
                                        <ins><?php echo \ContentEgg\application\helpers\TemplateHelper::formatPriceCurrency($offer_price, $currency_code, '', ''); ?>

                                        </ins>
                                        <?php if ($offer_price_old): ?>
                                            <del><?php echo \ContentEgg\application\helpers\TemplateHelper::formatPriceCurrency($offer_price_old, $currency_code, '', ''); ?> </del>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php echo rh_best_syncpost_deal($itemsync); ?>
                            <?php $buy_best_text = (rehub_option('buy_best_text') != '') ? esc_html(rehub_option('buy_best_text')) : __('Buy for best price', 'rehub_framework'); ?>
                            <a href="<?php echo esc_url($offer_url); ?>"
                               class="re_track_btn wpsm-button rehub_main_btn btn_offer_block" target="_blank"
                               rel="nofollow"><?php echo $buy_best_text; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                 <?php */ ?>

                <div class="wpsm-one-half wpsm-column-last">
                    <?php echo do_shortcode('[content-egg-block template=custom/all_merchant_widget]'); ?>
                    <div class="floatright mt15"><?php echo RH_get_wishlist($post->ID); ?></div>
                    <?php if (rehub_option('rehub_disable_share_top') == '1' || vp_metabox('rehub_post_side.disable_parts') == '1')  : ?>
                    <?php else : ?>
                        <div class="top_share floatleft notextshare mt20">
                            <?php include(rh_locate_template('inc/parts/post_share.php')); ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="post-inner">
                <br>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</div>
<!-- CONTENT -->
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
    <?php /* ?>
    <?php if (get_post_meta($post->ID, 'post_size', true) == 'full_post') : ?><?php else : ?><?php get_sidebar(); ?><?php endif; ?>
    <?php */ ?>
 <!-- /Sidebar -->
</div>
</div>
    <!-- /CONTENT -->


<!-- FOOTER -->
<?php get_footer(); ?>