<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly ?>
<?php

/* Template Name: Hardware Overview */

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
                <br/>
                <div class="table-responsive overflow-x:auto;">
                    <table id="allHardwareOverview" style="width:100%; float: left;" class="table table-bordered"></table>
                </div>
                <!-- END -->
            </article>
        </div>

        <!-- /Main Side -->
    </div>
    </div>
    <!-- /CONTENT -->

    <!-- FOOTER -->
    <?php get_footer();?>
