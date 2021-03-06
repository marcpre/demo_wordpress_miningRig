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
   
    // Validation
    if(strlen($data['title']) < 4) {
        die("Your title has to be longer than 3 characters.");
    }
    
    if(sizeof($data['miningRigPostIds']) == 0) {
        die("Please add hardware parts to your built.");
    }
    
    
    // TODO validation for content is removed
    /* if(strlen($data['content']) < 80) {
        die("Your mining rig descriptions has to be longer than 80 characters.");
    } */
    
    // Santitize array input
    $rigHardwareArray = array_map('esc_attr', $data['miningRigPostIds']);

    // content field
    $dataContent = sanitize_textarea_field($data['content']); 
    $rigDescription = $dataContent . "\n\n" . '[content-egg module=Amazon template=list]';
    
    // sharing fields
    $redditSharing = sanitize_textarea_field($data['reddit_sharing']);
    $twitchSharing = sanitize_textarea_field($data['twitch_sharing']);
    $vbcodeSharing = sanitize_textarea_field($data['vbcode_sharing']);
    
    $miningRigsId = wp_insert_post(array(
        'post_type' => 'Mining-Rig',
        'post_status' => 'publish',
        'post_content' => $rigDescription,
        'post_title' => sanitize_textarea_field($data['title']),
        'meta_input' => array(
            'miningRig' => json_encode($rigHardwareArray),
            'reddit_sharing' => json_encode($redditSharing),
            'twitch_sharing' => json_encode($twitchSharing),
            'vbcode_sharing' => json_encode($vbcodeSharing),
        ),
    ));
    
    setCustomFieldContentEgg($miningRigsId, $rigHardwareArray);
    $permalink = get_permalink($miningRigsId); 

    return $permalink;
}

function setCustomFieldContentEgg($miningRigsId, $rigHardwareArray) 
{
    // $miningRigsId = 112; //Mining Rig Post
    // $rigHardwareArray = ["71","59","71","59", "67"];
    
    $results = array();
        
    foreach ($rigHardwareArray as $computerHardwareId) {
        $id = intval($computerHardwareId);
        
        $cfContentEgg = get_post_meta($id, '_cegg_data_Amazon', true);

        $results  = array_merge($results, $cfContentEgg);
    }
    
    return serialize(update_post_meta( $miningRigsId, '_cegg_data_Amazon', $results )); 
}

function allMiningRigs()
{
    $miningRigsQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'Mining-Rig',
    ));

    $results = array(
        'generalInfo' => array(),
    );

    while ($miningRigsQuery->have_posts()) {
        $miningRigsQuery->the_post();

        $miningRigPostIds = json_decode(get_post_meta(get_the_ID(), 'miningRig', true));

        $computerHardwareQuery = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'Computer-Hardware',
            'post__in' => $miningRigPostIds,
        ));

        //get post meta
        // $miningRig = json_decode(get_post_meta(get_the_ID(), 'miningRig', true));
        //$keys = array_keys($amazon); // convert associative arrays to index array
        // if (get_post_type() == 'post' or get_post_type() == 'page') {
        array_push($results['generalInfo'], array(
            'post_id' => get_the_ID(),
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'category' => get_the_category(),
            'totalPrice' => getTotalPrice($computerHardwareQuery),
            'miningHardware' => getHardware($computerHardwareQuery),
        ));
    }
    return $results;
}

function getHardware($computerHardwareQuery){
        $results = array();
        
        foreach ($computerHardwareQuery->posts as $item) {
            // get content-egg data
            $amazon = get_post_meta($item->ID, '_cegg_data_Amazon', true);
            $keys = array_keys($amazon);
            
            // get post category
            // $category = wp_get_post_categories($item->ID);
              
            array_push($results, array(
                'partCategory' => 'x',
                'partTitle' => $item->post_title,
                //'price' => $amazon[$keys[0]]['price'],
                'post_id' => $item->ID,
                'unique_id' => $amazon[$keys[0]]['unique_id'],
                'manufacturer' => $amazon[$keys[0]]['manufacturer'],
                'postImage' => wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'single-post-thumbnail'),
                'amzImg' => $amazon[$keys[0]]['img'],
                'currency' => $amazon[$keys[0]]['currency'],
                'price' => $amazon[$keys[0]]['price'],
                'availability' => $amazon[$keys[0]]['extra']['availability'],
                'tellAFriend' => $amazon[$keys[0]]['extra']['itemLinks'][4]['URL'],
                'affiliateLink' => $amazon[$keys[0]]['url'],
                //'all' => $computerHardwareQuery,

                // 'amazon' => $amazon[$keys],
            ));
        }
        return $results;
}

function getTotalPrice($computerHardwareQuery){
        $totalPrice = 0;
        
        foreach ($computerHardwareQuery->posts as $item) {
            // get content-egg data
            $amazon = get_post_meta($item->ID, '_cegg_data_Amazon', true);
            $keys = array_keys($amazon);
            $totalPrice += (float) $amazon[$keys[0]]['price'];
        }
        return $totalPrice;
}
