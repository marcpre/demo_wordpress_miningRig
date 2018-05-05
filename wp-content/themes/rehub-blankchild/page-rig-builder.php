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
                        <div class="errors"></div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">Titel</label>
                            <div class="col-10">
                                <input class="form-control posttitle" type="text" placeholder="My awesome mining rig" id="miningrigtitle">
                            </div>
                        </div>
                        <table id="miningRigTable" style="float: left;" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Component</th>
                                    <th>Selection</th>
                                    <th>Price</th>
                                    <th>Where</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CPU</td>
                                    <td>
                                        <button type="button" data-exists="cpu" class="btn btn-primary btn-sm cpu">
                                            Add CPU
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Motherboard</td>
                                    <td>
                                        <button type="button" data-exists="motherboard" class="btn btn-primary btn-sm motherboard">
                                            Add Motherboard
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Memory</td>
                                    <td>
                                        <button type="button" data-exists="memory" class="btn btn-primary btn-sm memory">
                                            Add Memory
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Graphic Card</td>
                                    <td>
                                        <button type="button" data-exists="graphic-card" class="btn btn-primary btn-sm graphic-card">
                                            Add Graphic Card
                                        </button>
                                        <!-- <button type="button" class="btn btn-dark btn-sm graphic-card" disabled>+</button> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>Power Supply&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="power-supply" class="btn btn-primary btn-sm power-supply">
                                            Add Power Supply
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rig Frame&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="rig-frame" class="btn btn-primary btn-sm rig-frame">
                                            Add Rig Frame
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>More Parts&nbsp;</td>
                                    <td>
                                        <button type="button" data-exists="more-parts" class="btn btn-primary btn-sm more-parts">
                                            Add More Parts
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td align="right">Total: $
                                        <span id="total" class="total"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div align="left">
                            <!-- <a href=""><i class="fas fa-link"></i>Link</a> -->
                            <a class="sn-reddit" href="">
                                <i class="fab fa-reddit-alien"></i>Reddit</a>
                            <a class="sn-vBCode" href="">
                                <i class="fas fa-users"></i>vBCode</a>
                            <a class="sn-twitch" href="">
                                <i class="fab fa-twitch"></i>Twitch</a>
                        </div>
                        <div align="right">
                            Total:
                            <i class="fas fa-dollar-sign"></i>
                            <span id="total" class="total"></span>
                        </div>
                        <div align="right">
                            Wattage Estimate:
                            <i class="fas fa-bolt"></i>
                            <span id="wattage" class="wattage" aria-hidden="true"></span>W
                        </div>
                        <div class="form-group row" align="left">
                            <div class="col-7">
                                <textarea class="form-control miningRigDescription" rows="4" id="comment" placeholder="Describe your mining rig briefly. E.g.: For which coins can it be used?"></textarea>
                            </div>
                        </div>
                        <div align="right">
                            <button type="button" class="btn btn-primary btn-lg save-list">
                                Save Mining Rig
                            </button>
                        </div>
                        <?php
}
;
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
                                        <table id="table_id" class="display" style="width:100%"></table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END -->
                        <!-- MODALS START -->
                        <?php get_template_part('template-parts/modal-reddit');?>
                        <?php get_template_part('template-parts/modal-twitch');?>
                        <?php get_template_part('template-parts/modal-vBCode');?>

                        <!-- MODALS END -->
                </article>
            </div>

            <!-- /Main Side -->
        </div>
    </div>
    <!-- /CONTENT -->

    <!-- FOOTER -->
    <?php get_footer();?>
