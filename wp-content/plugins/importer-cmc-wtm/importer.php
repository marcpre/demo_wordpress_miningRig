<?php

/*
Plugin Name: Custom Importer Coin Data
Plugin URI: 
Description: 
Author:
Version: 1.0
Author: Batman
Author URI:
*/


add_action('admin_menu', 'button_menu');

function button_menu()
{
    add_menu_page('Import Coin Page', 'Import Coins from CMC/WTM', 'manage_options', 'coinmarketcapimporter-slug', 'importCoinMarketCap_admin_page');
    
}

function importCoinMarketCap_admin_page()
{
    
    // This function creates the output for the admin page.
    // It also checks the value of the $_POST variable to see whether
    // there has been a form submission. 
    
    // The check_admin_referer is a WordPress function that does some security
    // checking and is recommended good practice.
    
    // General check for user permissions.
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient privilege to access this page.'));
    }
    
    // Start building the page
    
    echo '<div class="wrap">';
    
    echo '<h2>Import Data</h2>';
    
    // Check whether the button has been pressed AND also check the nonce
    if (isset($_POST['importCoinMarketCap']) && check_admin_referer('importCoinMarketCap_clicked')) {
        // the button has been pressed AND we've passed the security check
        importAction();
    }
    
    echo '<form action="options-general.php?page=coinmarketcapimporter-slug" method="post">';
    
    // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
    wp_nonce_field('importCoinMarketCap_clicked');
    echo '<input type="hidden" value="true" name="importCoinMarketCap" />';
    submit_button('Import CoinMarketCap/WhatToMine Coins');
    echo '</form>';
    
    echo '</div>';
    
}

function importAction()
{
    
    $COIN_MARKET_CAP_URL = 'https://s2.coinmarketcap.com/generated/search/quick_search.json';
    // $WHAT_TO_MINE_GPU_URL = "https://whattomine.com/coins.json";
    $WHAT_TO_MINE_URL = 'https://whattomine.com/calculators.json';
        
    echo '<div id="message" class="updated fade"><p>' . 'The "Call Function" button was clicked.' . '</p></div>';
    
    // Get data for all coins from Coinmarketcap API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $COIN_MARKET_CAP_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    // Replace quotes that might break the json decode
    $output = str_replace("'", "", $output);
    // Decode json data to use as a php array
    $outputdecoded = json_decode($output, true);
    
    // Get data for all coins from What to mine API
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $WHAT_TO_MINE_URL);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    $outputWTM = curl_exec($c);
    curl_close($c);
    // Replace quotes that might break the json decode
    $outputWTM = str_replace("'", "", $outputWTM);
    // Decode json data to use as a php array
    $outputdecodedWTM = json_decode($outputWTM, true);
    
    // $i = 0;
    echo '<b>Start import</b>';
    echo '<ul>';
    // iterate over what to mine coin
    foreach ($outputdecodedWTM['coins'] as $key => $wtm) {
        $algo = $wtm['algorithm'];

        foreach ($outputdecoded as $coin) {
            if($wtm['tag'] == $coin['symbol']){
                
                // Check if the post already exists
                if (!post_exists($coin['name'])) {
                    // Get CMC ID
                    $cmcid = $coin["id"];
                    // Get public ID
                    $pubid = $coin["slug"];
                    // Using the CMC ID build the link for logo
                    $file_url = "https://s2.coinmarketcap.com/static/img/coins/128x128/" . $cmcid . ".png";

                    // insert the post
                    echo ('<li>' . $pubid . '</li>');
                     
                    $postId = wp_insert_post(array(
                        'post_type' => 'coin',
                        'post_status' => 'publish',
                        'post_title' => sanitize_textarea_field($coin['name']),
                        'post_name' => sanitize_textarea_field($coin['slug']),
                        'meta_input' => array(
                            'symbol' => sanitize_textarea_field($wtm['tag']),
                            'coin_algorithm' => sanitize_textarea_field($algo),
                        ),
                    ));
                    
                    // get image
                    $uploaddir  = wp_upload_dir();
                    $filename = $uploaddir['path'] . '/' . $coin["slug"]  . '.png';
                    
                    $contents = file_get_contents($file_url);
                    $savefile = fopen($filename, 'w');
                    fwrite($savefile, $contents);
                    fclose($savefile);
                    
                    // add image to media library
                    $wp_filetype = wp_check_filetype(basename($filename), null);
                    
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => $filename,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    
                    $thumbnail_id = wp_insert_attachment($attachment, $filename);
                    set_post_thumbnail($postId, $thumbnail_id);
                    
                    // LIMIT THE LOOP
                    // if (++$i > 19)
                    //    break;
                }
            }
        }
    }
    echo '</ul>';
    echo '<b>End import</b>';

/*    
    foreach ($outputdecoded as $coin) {
        // Get CMC ID
        $cmcid = $coin["id"];
        // Get public ID
        $pubid = $coin["slug"];
        // Using the CMC ID build the link for logo
        $file_url = "https://s2.coinmarketcap.com/static/img/coins/128x128/" . $cmcid . ".png";
        
        // Match symbol of coinmarketcap with what to mine
        foreach ($outputdecodedWTM['coins'] as $key => $wtm) {
            if($wtm['tag'] == $coin['symbol']){
                $algo = $wtm['algorithm'];
                break;
            }
        }
        
        // Check if the post already exists
        if (!post_exists($coin['name'])) {
            
            // echo '<div id="message" class="updated fade"><p>' . 'Coin: ' . var_dump($coin) . '</p></div>';
            
            // insert the post
            echo ('<li>' . $pubid . '</li>');
            
            
            $postId = wp_insert_post(array(
                'post_type' => 'coin',
                'post_status' => 'publish',
                'post_title' => sanitize_textarea_field($coin['name']),
                'post_name' => sanitize_textarea_field($coin['slug']),
                'meta_input' => array(
                    'symbol' => sanitize_textarea_field($coin['symbol']),
                    'coin_algorithm' => sanitize_textarea_field($algo),
                ),
            ));
            
            // get image
            $uploaddir  = wp_upload_dir();
            $filename = $uploaddir['path'] . '/' . $coin["slug"]  . '.png';
            
            $contents = file_get_contents($file_url);
            $savefile = fopen($filename, 'w');
            fwrite($savefile, $contents);
            fclose($savefile);
            
            // add image to media library
            $wp_filetype = wp_check_filetype(basename($filename), null);
            
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => $filename,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            $thumbnail_id = wp_insert_attachment($attachment, $filename);
            set_post_thumbnail($postId, $thumbnail_id);
            
            // LIMIT THE LOOP
            if (++$i > 19)
                break;
        }
    }
    */
}
?>
