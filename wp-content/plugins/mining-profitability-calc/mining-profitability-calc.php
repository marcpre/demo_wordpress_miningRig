<?php
/**
Plugin Name: Mining Rig Profitability Calculator
description: Get Data via APIs
Version: 1.0
Author: Batman
License: GPLv2 or later
Text Domain: mining-profitability-calc
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'vendor/autoload.php';

/**
 * Main Mining Profitability Calculator class.
 */
class Mining_Profitability_Calculator {

     /**
     * Constructor.
     */
    public function __construct() {
        // MinProfCalc constants.
        define( 'MinProfCalc_FILE', __FILE__ );
        define( 'MinProfCalc_DIR', trailingslashit( dirname( __FILE__ ) ) );
		define( 'MinProfCalc_VERSION', '0.0.1' );
        register_activation_hook( basename( MinProfCalc_DIR ) . '/' . basename( MinProfCalc_FILE ), array( $this, 'activate' ) );
        // add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'plugins_loaded', array( $this, 'includes' ) );
		add_action( 'init', array( $this, 'maybe_update' ) );
    }
    /**
	 * Called on plugin activation
	 */
	public function activate() {
        $this->includes();
        $this->runCronJobs();
		$this->create_tables();

        flush_rewrite_rules();
    }
    /**
     * Includes.
     */
    public function includes() {
        include_once( MinProfCalc_DIR . 'includes/WhatToMineAPI.php' );
    }
    /**
     * Cron Jobs.
     */
    public function runCronJobs() {
        WhatToMineAPI::setupCronJob('twicedaily');
    }
	/**
	 * Create tables.
	 */
	public function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        // TODO create new table
        // dbDelta( $this->get_schema() );
	}
	/**
	 * Get table schema.
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		} else {
			$collate = '';
		}
		$tables = "
CREATE TABLE {$wpdb->prefix}mining_prof_calc_history (
id bigint(20) NOT NULL AUTO_INCREMENT,
PRIMARY KEY  (id)
) $collate;
		";
		return $tables;
	}
	/**
	 * Maybe update MinProfCalc.
	 */
	public function maybe_update() {
		$version = get_option( 'MinProfCalc_version', 0 );
		if ( version_compare( $version, MinProfCalc_VERSION, '<' ) ) {
			$this->create_tables();
			update_option( 'MinProfCalc_version', MinProfCalc_VERSION );
		}
	}
}
new Mining_Profitability_Calculator();
