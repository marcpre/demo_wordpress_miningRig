<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly ?>
<?php

/* Template Name: Full width */

?>
    <?php get_header();?>
    <!-- CONTENT -->
    <div class="rh-container">
        <div class="rh-content-wrap clearfix">
            <!-- Main Side -->
            <div class="main-side page clearfix full_width">
                <div class="title">
                    <h1>
                        <?php the_title();?>
                    </h1>
                </div>
                <article class="post" id="page-<?php the_ID();?>">
                    <?php if (have_posts()): while (have_posts()): the_post();?>
		                    <?php the_content();?>
		                    <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'rehub_framework'), 'after' => '</div>'));?>
		                    <?php endwhile;endif;?>
 
                    <?php
$products = new WP_Query(array(
    'posts_per_page'   => -1,
    'post_type'        => 'post',
    //'meta_key'		=> '_cegg_data_Amazon',
	// 'meta_value'	=> 'Melbourne'
));

// var_dump($products->posts);

if( $products->have_posts() ) { ?>

 <!-- Button trigger modal -->
 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>


	<ul>
	<?php while ( $products->have_posts() ) { $products->the_post()  ?>
		<li>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </br>
            <?php 
            $amazon = get_post_meta( get_the_ID(), '_cegg_data_Amazon', true); 
                        
            print_r($amazon);
            ?>
		</li>
	<?php }; ?>
	</ul>
<?php }; 


wp_reset_postdata();

?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table id="table_id" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                </tr>
                <tr>
                    <td>Row 2 Data 1</td>
                    <td>Row 2 Data 2</td>
                    <td>lolonator</td>
                    <td>Row 1 Data 2</td>
                </tr>
            </tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

                    <!-- END -->
                </article>
            </div>

            <!-- /Main Side -->
        </div>
    </div>
    <!-- /CONTENT -->
    <!-- MODAL START -->

    <!-- MODAL END -->
    <!-- FOOTER -->
    <?php get_footer();?>
