<?php

require get_theme_file_path('/inc/computerHardwareRoute.php');
require get_theme_file_path('/inc/miningRigsRoute.php');

function enqueue_parent_theme_style()
{
    if ( is_page( 'Rig Builder' )  || is_page('Mining Rigs')) {
        
        $parentStyle = 'parent-style';
        
        //css
        wp_enqueue_style($parentStyle, get_template_directory_uri() . '/style.css');
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', '//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css', array($parentStyle) );
        wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', 'https://use.fontawesome.com/releases/v5.0.10/js/all.js', NULL, '1.0', true);
        wp_enqueue_script('main-mining-rig-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);

        wp_localize_script('main-mining-rig-js', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }
}

add_action('wp_enqueue_scripts', 'enqueue_parent_theme_style', 1);
