<?php
add_action('rest_api_init', 'computerHardwareRoutes');

function computerHardwareRoutes()
{
    register_rest_route('rigHardware/v1', 'manageRigHardware', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allRigHardware',
    ));

}

function allRigHardware()
{
    $mainQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'Computer-Hardware',
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
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'category' => get_the_category(),
                'img' => $amazon[$keys[0]]['img'],
                'curreny' => $amazon[$keys[0]]['currency'],
                'price' => $amazon[$keys[0]]['price'],
                'availability' => $amazon[$keys[0]]['extra']['availability'],
                'tellAFriend' => $amazon[$keys[0]]['extra']['itemLinks'][4]['URL'],
                'affiliateLink' => $amazon[$keys[0]]['url'],
            ));
//        }
    }
    return $results;
}
