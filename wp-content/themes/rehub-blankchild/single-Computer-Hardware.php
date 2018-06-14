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
              <h2>Specifications</h2>
              <table class="table stats">
                <tbody>
                    <?php if( get_field('hash_rate') ): ?>
                      <tr>
                        <th>Hash Rate :</th>
                        <td class=" text-right"><?php the_field('hash_rate'); ?></td>
                      </tr>
                    <?php endif; ?>
                    <?php if( get_field('manufacturer') ): ?>
                      <tr>
                        <th>Manufacturer:</th>
                        <td class=" text-right"><?php the_field('manufacturer'); ?></td>
                      </tr>
                    <?php endif; ?>
                    <?php if( get_field('watt_estimate') ): ?>
                      <tr>
                        <th>Wattage:</th>
                        <td class=" text-right"><?php the_field('watt_estimate'); ?></td>
                      </tr>
                    <?php endif; ?> 
                    <?php if( get_field('watt_estimate') ): ?>
                      <tr>
                        <th>Algorithm:</th>
                        <td class=" text-right"><?php the_field('algorithm'); ?></td>
                      </tr>
                    <?php endif; ?> 
                    <?php if( get_field('watt_estimate') ): ?>
                      <tr>
                        <th>Wattage:</th>
                        <td class=" text-right"><?php the_field('watt_estimate'); ?></td>
                      </tr>
                    <?php endif; ?>                     
                  <tr>
                    <th>Nethash :</th>
                    <td class=" text-right">36,713,089.80 TH/s</td>
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

<div class="col-sm-12">
   <h2>Minable coins</h2>
   <div>
      <div style="padding:4px;float:left;">
         <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_60,w_60/v1525359468/a6xe2co1mrdxvb8vtcjd.png" data-toggle="tooltip" data-placement="bottom" data-html="true" title="" data-original-title="<b>BitcoinGold</b> (BTG)<br/><i>Equihash</i>"></div>
      </div>
      <div style="padding:4px;float:left;">
         <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_60,w_60/v1525359278/epsvwji680evuyfnu1ay.jpg" data-toggle="tooltip" data-placement="bottom" data-html="true" title="" data-original-title="<b>ZenCash</b> (ZEN)<br/><i>Equihash</i>"></div>
      </div>
      <div style="padding:4px;float:left;">
         <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_60,w_60/v1520031739/vcslnfksrwcwrilo0jxk.png" data-toggle="tooltip" data-placement="bottom" data-html="true" title="" data-original-title="<b>Zcash</b> (ZEC)<br/><i>Equihash</i>"></div>
      </div>
      <div style="padding:4px;float:left;">
         <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_60,w_60/v1525359676/tho1ioeo8258ekttwgtj.png" data-toggle="tooltip" data-placement="bottom" data-html="true" title="" data-original-title="<b>Hush</b> (HUSH)<br/><i>Equihash</i>"></div>
      </div>
   </div>
   <div class="clearfix"></div>
   <br> 
   <!-- START -->
<?php 

$posts = get_field('related_coins');

if( $posts ): ?>
    <ul>
    <?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
        <?php setup_postdata($post); ?>
        <li>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <span>Custom field from $post: <?php the_field('author'); ?></span>
        </li>
        <div style="padding:4px;float:left;">
         <div class="image-wrap"><img class="img-responsive" src="echo get_post_meta($post->ID, 'featured_image', true);" data-toggle="tooltip" data-placement="bottom" data-html="true" title="" data-original-title="<b><?php the_title(); ?></b><br/><i><?php the_field('algorithm'); ?></i>"></div>
      </div>
    <?php endforeach; ?>
    </ul>
    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; ?>   
<!-- END -->   
   
</div>


   <div class="col-sm-12">
      <h2>Mining pools</h2>
      <table class="table table-striped table-small">
         <tbody>
            <tr>
               <td>
                  <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_48,w_120/v1526247983/c9luvcoxznqfo8wlgclo.png"></div>
               </td>
               <td><b style="font-size:1.2em;"><a href="https://slushpool.com">SlushPool</a></b><br>slushpool.com</td>
               <td class="hidden-xs hidden-sm" style="text-align:center; vertical-align:middle; ;"><b>PPLNS</b><br>2%</td>
               <td class="text-right" style="vertical-align: middle; width:40px;">
                  <a class="btn btn-primary" href="https://slushpool.com" target="_blank">
                     <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                     </svg>
                     <!-- <i class="fas fa-chevron-right"> </i> -->
                  </a>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_48,w_120/v1526250650/toiusvqctaoc922dnfvd.png"></div>
               </td>
               <td><b style="font-size:1.2em;"><a href="https://www.nicehash.com">NiceHash</a></b><br>www.nicehash.com</td>
               <td class="hidden-xs hidden-sm" style="text-align:center; vertical-align:middle; ;"><b>RESELL</b><br></td>
               <td class="text-right" style="vertical-align: middle; width:40px;">
                  <a class="btn btn-primary" href="https://www.nicehash.com" target="_blank">
                     <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                     </svg>
                     <!-- <i class="fas fa-chevron-right"> </i> -->
                  </a>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_48,w_120/v1525041919/ddfuvfhlyzy7eodo3iei.png"></div>
               </td>
               <td><b style="font-size:1.2em;"><a href="https://www.antpool.com">AntPool</a></b><br>www.antpool.com</td>
               <td class="hidden-xs hidden-sm" style="text-align:center; vertical-align:middle; ;"><b>PPLNS</b><br>0%</td>
               <td class="text-right" style="vertical-align: middle; width:40px;">
                  <a class="btn btn-primary" href="https://www.antpool.com" target="_blank">
                     <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                     </svg>
                     <!-- <i class="fas fa-chevron-right"> </i> -->
                  </a>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_48,w_120/v1525643428/legs91aclwbpj8zkslyl.png"></div>
               </td>
               <td><b style="font-size:1.2em;"><a href="https://nanopool.org">NanoPool</a></b><br>nanopool.org</td>
               <td class="hidden-xs hidden-sm" style="text-align:center; vertical-align:middle; ;"><b>PPLNS</b><br>2%</td>
               <td class="text-right" style="vertical-align: middle; width:40px;">
                  <a class="btn btn-primary" href="https://nanopool.org" target="_blank">
                     <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                     </svg>
                     <!-- <i class="fas fa-chevron-right"> </i> -->
                  </a>
               </td>
            </tr>
            <tr>
               <td>
                  <div class="image-wrap"><img class="img-responsive" src="https://res.cloudinary.com/dluwgr5op/image/upload/c_fit,f_auto,h_48,w_120/v1526247393/sjkwcqbsrvawogpgz5l2.png"></div>
               </td>
               <td><b style="font-size:1.2em;"><a href="https://zcash.flypool.org">FlyPool</a></b><br>zcash.flypool.org</td>
               <td class="hidden-xs hidden-sm" style="text-align:center; vertical-align:middle; ;"><b>PPLNS</b><br>1%</td>
               <td class="text-right" style="vertical-align: middle; width:40px;">
                  <a class="btn btn-primary" href="https://zcash.flypool.org" target="_blank">
                     <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                     </svg>
                     <!-- <i class="fas fa-chevron-right"> </i> -->
                  </a>
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