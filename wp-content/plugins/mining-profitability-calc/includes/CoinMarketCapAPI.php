<?php

use GuzzleHttp\Client;

class CoinMarketCapAPI {

    const CRON_HOOK = 'update_coinmarketcap_api';
    const COIN_MARKET_CAP_URL = 'https://api.coinmarketcap.com/v2/ticker/';
    
    /**
     * Constructor.
     */
    public function __construct() {
        add_action(self::CRON_HOOK, array($this, 'updateCoinMarketCapAPI'));
    }
    
    public function setupCronJob($scheduleTime) {
        //Use wp_next_scheduled to check if the event is already scheduled
        $timestamp = wp_next_scheduled( self::CRON_HOOK );
      
        //If $timestamp == false schedule daily backups since it hasn't been done previously
        if( $timestamp == false ){
            //Schedule the event for right now, then to repeat hourly using the hook 'update_whatToMine_api'
            wp_schedule_event( time(), $scheduleTime, self::CRON_HOOK );
        }
    }
    
    public static function unsetCronJob() {
        // Get the timestamp for the next event.
        $timestamp = wp_next_scheduled( self::CRON_HOOK );
        wp_unschedule_event( $timestamp, self::CRON_HOOK );
    }
    
    public function updateCoinMarketCapAPI() {

        $client = new GuzzleHttp\Client();
        
        $response = $client->request('GET', self::COIN_MARKET_CAP_URL )->getBody();
        $obj = json_decode($response);
        
        // insert 
        global $wpdb;
        
        foreach ($obj->coins as $key => $value) {
            $res = array();
            $res = array(
                'coin' => $key,
                'id_WhatToMine' => $value->id,
                'tag' => $value->tag,
                'algorithm' => $value->algorithm,
                'block_time' => floatval($value->block_time),
                'block_reward' => floatval($value->block_reward),
                'block_reward24' => floatval($value->block_reward24),
                'last_block' => floatval($value->last_block),
                'difficulty' => floatval($value->difficulty),
                'difficulty24' => $value->difficulty24,
                'nethash' => $value->nethash,
                'exchange_rate' => $value->exchange_rate,
                'exchange_rate24' => $value->exchange_rate24,
                'exchange_rate_vol' => floatval($value->exchange_rate_vol),
                'exchange_rate_curr' => $value->exchange_rate_curr,
                'market_cap' => $value->market_cap,
                'estimated_rewards' => $value->estimated_rewards,
                'estimated_rewards24' => $value->estimated_rewards24,
                'btc_revenue' => $value->btc_revenue,
                'btc_revenue24' => $value->btc_revenue24,
                'profitability' => floatval($value->profitability),
                'profitability24' => floatval($value->profitability24),
                'lagging' => $value->lagging,
                'timestamp' => date('Y-m-d H:i:s', $value->timestamp),       
            );
            
            // show db errors
            $wpdb->show_errors(false);
            $wpdb->print_error();

            //check if record exists
            $recordExists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}whatToMine_API
                     WHERE 
                        coin = %s
                        AND btc_revenue = %s 
                        AND estimated_rewards = %s 
                     LIMIT 1",
                     $key, $value->btc_revenue, $value->estimated_rewards
                )
            );

            if ( $recordExists == 0 || $recordExists == null ) {
                try {
                    $res['created_at'] = date('Y-m-d H:i:s');
                    $res['updated_at'] = date('Y-m-d H:i:s');
                    $wpdb->insert("{$wpdb->prefix}whatToMine_API", $res);
                } catch (\Exception $ex) {
                  // ...  
                }
            } else {
                try {
                    $res['updated_at'] = date('Y-m-d H:i:s');
                    $wpdb->update("{$wpdb->prefix}whatToMine_API", $res, array('id' => $recordExists));
                } catch (\Exception $ex) {
                  // ...  
                }
            }
        }    
    }
}
new CoinMarketCapAPI();
