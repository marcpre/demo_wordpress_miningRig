<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<?php /* * Template Name: Computer-Hardware Post Template * Template Post Type: Computer-Hardware */ ?>
<?php get_header(); ?>
<?php global $post; ?>
<!-- CONTENT -->
<div class="rh-container">
    <div class="rh-content-wrap clearfix">
        <!-- Main Side -->
        <div class="main-side single post-readopt full_width clearfix">
            <?php if (have_posts()) : while (have_posts()) :
            the_post(); ?>
            <article class="post <?php $category = get_the_category($post->ID);
            if ($category) {
                $first_cat = $category[0]->term_id;
                echo 'category-' . $first_cat . '';
            } ?>"
                     id="post-<?php the_ID(); ?>">
                <!-- Title area -->
                <div class="rh_post_layout_metabig">
                    <div class="title_single_area">
                        <?php if (rehub_option('compare_btn_single') != '' && is_singular('post')) : ?>
                            <?php $cmp_btn_args = array(); ?>
                            <?php if (rehub_option('compare_btn_cats') != '') {
                                $cmp_btn_args['cats'] = esc_html(rehub_option('compare_btn_cats'));
                            } ?>
                            <?php echo wpsm_comparison_button($cmp_btn_args); ?>
                        <?php endif; ?>
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
                        <h1>
                            <?php the_title(); ?>
                        </h1>
                    </div>
                </div>
                <?php if (rehub_option('rehub_single_after_title')) : ?>
                    <div class="mediad mediad_top">
                        <?php echo do_shortcode(rehub_option('rehub_single_after_title')); ?>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>
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
            <div class="date_time_post">
              <?php the_time(get_option( 'date_format' )); ?>
            </div>
            <?php */ ?>
                    <?php if (rehub_option('rehub_disable_share_top') == '1' || vp_metabox('rehub_post_side.disable_parts') == '1') : ?>
                    <?php else : ?>
                        <div class="top_share">
                            <?php include(rh_locate_template('inc/parts/post_share.php')); ?>
                        </div>
                        <div class="clearfix"></div>
                    <?php endif; ?>
                    <!--    </div> -->
                    <?php /* ?>
            <?php  include(rh_locate_template( 'inc/parts/top_image.php')); ?>
            <?php */ ?>
                </div>
                <?php if (rehub_option('rehub_single_before_post') && vp_metabox('rehub_post_side.show_banner_ads') != '1') : ?>
                    <div class="mediad mediad_before_content">
                        <?php echo do_shortcode(rehub_option('rehub_single_before_post')); ?>
                    </div>
                <?php endif; ?>

                <div class="post-inner">
                    <?php the_content(); ?>
                </div>
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
        </article>
        <div class="clearfix"></div>
        <?php include(rh_locate_template('inc/post_layout/single-common-footer.php')); ?>
        <?php endwhile;
        endif; ?>
    </div>

    <div>
        <?php echo do_shortcode('[rwp_box id="-1" template="compHardware_Review_Box"]'); ?>
    </div>

    <?php comments_template(); ?>
</div>
<!-- /Main Side -->
</div>
</div>
<!-- /CONTENT -->


<!-- FOOTER -->
<?php get_footer(); ?>
