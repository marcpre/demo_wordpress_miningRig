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
        //cron jobs
        add_action( 'update_whatToMine_api', 'updateWhatToMineAPI');        

    }
    /**
	* Called on plugin activation
	*/
	public function activate() {
        $this->includes();
		$this->create_tables();
        $this->runCronJobs();

        flush_rewrite_rules();
	}
	/**
    * Includes.
    */
    public function includes() {
		include_once( MinProfCalc_DIR . 'includes/WhatToMineAPI.php' );
		include_once( MinProfCalc_DIR . 'includes/CoinMarketCapAPI.php' );
    }
	/**
	* Called on plugin deactivation
	*/
	public static function deactivate() {
		// TODO
		WhatToMineAPI::unsetCronJob();
		CoinMarketCapAPI::unsetCronJob();
    }
    /**
    * Cron Jobs.
    */
    public function runCronJobs() {
		// TODO
		WhatToMineAPI::setupCronJob('hourly');
		CoinMarketCapAPI::setupCronJob('hourly');
    }
    /**
	* Create tables.
	*/
	public function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        // TODO create new table
		dbDelta( $this->getWhatToMineSchema() );
		dbDelta($this->getCoinSchema());
		dbDelta($this->getTickSchema());
	}
    /**
	* Get table schema.
	* @return string
	*/
	private static function getWhatToMineSchema() {
		global $wpdb;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		} else {
			$collate = '';
		}
		$tables = "
CREATE TABLE {$wpdb->prefix}whatToMine_API (
id bigint(20) NOT NULL AUTO_INCREMENT,
coin longtext NOT NULL,
id_WhatToMine bigint(20) NOT NULL,
tag longtext NOT NULL,
algorithm longtext NOT NULL,
block_time DECIMAL(15,6) NOT NULL,
block_reward DECIMAL(15,6) NOT NULL,
block_reward24 DECIMAL(25,18) NOT NULL,
last_block DECIMAL(20,3) NOT NULL,
difficulty DECIMAL(25,5) NOT NULL,
difficulty24 DECIMAL(25,5) NOT NULL,
nethash DECIMAL(25,5) NOT NULL,
exchange_rate DECIMAL(20,11) NOT NULL,
exchange_rate24 DECIMAL(25,22) NOT NULL,
exchange_rate_vol DECIMAL(27,18) NOT NULL,
exchange_rate_curr longtext NOT NULL,
market_cap longtext NOT NULL,
estimated_rewards longtext NOT NULL,
estimated_rewards24 longtext NOT NULL,
btc_revenue longtext NOT NULL,
btc_revenue24 longtext NOT NULL,
profitability DECIMAL NOT NULL,
profitability24 DECIMAL NOT NULL,
lagging BOOL NULL, 
timestamp datetime NOT NULL,
created_at datetime NULL,
updated_at datetime NULL,
PRIMARY KEY (id)
) $collate;
		";
		return $tables;
	}
	/**
	* Get table schema.
	* @return string
	*/
	private static function getCoinSchema() {
		global $wpdb;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		} else {
			$collate = '';
		}
		$tables = "
CREATE TABLE {$wpdb->prefix}coins (
id bigint(20) NOT NULL AUTO_INCREMENT,
name longtext NOT NULL,
symbol longtext NOT NULL,
website_slug longtext NOT NULL,
rank DECIMAL NOT NULL,
circulating_supply bigint(20) NOT NULL,
total_supply bigint(20) NOT NULL,
max_supply bigint(20) NOT NULL,
last_updated_coin_market_cap datetime NOT NULL,
created_at datetime NULL,
updated_at datetime NULL,
PRIMARY KEY (id)
) $collate;
		";
		return $tables;
	}
	/**
	* Get table schema.
	* @return string
	*/
	private static function getTickSchema() {
		global $wpdb;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		} else {
			$collate = '';
		}
		$tables = "
CREATE TABLE {$wpdb->prefix}ticker (
id bigint(20) NOT NULL AUTO_INCREMENT,
coin_id bigint(20) NOT NULL,
price longtext NOT NULL,
volume_24h bigint(20) NOT NULL,
market_cap bigint(20) NOT NULL,
percent_change_1h DECIMAL NOT NULL,
percent_change_24h DECIMAL NOT NULL,
percent_change_7d DECIMAL NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (coin_id) REFERENCES {$wpdb->prefix}coins(id)
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
