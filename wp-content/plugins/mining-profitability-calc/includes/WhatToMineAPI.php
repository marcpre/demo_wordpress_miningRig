<?php

use GuzzleHttp\Client;

class WhatToMineAPI {

    /**
     * Constructor.
     */
    public function __construct() {
        // add_action( 'setupCronJob_whatToMine', 'setupCronJob');
        add_action( 'update_whatToMine_api', 'updateWhatToMineAPI');        
    }
    
    public function updateWhatToMineAPI() {
        $whatToMineURL = "http://whattomine.com/coins.json";

        $client = new GuzzleHttp\Client();
        
        $response = $client->request('GET', $whatToMineURL)->getBody();
        $obj = json_decode($response);
        
        // insert 
        global $wpdb;
        
        foreach ($obj->coins as $key => $value) {
            $res = array();
            $res = array(
                'coin' =>$key,
                'id_WhatToMine' => $value->id,
                'tag' => $value->tag,
                'algorithm' => $value->algorithm,
                'block_time' => $value->block_time,
                'block_reward' => $value->block_reward,
                'block_reward24' => $value->block_reward24,
                'last_block' => $value->last_block,
                'difficulty' => $value->difficulty,
                'difficulty24' => $value->difficulty24,
                'nethash' => $value->nethash,
                'exchange_rate' => $value->exchange_rate,
                'exchange_rate24' => $value->exchange_rate24,
                'exchange_rate_vol' => $value->exchange_rate_vol,
                'exchange_rate_curr' => $value->exchange_rate_curr,
                'market_cap' => $value->market_cap,
                'estimated_rewards' => $value->estimated_rewards,
                'estimated_rewards24' => $value->estimated_rewards24,
                'btc_revenue' => $value->btc_revenue,
                'btc_revenue24' => $value->btc_revenue24,
                'profitability' => $value->profitability,
                'profitability24' => $value->profitability24,
                'lagging' => $value->lagging,
                'timestamp' => $value->timestamp,                
            );
            try {
                $wpdb->show_errors(true);
                $wpdb->insert("{$wpdb->prefix}whatToMine_API", $res);
                $wpdb->print_error();
            } catch (\Exception $ex) {
              // ...  
            }
        }
        /*
        $wpdb->insert("{$wpdb->prefix}whatToMine_API", array(

            'estimated_rewards' longtext NOT NULL,
            'estimated_rewards24' longtext NOT NULL,
            'btc_revenue' longtext NOT NULL,
            'btc_revenue24' longtext NOT NULL,
            'profitability' DECIMAL NOT NULL,
            'profitability24' DECIMAL NOT NULL,
            'lagging' BOOLEAN NOT NULL, 
            'timestamp' datetime NOT NULL,            
            
            ) 
        ); */
        
    }
    
    public function setupCronJob($scheduleTime) {
        //Use wp_next_scheduled to check if the event is already scheduled
        $timestamp = wp_next_scheduled( 'update_whatToMine_api' );
      
        //If $timestamp == false schedule daily backups since it hasn't been done previously
        if( $timestamp == false ){
            //Schedule the event for right now, then to repeat twicedaily using the hook 'update_whatToMine_api'
            wp_schedule_event( time(), $scheduleTime, 'update_whatToMine_api' );
        }
    }
}
new WhatToMineAPI();
