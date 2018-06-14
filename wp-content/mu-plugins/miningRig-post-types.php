<?php

function miningRigBuilder_post_types()
{

    // Mining-Rig Post Type
    register_post_type('Mining-Rig', array(
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt','comments','revisions'),
        'public' => true,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Mining Rigs',
            'add_new_item' => 'Add New Mining Rig',
            'edit_item' => 'Edit Mining Rig',
            'all_items' => 'All Mining Rigs',
            'singular_name' => 'Mining Rig',
        ),
        'menu_icon' => 'dashicons-hammer',
    ));

    // Computer Hardware Post Type
    register_post_type('Computer-Hardware', array(
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'public' => true,
        // 'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Computer-Hardware',
            'add_new_item' => 'Add New Computer-Hardware',
            'edit_item' => 'Edit Computer-Hardware',
            'all_items' => 'All Computer-Hardware',
            'singular_name' => 'Computer-Hardware',
        ),
        'menu_icon' => 'dashicons-dashboard',
        'taxonomies' => array( 'category' ),
    ));
    
    // Coin Post Type
    register_post_type('coin', array(
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Coins',
            'add_new_item' => 'Add New Coin',
            'edit_item' => 'Edit Coin',
            'all_items' => 'All Coins',
            'singular_name' => 'Coin',
        ),
        'menu_icon' => 'dashicons-money',
    ));

    // Mining Pools Post Type
    register_post_type('miningPools', array(
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Mining Pools',
            'add_new_item' => 'Add New Mining Pool',
            'edit_item' => 'Edit Mining Pool',
            'all_items' => 'All Mining Pools',
            'singular_name' => 'Mining Pool',
        ),
        'menu_icon' => 'dashicons-networking',
    ));    
    
    // Mining Store Owners
    register_post_type('miningStores', array(
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt','comments','revisions'),
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Mining Stores',
            'add_new_item' => 'Add New Mining Store',
            'edit_item' => 'Edit Mining Store',
            'all_items' => 'All Mining Store',
            'singular_name' => 'Mining Store',
        ),
        'menu_icon' => 'dashicons-store',
    ));  

}

add_action('init', 'miningRigBuilder_post_types');
