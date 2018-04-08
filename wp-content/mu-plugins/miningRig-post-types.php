<?php

function miningRigBuilder_post_types()
{

    // Like Post Type
    register_post_type('Mining-Rig', array(
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => false,
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
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Computer-Hardware',
            'add_new_item' => 'Add New Computer-Hardware',
            'edit_item' => 'Edit Computer-Hardware',
            'all_items' => 'All Computer-Hardware',
            'singular_name' => 'Computer-Hardware',
        ),
        'menu_icon' => 'dashicons-dashboard',
    ));

}

add_action('init', 'miningRigBuilder_post_types');
