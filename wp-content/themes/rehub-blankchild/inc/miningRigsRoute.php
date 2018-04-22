<?php
add_action('rest_api_init', 'miningRigsRoutes');

function miningRigsRoutes()
{
    register_rest_route('miningRigs/v1', 'createRig', array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => 'createMiningRig',
    ));
    
    register_rest_route('miningRigs/v1', 'allRigs', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'allMiningRigs',
    ));
}

function createMiningRig($data)
{
    //santitize array input
    $rig = json_encode(array_map( 'esc_attr',$data['miningRigPostIds']));
    
    //if ($data['miningRig']->count() > 0) {
        return wp_insert_post(array(
            'post_type' => 'Mining-Rig',
            'post_status' => 'publish',
            'post_title' => $data['title'],
            'meta_input' => array(
                'miningRig' => $rig,
            ),
        ));
    //} 
}

function allMiningRigs() {
    $mainQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'Mining-Rig',
    ));

    $results = array(
        'allRigs' => array(),
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        //get post meta
        $miningRig = json_decode(get_post_meta(get_the_ID(), 'miningRig', true));
        //$keys = array_keys($amazon); // convert associative arrays to index array

//        if (get_post_type() == 'post' or get_post_type() == 'page') {
        array_push($results['allRigs'], array(
            'post_id' => get_the_ID(),
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'miningRig' => $miningRig,
            'category' => get_the_category(),
        ));
//        }
    }
    return $results;
}
