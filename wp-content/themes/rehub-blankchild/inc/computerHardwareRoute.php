<?php
add_action('rest_api_init', 'computerHardwareRoutes');

function computerHardwareRoutes()
{
    register_rest_route('rigHardware/v1', 'manageRigHardware', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allRigHardware',
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
            'watt' => get_field('watt_estimate', get_the_ID()),
            'hashRatePerSecond' => floatval(get_field('hash_rate', get_the_ID())),
            'availability' => $amazon[$keys[0]]['extra']['availability'],
            'tellAFriend' => $amazon[$keys[0]]['extra']['itemLinks'][4]['URL'],
            'affiliateLink' => $amazon[$keys[0]]['url'],
        ));
//        }
    }
    return $results;
}
