 <?php

/*
Plugin Name: Importer CoinMarketCap Data
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
    add_menu_page('Import Data Page', 'Import Data CoinMarketCap', 'manage_options', 'coinmarketcapimporter-slug', 'importCoinMarketCap_admin_page');
    
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
        wp_die(__('You do not have sufficient pilchards to access this page.'));
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
    submit_button('Import CoinMarketCap Coins');
    echo '</form>';
    
    echo '</div>';
    
}

function importAction()
{
    
    
    /**    
    echo '<div id="message" class="updated fade"><p>'
    .'The "Call Function" button was clicked.' . '</p></div>';
    
    $path = WP_TEMP_DIR . '/button-log.txt';
    
    $handle = fopen($path,"w");
    
    if ($handle == false) {
    echo '<p>Could not write the log file to the temporary directory: ' . $path . '</p>';
    }
    else {
    echo '<p>Log of button click written to: ' . $path . '</p>';
    
    fwrite ($handle , "Call Function button clicked on: " . date("D j M Y H:i:s", time())); 
    fclose ($handle);
    }
    **/
    
    echo '<div id="message" class="updated fade"><p>' . 'The "Call Function" button was clicked.' . '</p></div>';
    
    // Get data for all coins from Coinmarketcap API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://s2.coinmarketcap.com/generated/search/quick_search.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    // Replace quotes that might break the json decode
    $output        = str_replace("'", "", $output);
    // Decode json data to use as a php array
    $outputdecoded = json_decode($output, true);
    
    $i = 0;
    foreach ($outputdecoded as $coin) {
        // Get CMC ID
        $cmcid = $coin["id"];
        // Get public ID
        $pubid = $coin["slug"];
        echo ($cmcid);
        echo ($pubid);
        // Using the CMC ID build the link for logo
        $file_url = "https://s2.coinmarketcap.com/static/img/coins/128x128/" . $cmcid . ".png";
        // Get file logo, rename it and save
        //$image    = file_get_contents($file_url);
        //$img_path = "coins/128x128/" . $pubid . ".png";
        // file_put_contents($img_path, $image);
        
        //TODO check if this works for a custom post type 
        
        // Check if the post already exists
        if (!post_exists($coin['name'])) {
            
            // echo '<div id="message" class="updated fade"><p>' . 'Coin: ' . var_dump($coin) . '</p></div>';
            
            // insert the post
            
            $postId = wp_insert_post(array(
                'post_type' => 'coin',
                'post_status' => 'publish',
                'post_title' => sanitize_textarea_field($coin['name'])
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
            
            
            if (++$i > 3)
                break;
        }
    }
}
?> 