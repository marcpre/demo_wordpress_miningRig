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
    'posts_per_page' => -1,
    //'post_type' => 'post',
    'post_type' => 'Computer-Hardware',
    //'meta_key'        => '_cegg_data_Amazon',
    // 'meta_value'    => 'Melbourne'
));

// var_dump($products->posts);

if ($products->have_posts()) {?>

                        <table style="float: left;" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>CPU</td>
                                    <td>
                                        <button type="button" data-exists="cpu" class="btn btn-primary btn-sm cpu" >
                                            Add CPU
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Motherboard</td>
                                    <td>
                                        <button type="button" data-exists="motherboard" class="btn btn-primary btn-sm motherboard" >
                                            Add Motherboard
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Graphic Card</td>
                                    <td>
                                        <button type="button" data-exists="graphic-card" class="btn btn-primary btn-sm graphic-card" >
                                            Add Graphic Card
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Power Supply&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="power-supply" class="btn btn-primary btn-sm power-supply" >
                                            Add Power Supply
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rig Frame&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="rig-frame" class="btn btn-primary btn-sm rig-frame" >
                                            Add Rig Frame
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>More Parts&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="more-parts" class="btn btn-primary btn-sm more-parts" >
                                            Add More Parts
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <!-- Button trigger modal -->
<!--                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Launch demo modal
                        </button>
-->                        
                        <!-- DELETE ME -->
                        <ul></ul>
                        <!-- DELETE ME -->


                        <?php  };

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
                            <!--                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Manufacturer</th>
                                                    <th>Price</th>
                                                    <th>Availability</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
-->                                                <?php /*
                                $i = 0;
                                while ($products->have_posts()) {
                                    $products->the_post()?>

                                                </br>
                                                <?php 
$amazon = get_post_meta(get_the_ID(), '_cegg_data_Amazon', true);
$keys = array_keys($amazon); // convert associative arrays to index array
    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $i++; ?>
                                                        </td>
                                                        <td>
                                                            <img src="<?php echo $amazon[$keys[0]]['img']; ?>" alt="<?php the_title();?>" height="42" width="42">
                                                            <a href="<?php the_permalink();?>">
                                                                <?php the_title();?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?php echo $amazon[$keys[0]]['manufacturer']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                echo $amazon[$keys[0]]['currency'];
                                                echo $amazon[$keys[0]]['price'];
                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php print_r($amazon[$keys[0]]['extra']['availability']);?>
                                                        </td>
                                                        <td>Buy</td>
                                                        <td>Add</td>
                                                    </tr>

                                                    <?php

    //remove this php part
    //            print_r($amazon[$keys[0]]['price']);
    //            print_r($amazon[$keys[0]]['currency']);
    //            print_r($amazon[$keys[0]]['manufacturer']);
    //            print_r($amazon[$keys[0]]['img']);
    //            print_r($amazon[$keys[1]][0]);  //get title here

//            print_r($amazon);
    //            $var_str = var_export($amazon[$keys[0]], true);
    //            file_put_contents('filename.txt', $var_str);
    ?>
                                                        <?php }; */ ?>
                                        <!--S    </tbody> -->
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
