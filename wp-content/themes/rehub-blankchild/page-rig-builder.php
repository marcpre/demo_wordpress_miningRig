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
                            <label for="example-text-input" class="col-2 col-form-label">
                                <b>Titel</b>
                            </label>
                            <div class="col-10">
                                <input class="form-control posttitle" type="text" placeholder="My awesome mining rig" id="miningrigtitle">
                            </div>
                        </div>
                        <div class="table-responsive overflow-x:auto;">
                            <table id="miningRigTable" style="float: left;" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <b>Component</b>
                                        </th>
                                        <th>
                                            <b>Selection</b>
                                        </th>
                                        <th>
                                            <b>Price</b>
                                        </th>
                                        <th>
                                            <b>Where</b>
                                        </th>
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
                                        <td>PCI-E Riser</td>
                                        <td>
                                            <button type="button" data-exists="pci-e" class="btn btn-primary btn-sm pci-e">
                                                Add PCI-E Riser
                                            </button>
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
                        </div>
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
                            <b>Total:</b>
                            <i class="fas fa-dollar-sign"></i>
                            <span id="total" class="total"></span>
                            <br>
                            <b>Wattage Estimate:</b>
                            <i class="fas fa-bolt"></i>
                            <span id="wattage" class="wattage" aria-hidden="true"></span>W
                        </div>
                        <div class="form-group row" align="left">
                            <div class="col-7">
                                <textarea class="form-control description miningRigDescription" rows="8" id="comment" placeholder="Describe your mining rig briefly. E.g.: For which coins can it be used?"></textarea>
                                <span class='typedChar'></span>
                            </div>
                        </div>
                        <div align="right">
                            <button type="button" class="btn btn-warning btn-lg calc-prof">
                                Calculate Profitability
                            </button>
                            <button type="button" class="btn btn-primary btn-lg save-list">
                                Save Mining Rig
                            </button>
                        </div>
                        <div align="left">
                        <!-- -->
                            <br>
                            <div id="profitCalculator" class="table-responsive overflow-x:auto;">
                                <table style="float: left;" class="table table-bordered">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th><b>Initial Costs</b></th>
                                            <th><b>Algorithm</b></th>
                                            <th><b>Hash Rate</b></th>
                                            <th><b>Coin</b></th>
                                            <th><b>Monthly Revenue</b></th>
                                            <th><b>Annual Revenue</b></th>
                                            <th><b>Payback</b></th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>
                                            <i class="fas fa-dollar-sign"></i>
                                            <span id="total" class="total"></span>
                                        </td>
                                        <td>
                                            <span class="algorithmProf"></span>
                                        </td>
                                        <td>
                                            <span class="hashRateProf"></span>
                                        </td>
                                        <td>
                                            <span class="coinProf"></span>
                                        </td>
                                        <td>
                                            <span class="monthMinProf"></span>
                                        </td>
                                        <td>
                                            <span class="yearMinProf"></span>
                                        </td>
                                        <td style="font-size: 23px; color: orange; font-weight: bold;">
                                            <span class="paybackProf"></span>
                                        </td>
                                    </tr>
                                </table>
                                <input type="text" class="form-control-cost-per-kwh" placeholder="Cost per kWh ($)" style="width: 200px;"/>
                            </div>
                        <!-- -->
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
                                        <h5 class="modal-title" id="exampleModalLabel">Mining Rig Parts</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <div class="part-node ib part-node-unselected cpu ">CPU </div>
                                            <div class="part-node ib part-node-unselected motherboard">Motherboard</div>
                                            <div class="part-node ib part-node-unselected memory">Memory</div>
                                            <div class="part-node ib part-node-unselected graphic-card">Graphic Card</div>
                                            <div class="part-node ib part-node-unselected pci-e">PCI-E Riser</div>
                                            <div class="part-node ib part-node-unselected power-supply">Power Supply</div>
                                            <div class="part-node ib part-node-unselected rig-frame">Rig Frame</div>
                                            <div class="part-node ib part-node-unselected more-parts">More Parts</div>
                                        </div>
                                        <br/>
                                        <br/>
                                        <div class="table-responsive overflow-x:auto;">
                                            <table id="table_id" class="display" style="width:100%" width="100%"></table>
                                        </div>
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
