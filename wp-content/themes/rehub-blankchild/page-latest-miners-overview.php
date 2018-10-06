<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly ?>
<?php

/* Template Name: Latest Miners Overview */

?>
    <?php get_header();?>
    <!-- CONTENT -->
    <div class="rh-container">
        <div class="rh-content-wrap clearfix">
            <!-- Main Side -->
            <div class="main-side page clearfix full_width">
                <?php /*
                <div class="title">
                    <h1>
                        <?php the_title();?>
                </h1>
            </div> */?>
            <article class="post" id="page-<?php the_ID();?>">
                <?php if (have_posts()): while (have_posts()): the_post();?>
                <?php the_content();?>
                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'rehub_framework'), 'after' => '</div>'));?>
                <?php endwhile;endif;?>
                <div class="hardwareOverviewSpinner"></div>
                <!-- START -->
                <!--
                    <div align="right">
                        <button type="button" class="btn btn-primary btn-lg createRig">
                            Create your own Mining Rig
                        </button>
                    </div> -->
                <div align="center">
                    <?php echo get_field('headertext'); ?>
                </div>

                <!-- START -->
                <div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" id="asic-latest-tab" data-toggle="tab" href="#asic" role="tab" aria-controls="asic" aria-selected="false">ASICS</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" id="gpu-latest-tab" data-toggle="tab" href="#gpu" role="tab" aria-controls="gpu" aria-selected="false">GPU</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-latest-tab">
                            <div class="table-responsive overflow-x:auto;">
                                <table id="allLatestOverview" style="width:100%; float: left;" class="table table-bordered"></table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="asic" role="tabpanel" aria-labelledby="asic-latest-tab">
                            <div class="table-responsive overflow-x:auto;">
                                <table id="allLatestOverviewasic" style="width:100%; float: left;" class="table table-bordered"></table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="gpu" role="tabpanel" aria-labelledby="gpu-latest-tab">
                            <div class="table-responsive overflow-x:auto;">
                                <table id="allLatestOverviewgpu" style="width:100%; float: left;" class="table table-bordered"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END -->

                <!-- END -->
            </article>
        </div>

        <!-- /Main Side -->
    </div>
    </div>
    <!-- /CONTENT -->

    <!-- FOOTER -->
    <?php get_footer();?>
