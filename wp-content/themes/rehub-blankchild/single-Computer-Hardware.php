<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php /* * Template Name: Computer-Hardware Post Template * Template Post Type: Computer-Hardware */ ?>
<?php get_header(); ?>
<?php global $post;?>
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
            <?php if(rehub_option( 'compare_btn_single') !='' && is_singular( 'post')) :?>
            <?php $cmp_btn_args=array();?>
            <?php if(rehub_option( 'compare_btn_cats') !='' ) { $cmp_btn_args[ 'cats']=esc_html(rehub_option( 'compare_btn_cats')); }?>
            <?php echo wpsm_comparison_button($cmp_btn_args); ?>
            <?php endif;?>
            <?php $crumb='' ; if( function_exists( 'yoast_breadcrumb' ) ) { $crumb=yoast_breadcrumb( '<div class="breadcrumb">', '</div>', false); } if( ! is_string( $crumb ) || $crumb==='' ) { if(rehub_option( 'rehub_disable_breadcrumbs')=='1' || vp_metabox( 'rehub_post_side.disable_parts')=='1' ) {echo '';} elseif (function_exists( 'dimox_breadcrumbs')) { dimox_breadcrumbs(); } } echo $crumb; ?>
            <?php echo re_badge_create( 'labelsmall'); ?>
            <?php rh_post_header_cat( 'post', true);?>
            <h1><?php the_title(); ?></h1>
          </div>
        </div>
        <?php if(rehub_option( 'rehub_single_after_title')) : ?>
        <div class="mediad mediad_top">
          <?php echo do_shortcode(rehub_option( 'rehub_single_after_title')); ?>
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
            <?php if(rehub_option( 'rehub_disable_share_top')=='1' || vp_metabox( 'rehub_post_side.disable_parts')=='1' ) : ?>
            <?php else :?>
            <div class="top_share">
              <?php include(rh_locate_template( 'inc/parts/post_share.php')); ?>
            </div>
            <div class="clearfix"></div>
            <?php endif; ?>
            <!--    </div> -->
            <?php include(rh_locate_template( 'inc/parts/top_image.php')); ?>
          </div>
          <?php if(rehub_option( 'rehub_single_before_post') && vp_metabox( 'rehub_post_side.show_banner_ads') !='1' ) : ?>
          <div class="mediad mediad_before_content">
            <?php echo do_shortcode(rehub_option( 'rehub_single_before_post')); ?>
          </div>
          <?php endif; ?>

          <div class="post-inner">
            <?php the_content(); ?>
          </div>
          <!-- Stats START -->
          <div class="row">
            <div class="col-xs-6">
              <h2>Stats</h2>
              <table class="table stats">
                <tbody>
                  <tr>
                    <th>Price :</th>
                    <td class="text-primary text-right">
                      <strong>$6,754.30 USD </strong> <span class="text-red">(-0.15%) <i class="fa fa-caret-down"></i></span>
                    </td>
                  </tr>
                  <tr>
                    <th>Difficulty :</th>
                    <td class="text-right">
                      <div id="difficulty-line"></div>
                      <strong>Current :</strong> 4,940,705M <span class=" text-danger  ">(0.00%)</span>
                      <br>
                      <strong>24 Hour :</strong> 4,940,705M
                    </td>
                  </tr>
                  <tr>
                    <th>Nethash :</th>
                    <td class=" text-right">36,713,089.80 TH/s</td>
                  </tr>
                  <tr>
                    <th>Market Cap :</th>
                    <td class=" text-right">$115,427,778,708 USD</td>
                  </tr>
                  <tr>
                    <th>Block Reward :</th>
                    <td class=" text-right">12.6655 </td>
                  </tr>
                  <tr>
                    <th>Current Block :</th>
                    <td class=" text-right">527167 </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-xs-6">
              <h2>Estimate Earning</h2>
              <table class="table stats">
                <tbody>
                  <tr>
                    <th>Period</th>
                    <th class="text-right">BTC</th>
                    <th class="text-right">Rev</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">Profit</th>
                  </tr>
                  <tr>
                    <td>Hour</td>
                    <td class="text-right"><span id="coin-hour">0.0000</span>
                    </td>
                    <td class="text-right">$<span id="rev-hour">0.20</span>
                    </td>
                    <td class="text-right">$<span id="cost-hour">0.00</span>
                    </td>
                    <td class="text-right text-success">$<span id="earning-hour">0.20</span>
                    </td>
                  </tr>
                  <tr>
                    <td>Day</td>
                    <td class="text-right"><span id="coin-day">0.0007</span>
                    </td>
                    <td class="text-right">$<span id="rev-day">4.88</span>
                    </td>
                    <td class="text-right">$<span id="cost-day">0.00</span>
                    </td>
                    <td class="text-right text-success">$<span id="earning-day">4.88</span>
                    </td>
                  </tr>
                  <tr>
                    <td>Week</td>
                    <td class="text-right"><span id="coin-week">0.0051</span>
                    </td>
                    <td class="text-right">$<span id="rev-week">34.13</span>
                    </td>
                    <td class="text-right">$<span id="cost-week">0.00</span>
                    </td>
                    <td class="text-right text-success">$<span id="earning-week">34.13</span>
                    </td>
                  </tr>
                  <tr>
                    <td>Month</td>
                    <td class="text-right"><span id="coin-month">0.0217</span>
                    </td>
                    <td class="text-right">$<span id="rev-month">146.29</span>
                    </td>
                    <td class="text-right">$<span id="cost-month">0.00</span>
                    </td>
                    <td class="text-right text-success">$<span id="earning-month">146.29</span>
                    </td>
                  </tr>
                  <tr>
                    <td>Year</td>
                    <td class="text-right"><span id="coin-year">0.2635</span>
                    </td>
                    <td class="text-right">$<span id="rev-year">1,779.87</span>
                    </td>
                    <td class="text-right">$<span id="cost-year">0.00</span>
                    </td>
                    <td class="text-right text-success">$<span id="earning-year">1,779.87</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- Stats END -->
      </article>
      <div class="clearfix"></div>
      <?php include(rh_locate_template( 'inc/post_layout/single-common-footer.php')); ?>
      <?php endwhile; endif; ?>
      <?php comments_template(); ?>
      </div>
      <!-- /Main Side -->
    </div>
  </div>
  <!-- /CONTENT -->


  <!-- FOOTER -->
  <?php get_footer(); ?>