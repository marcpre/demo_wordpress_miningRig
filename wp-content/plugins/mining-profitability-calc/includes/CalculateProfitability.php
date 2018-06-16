<?php

class CalculateProfitability {

    const CRON_HOOK = 'update_profitability_compHardware';
    const COIN_MARKET_CAP_URL = 'https://api.coinmarketcap.com/v2/ticker/';
    
    /**
     * Constructor.
     */
    public function __construct() {
        add_action(self::CRON_HOOK, array($this, 'updateProfitabilityCompHardware'));
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
    
    public function updateProfitabilityCompHardware() {

        // 1. get all gpu && asic parts
        // 2. calculate profitability and save to db
        
    }
}
new CalculateProfitability();
