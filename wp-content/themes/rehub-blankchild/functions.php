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
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);        
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
/* function acf_load_coin_algorithm_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    // get the textarea value from options page without any formatting
    $choices = get_field('coin_algorithm', 'option', false);
    // explode the value so that each line is a new array piece
    $choices = explode("\n", $choices);
    // remove any unwanted white space
    $choices = array_map('trim', $choices);
    // loop through array and add to field 'choices'
    if( is_array($choices) ) {   
        foreach( $choices as $choice ) { 
            $field['choices'][ $choice ] = $choice;
        }
        
    }
    return $field;
}

add_filter('acf/load_field/name=coin_algorithm', 'acf_load_coin_algorithm_field_choices');
*/
