<?php

add_action('wp_enqueue_scripts', 'enqueue_parent_theme_style');
function enqueue_parent_theme_style()
{
    //css
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css');
   
    //js
    wp_enqueue_script('main-mining-rig-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
}
