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

                    <!-- START -->
                    <div align="right">
                        <button type="button" class="btn btn-primary btn-lg createRig">
                            Create your own Mining Rig
                        </button>
                    </div>
                    <br/>

                    <table id="allHardwareOverview" style="float: left;" class="table table-bordered"></table>
                    <!-- END -->
                </article>
            </div>

            <!-- /Main Side -->
        </div>
    </div>
    <!-- /CONTENT -->

    <!-- FOOTER -->
    <?php get_footer();?>
