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
    //if ($data['miningRig']->count() > 0) {
        return wp_insert_post(array(
            'post_type' => 'Mining-Rig',
            'post_status' => 'publish',
            'post_title' => $data['title'],
            'meta_input' => array(
                'miningRig' => json_encode($data['miningRig']),
            ),
        ));
    //} 
}

function allMiningRigs() {
    return "All mining Rigs";
}
