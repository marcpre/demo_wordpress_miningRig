<?php
add_action('rest_api_init', 'miningProfitabilityRoutes');

function miningProfitabilityRoutes()
{
    register_rest_route('miningProf/v1', 'manageMiningProf', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allMiningProfitability',
    ));
}

/**
 * Get all profitability data
 * e.g.: http://localhost/demo_wordpress_rig-builder/wp-json/miningProf/v1/manageMiningProf 
 */

function allMiningProfitability()
{
    global $wpdb;
    
    // show db errors
    $wpdb->show_errors(true);
    $wpdb->print_error();
    
    $mainQuery = $wpdb->get_results( "SELECT * 
                                  FROM wp_whatToMine_API
                                  WHERE id IN( 
                                      SELECT MAX(id) 
                                      FROM wp_whatToMine_API
                                      GROUP BY id ) 
                                  ORDER BY tag 
                                  ASC;" );
                                  
    $results = array(
        	'miningProfitability' => array(),
    );  
                                
    foreach ($mainQuery as $key => $value) {
        array_push($results['miningProfitability'], array(
            'coin' => $value->coin
        ));
    }
    
    return $results;
}
