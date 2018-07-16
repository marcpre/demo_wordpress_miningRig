<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if (defined('RH_GRANDCHILD_DIR')) {
    include RH_GRANDCHILD_DIR . 'rh-grandchild-func.php';
}

require get_theme_file_path('/inc/computerHardwareRoute.php');
require get_theme_file_path('/inc/miningRigsRoute.php');
require get_theme_file_path('/inc/miningProfRoute.php');

add_action('wp_enqueue_scripts', 'enqueue_parent_theme_style');
function enqueue_parent_theme_style()
{
    if (!defined('RH_MAIN_THEME_VERSION')) {
        define('RH_MAIN_THEME_VERSION', '7.2.1');
    }

    $parentStyle = 'parent-style';

    wp_enqueue_style($parentStyle, get_template_directory_uri() . '/style.css');

    if (is_page('Rig Builder') ) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('sweetalert', get_stylesheet_directory_uri() . '/css/sweetalert.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('sweetalert', get_theme_file_uri('/js/libs/sweetalert.min.js'), null, '1.0', true);
        wp_enqueue_script('main-mining-rig-js', get_theme_file_uri('/js/scripts-bundled.js'), null, '1.0', true);

        wp_localize_script('main-mining-rig-js', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
    
    if (is_singular('computer-hardware')) {
       
        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('morris', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('popper-1.12.9', get_theme_file_uri('/js/libs/popper.min.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);        
        wp_enqueue_script('raphael', get_theme_file_uri('/js/libs/raphael-min.js'), null, '1.0', true);        
        wp_enqueue_script('morris', get_theme_file_uri('/js/libs/morris.min.js'), null, '1.0', true);        
        wp_enqueue_script('computerHardwareChart', get_theme_file_uri('/js/charts/ComputerHardwareTemplate.js'), null, '1.0', true);

        wp_localize_script('computerHardwareChart', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
    
    if (is_page('Hardware Overview')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-bootstrap-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));
        
        //js
        //wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);        
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('dataTables', get_theme_file_uri('/js/libs/jquery.dataTables.min.js'), null, '1.0', true);                
        wp_enqueue_script('hardware-overview', get_theme_file_uri('/js/overview/HardwareOverview.js'), null, '1.0', true);

        wp_localize_script('hardware-overview', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
    
    if (is_page('Mining Rigs')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));
        
        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);        
        wp_enqueue_script('dataTables', get_theme_file_uri('/js/libs/jquery.dataTables.min.js'), null, '1.0', true);                
        wp_enqueue_script('mining-overview', get_theme_file_uri('/js/overview/MiningRigs.js'), null, '1.0', true);

        wp_localize_script('mining-overview', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
    
    if (is_page('Mining List')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));
        
        //js
        // ..
    }
}

//////////////////////////////////////////////////////////////////
// Translation
//////////////////////////////////////////////////////////////////
add_action('after_setup_theme', 'rehubchild_lang_setup');
function rehubchild_lang_setup()
{
    load_child_theme_textdomain('rehubchild', get_stylesheet_directory() . '/lang');
}

//////////////////////////////////////////////////////////////////
// Advanced Custom Fields Filters
//////////////////////////////////////////////////////////////////
add_filter( 'acf/load_field/name=related_coins', 'register_algorithm_value_filter' );
function register_algorithm_value_filter( $field ) {
    if ( !isset( $field['filters'] ) ) {
        return $field;
    }

    $field['filters'][] = 'algorithm_value';

    return $field;
}

add_action( 'acf/create_field/type=relationship', 'create_algorithm_value_filter_menu' );
function create_algorithm_value_filter_menu( $field ) {
    if ( 'acf-field-related_coins' !== $field['id'] ) {
        return;
    }

    global $wpdb;

    $choices = [
        'any' => 'Filter by Algorithm',
    ];

    $values = $wpdb->get_col(
        "SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm " .
        "INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id " .
        "WHERE pm.meta_key = 'coin_algorithm' AND p.post_type = 'coin' " .
        "ORDER BY pm.meta_value ASC"
    );
    foreach ( $values as $value ) {
        $choices[ $value ] = $value;
    }
    unset( $values );

    create_field( [
        'type'    => 'select',
        'name'    => 'algorithm_value',
        // The select-algorithm_value class is required by the JS script.
        // You should also keep the hide-if-js class.
        'class'   => 'select-algorithm_value hide-if-js',
        'value'   => '',
        'choices' => $choices,
    ] );
}

add_action( 'admin_print_footer_scripts', 'print_algorithm_value_filter_script', 11 );
function print_algorithm_value_filter_script() {
    $screen = get_current_screen();

    // Add the script only on the wp-admin/post.php page, and only if the post
    // being edited is of the "coin" or "computer-hardware" type.
    if ( 'computer-hardware' === $screen->id || 'coin' === $screen->id ) :
    ?>
    <script>
        jQuery(function ($) {
            var field_name = 'algorithm_value',
                $div = jQuery('#acf-related_coins:has(.has-' + field_name + ')');

            // Moves the Algorithm filter menu to below the Post Type filter menu.
            $div.find('.select-' + field_name).insertAfter(
                $div.find('.select-post_type')
                // And this does the AJAX filtering..
            ).on('change', function () {
                $div.find('.has-' + field_name)
                    .attr('data-' + field_name, this.value);

                $div.find('.select-post_type').trigger('change'); // Run the AJAX.
            });

            // Shows the Algorithm filter menu only if the chosen Post Type is 'coin'.
            // Keep the event's namespace! (but you may use name other than post_type).
            $div.find('.select-post_type').on('change.post_type', function () {
                if ('coin' === this.value) {
                    $div.find('.select-' + field_name).show();
                } else {
                    $div.find('.select-' + field_name).hide();
                }
            });
        });
    </script>
    <?php
    endif;
}

add_action( 'acf/fields/relationship/query/name=related_coins', 'query_posts_by_algorithm_value' );
function query_posts_by_algorithm_value( $options ) {
    // Filters only if the Post Type is exactly 'coin'.
    if ( 'coin' !== $options['post_type'] ) {
        return $options;
    }

    if ( isset( $options['algorithm_value'] ) ) {
        $value = $options['algorithm_value'];

        if ( $value && 'any' !== $value ) {
            if ( ! isset( $options['meta_query'] ) ) {
                $options['meta_query'] = [];
            }

            $options['meta_query'][] = [
                'key'     => 'coin_algorithm',
                'value'   => $value,
                'compare' => '=',
            ];
        }

        // Don't pass to WP_Query.
        unset( $options['algorithm_value'] );
    }

    return $options;
}
