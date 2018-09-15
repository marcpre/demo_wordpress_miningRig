<?php
/**
Plugin Name: Auto Content Creator
Plugin URI: 
Description: Creates content based on content templates and custom fields
Author:
Version: 1.0
Author: Batman
Author URI:
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once 'vendor/autoload.php';

/**
 * Main Auto Content Creator class.
 */
class AutoContentCreator
{
    
    /**
     * Constructor.
     */
    public function __construct() {
        // AutoContentCreator constants.
        define( 'AutoContentCreator_FILE', __FILE__ );
        define( 'AutoContentCreator_DIR', trailingslashit( dirname( __FILE__ ) ) );
        define( 'AutoContentCreator_VERSION', '0.0.1' );
        register_activation_hook( basename( AutoContentCreator_DIR ) . '/' . basename( AutoContentCreator_FILE ), array( $this, 'activate' ) );

        add_action( 'plugins_loaded', array( $this, 'includes' ) );
        add_action( 'init', array( $this, 'maybe_update' ) );
        add_action('admin_menu', array( $this, 'button_menu' ) );
    }
    /**
     * Called on plugin activation
     */
    public function activate() {
        $this->includes();
        flush_rewrite_rules();
    }
    /**
     * Includes.
     */
    public function includes() {
        include_once( AutoContentCreator_DIR . 'includes/SinglePostContent.php' );
        include_once( AutoContentCreator_DIR . 'includes/src/Spintax/Spintax.php' );
    }

    public function button_menu()
    {
        add_menu_page('Import Coin Page', 'Import Content', 'manage_options', 'autoContentCreator-slug', array( $this, 'autoContentCreator_admin_page' ) );

    }

    public function autoContentCreator_admin_page()
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

        echo '<h2>Generate Content</h2>';

        // Check whether the button has been pressed AND also check the nonce
        if (isset($_POST['autoContentCreator']) && check_admin_referer('autoContentCreator_clicked')) {
            // the button has been pressed AND we've passed the security check
            SinglePostContent::main();
            echo 'Done!';
        }

        echo '<form action="options-general.php?page=autoContentCreator-slug" method="post">';

        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field('autoContentCreator_clicked');
        echo '<input type="hidden" value="true" name="autoContentCreator" />';
        submit_button('Generate Computer Hardware Content Templates');
        echo '</form>';

        echo '</div>';

    }
    /**
     * Maybe update AutoContentCreator.
     */
    public function maybe_update() {
        $version = get_option( 'AutoContentCreator_version', 0 );
        if ( version_compare( $version, AutoContentCreator_VERSION, '<' ) ) {
            // $this->create_tables();
            update_option( 'AutoContentCreator_version', AutoContentCreator_VERSION );
        }
    }
}
new AutoContentCreator();
