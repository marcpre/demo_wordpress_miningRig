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

// Register Custom Taxonomy
function custom_tax_hardware_creator() {

	$labels = array(
		'name'                       => _x( 'Hardware-Companies', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Hardware-Company', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Hardware-Companies', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'hardware-company', array( 'computer-hardware' ), $args );

}

add_action('init', 'miningRigBuilder_post_types');
add_action( 'init', 'custom_tax_hardware_creator', 0 );
