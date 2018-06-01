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
 * e.g.: http://localhost/demo_wordpress_rig-builder/wp-json/miningProf/v1/manageMiningProf?algorithm=Ethash&tag=ETH
 */

function allMiningProfitability($data)
{
    global $wpdb;
    
    // show db errors
    $wpdb->show_errors(false);
    $wpdb->print_error();
    
    // $data['term']
    
    $mainQuery = $wpdb->get_results( "SELECT *
    FROM wp_whatToMine_API
    WHERE id IN(
        SELECT max(date)
        FROM wp_whatToMine_API
        WHERE ALGORITHM = \"" . sanitize_text_field($data['algorithm']) . "\" and 
        TAG = \"" . sanitize_text_field($data['tag']) . "\"
        GROUP BY id)  
    ORDER BY profitability DESC
    LIMIT 1;" );
                                  
    $results = array(
        	'miningProfitability' => array(),
    );  
                                
    foreach ($mainQuery as $key => $value) {
        array_push($results['miningProfitability'], array(
            'id' => $key, //artificial coin id
            'coin' => $value->coin,
            'tag' => $value->tag,
            'algorithm' => $value->algorithm,
            'block_time' => floatval($value->block_time),
            'block_reward' => floatval($value->block_reward),
            'block_reward24' => floatval($value->block_reward24),
            'last_block' => floatval($value->last_block),
            'difficulty' => floatval($value->difficulty),
            'difficulty24' => floatval($value->difficulty24),
            'nethash' => floatval($value->nethash),
            'exchange_rate' => floatval($value->exchange_rate),
            'exchange_rate24' => floatval($value->exchange_rate24),
            'exchange_rate_vol' => floatval($value->exchange_rate_vol),
            'exchange_rate_curr' => $value->exchange_rate_curr,
            'market_cap' => $value->market_cap,
            'estimated_rewards' => floatval($value->estimated_rewards),
            'estimated_rewards24' => floatval($value->estimated_rewards24),
            'btc_revenue' => floatval($value->btc_revenue),
            'btc_revenue24' => floatval($value->btc_revenue24),
            'profitability' => floatval($value->profitability),
            'profitability24' => floatval($value->profitability24),
            'timestamp' => date('Y-m-d H:i:s', $value->timestamp),     
        ));
    }
    
    return $results;
}



