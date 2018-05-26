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
