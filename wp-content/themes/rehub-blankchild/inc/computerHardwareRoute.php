<?php
add_action('rest_api_init', 'computerHardwareRoutes');

function computerHardwareRoutes()
{
    register_rest_route('rigHardware/v1', 'manageRigHardware', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allRigHardware',
    ));
    
    register_rest_route('rigHardware/v1', 'allProfitableRigHardware', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allRigHardwareWithProfitability',
    ));
}

/**
 * Get all Hardware that is needed for a rig
 * e.g.: http://localhost/demo_wordpress_rig-builder/wp-json/rigHardware/v1/manageRigHardware?term=cpu
 */

function allRigHardware($data)
{
   
    $mainQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'Computer-Hardware',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => sanitize_text_field($data['term']),
            ),
        ),
    ));

    $results = array(
        'generalInfo' => array(),
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        //get post meta
        $amazon = get_post_meta(get_the_ID(), '_cegg_data_Amazon', true);
        $keys = array_keys($amazon); // convert associative arrays to index array

//        if (get_post_type() == 'post' or get_post_type() == 'page') {
        array_push($results['generalInfo'], array(
            'unique_id' => $amazon[$keys[0]]['unique_id'],
            'post_id' => get_the_ID(),
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'manufacturer' => $amazon[$keys[0]]['manufacturer'],
            'category' => get_the_category(),
            'img' => $amazon[$keys[0]]['img'],
            'currency' => $amazon[$keys[0]]['currency'],
            'price' => $amazon[$keys[0]]['price'],
            'watt' => floatval(get_field('watt_estimate', get_the_ID())),
            'algorithm' => get_field('algorithm', get_the_ID()),
            'hashRatePerSecond' => floatval(get_field('hash_rate', get_the_ID())),
            'availability' => $amazon[$keys[0]]['extra']['availability'],
            'tellAFriend' => $amazon[$keys[0]]['extra']['itemLinks'][4]['URL'],
            'affiliateLink' => $amazon[$keys[0]]['url'],
        ));
//        }
    }
    return $results;
}

/**
 * Hardware Overview - Get all Hardware that is needed for a rig
 * e.g.: http://localhost/demo_wordpress_rig-builder/wp-json/rigHardware/v1/allProfitableRigHardware?term=graphic-card  
 */

function allRigHardwareWithProfitability($data)
{
    
    global $wpdb;
    
    // show db errors
    $wpdb->show_errors(false);
    $wpdb->print_error();
    
    /*
    // ############################################################################
    // # This query works, BUT is slower --> HOWEVER, it can be better explained! #
    // ############################################################################
    */
    $mainQuery = $wpdb->get_results( "SELECT *
        FROM {$wpdb->prefix}posts p INNER JOIN 
            {$wpdb->prefix}miningprofitability m 
            ON m.post_id = p.ID
        WHERE m.created_at =(SELECT MAX(pp2.created_at)
                            FROM {$wpdb->prefix}miningprofitability pp2
                            WHERE pp2.post_id = m.post_id)
        ORDER BY
            m.daily_grossProfit
        DESC;" );
    /*
    $mainQuery = $wpdb->get_results("SELECT *
        FROM {$wpdb->prefix}posts t
        INNER JOIN (
            SELECT post_id, daily_netProfit, daily_grossProfit, daily_costs, max(created_at) AS MaxDate
            FROM {$wpdb->prefix}miningprofitability 
            GROUP BY post_id
        ) tm ON t.ID  = tm.post_id  
        ORDER BY tm.daily_netProfit DESC;");
    */
    $results = array(
        'profRigHardware' => array(),
    );
                            
    foreach ($mainQuery as $key => $value) {
        
        $post_id = $mainQuery[$key]->ID;
        
        //get post meta
        $amazon = get_post_meta($post_id, '_cegg_data_Amazon', true);
        $keys = array_keys($amazon); // convert associative arrays to index array
        
        array_push($results['profRigHardware'], array(
            'unique_id' => $amazon[$keys[0]]['unique_id'],
            'title' => get_the_title($post_id),
            'permalink' => get_the_permalink($post_id),
            'manufacturer' => $amazon[$keys[0]]['manufacturer'],
            'category' => get_the_category($post_id),
            'smallImg' => get_the_post_thumbnail_url($post_id, 'thumbnail'),
            // 'smallImg' => $amazon[$keys[0]]['extra']['smallImage'], 
            'currency' => $amazon[$keys[0]]['currency'],
            'price' => $amazon[$keys[0]]['price'],
            'watt' => floatval(get_field('watt_estimate', $post_id)),
            'algorithm' => get_field('algorithm', $post_id),
            'hashRatePerSecond' => floatval(get_field('hash_rate', $post_id)) / 1000000,
            'affiliateLink' => $amazon[$keys[0]]['url'],
            'daily_netProfit' => number_format( (float) $mainQuery[$key]->daily_netProfit, 2),
            // 'created_at' => date('Y-m-d H:i:s', strtotime( $mainQuery[$key]->MaxDate)),
            'created_at' => date('Y-m-d H:i:s', strtotime( $mainQuery[$key]->created_at)),              
        ));
    }
    return $results;
}
