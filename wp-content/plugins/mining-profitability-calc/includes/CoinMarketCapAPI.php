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
        
        // get coinmarketcap data
        $response = $client->request('GET', self::COIN_MARKET_CAP_URL )->getBody();
        $objCoinMarketCap = json_decode($response);
               
        // insert 
        global $wpdb;
        
        foreach ($objCoinMarketCap->data as $key => $value) {
            
            //coin table
            $resCoin = array();
            $resCoin = array(
                'id_coinMarketCap' => $value->id,
                'name' => $value->name,
                'symbol' => $value->symbol,
                'website_slug' => $value->website_slug,
                'rank' => $value->rank,
                'circulating_supply' => floatval($value->circulating_supply),
                'total_supply' => floatval($value->total_supply),
                'max_supply' => floatval($value->max_supply),
                'last_updated_coin_market_cap' => date('Y-m-d H:i:s', $value->last_updated),       
            );
                        
            // show db errors
            $wpdb->show_errors(false);
            $wpdb->print_error();

            //check if the above record exists already in the db
            $recordCoinExists = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}coins
                    WHERE 
                        symbol = %s
                        AND name = %s 
                        AND rank = %s 
                    LIMIT 1",
                    $value->symbol, $value->name, $value->rank
                )
            );

            // if record does exist $coin_id is the existing id
            if ( $recordCoinExists == 0 || $recordCoinExists == null ) { 
                // create coin in db and return id
                $resCoin['created_at'] = date('Y-m-d H:i:s');
                $resCoin['updated_at'] = date('Y-m-d H:i:s');
                $coin_id = $wpdb->insert("{$wpdb->prefix}coins", $resCoin);
            } else { 
                // exisiting id from db
                $coin_id = $recordCoinExists["0"]->id;                
            };
            // coin exists in db until this point!
            
            //ticker table
            $tick = $value->quotes->USD;
            $resTicker = array();
            $resTicker = array(
                'coin_id' => intval($coin_id),
                'price' => $tick->price,
                'volume_24h' => $tick->volume_24h,
                'market_cap' => floatval($tick->market_cap),
                'percent_change_1h' => floatval($tick->percent_change_1h),
                'percent_change_24h' => floatval($tick->percent_change_24h),
                'percent_change_7d' => floatval($tick->percent_change_7d),
            );
            
            //check if the above ticker record exists already in the db
            $recordTickerExists = $wpdb->get_results(
            $wpdb->prepare(
                 "SELECT * FROM {$wpdb->prefix}ticker
                 WHERE 
                     price = %s
                     AND coin_id = %s 
                     AND market_cap = %s 
                 LIMIT 1",
                 floatval($tick->price), $coin_id, floatval($tick->market_cap)
                )
            );
           
            if ( $recordTickerExists == 0 || $recordTickerExists == null ) {
                // ticker does not exists else do nothing
                try {
                    $resTicker['created_at'] = date('Y-m-d H:i:s');
                    $resTicker['updated_at'] = date('Y-m-d H:i:s');
                    $wpdb->insert("{$wpdb->prefix}ticker", $resTicker);
                } catch (\Exception $ex) {
                  // ...  
                }
            } else {
                error_log("Ticker exists! - " . $value->name . " for the following query: " . $wpdb->last_query);
            }
        }
    }
}
new CoinMarketCapAPI();
