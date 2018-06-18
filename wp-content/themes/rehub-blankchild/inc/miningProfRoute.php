<?php
add_action('rest_api_init', 'miningProfitabilityRoutes');

function miningProfitabilityRoutes()
{
    register_rest_route('miningProf/v1', 'manageMiningProf', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allMiningProfitability',
    ));
    
    register_rest_route('miningProf/v1', 'getLatestQuote', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getLatestCoinQuote',
    ));
    
    register_rest_route('miningProf/v1', 'getProfitability', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'getProfitability',
    ));
}

/**
 * Get profitability data for certain algorithm and tag
 * e.g.: localhost/demo_wordpress_rig-builder/wp-json/miningProf/v1/manageMiningProf?algorithm=Ethash&tag=ETH
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
        SELECT max(id)
        FROM wp_whatToMine_API
        WHERE ALGORITHM = \"" . sanitize_text_field($data['algorithm']) . "\" and 
        TAG = \"" . sanitize_text_field($data['tag']) . "\"
        GROUP BY id)  
    ORDER BY updated_at DESC
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

/**
 * Get price data for certain coin
 * e.g.: localhost/demo_wordpress_rig-builder/wp-json/miningProf/v1/getLatestQuote?symbol=ETH
 */
function getLatestCoinQuote($data)
{
    global $wpdb;
    
    // show db errors
    $wpdb->show_errors(false);
    $wpdb->print_error();

    $mainQuery = $wpdb->get_results( "SELECT * 
        FROM {$wpdb->prefix}ticker t 
        INNER JOIN wp_coins c ON c.id = t.coin_id
        WHERE c.symbol = \"" . sanitize_text_field($data['symbol']) . "\"
        ORDER BY c.created_at ASC
        LIMIT 1;");
    
    $results = array(
        'coin' => array(),
    );  
                                
    foreach ($mainQuery as $key => $value) {
        array_push($results['coin'], array(
            'id' => $key, //artificial coin id
            'name' => $value->name,
            'symbol' => $value->symbol,
            'price' => floatval($value->price),
            'circulating_supply' => floatval($value->circulating_supply),
            'total_supply' => floatval($value->total_supply),
            'volume_24h' => floatval($value->volume_24h),
            'market_cap' => floatval($value->market_cap),
            'percent_change_1h' => floatval($value->percent_change_1h),
            'percent_change_24h' => floatval($value->percent_change_24h),
            'percent_change_7d' => floatval($value->percent_change_7d),
        ));
    }
    
    return $results;
}

/**
 * Get profitability data for certain algorithm and tag
 * e.g.: localhost/demo_wordpress_rig-builder/wp-json/miningProf/v1/getProfitability?post_id=391
 */

function getProfitability($data)
{
    global $wpdb;
    
    // show db errors
    $wpdb->show_errors(false);
    $wpdb->print_error();
        
    $mainQuery = $wpdb->get_results( "SELECT *
    FROM 
        {$wpdb->prefix}miningprofitability
    WHERE 
        post_id = \"" . sanitize_text_field($data['post_id']) . "\"
    ORDER BY created_at DESC
    LIMIT 30;" );// LIMIT are the days to display, in this query 30 days
                                  
    $results = array(
            'profitabilityCompHardware' => array(),
    );  
                                
    foreach ($mainQuery as $key => $value) {
        array_push($results['profitabilityCompHardware'], array(
            'id' => $key, //artificial coin id 
            'daily_netProfit' => floatval($value->daily_netProfit),
            'daily_grossProfit' => floatval($value->daily_grossProfit),
            'daily_costs' => floatval($value->daily_costs),
            'created_at' => date('Y-m-d', strtotime($value->created_at)),
        ));
    }
        
    return $results;
}
