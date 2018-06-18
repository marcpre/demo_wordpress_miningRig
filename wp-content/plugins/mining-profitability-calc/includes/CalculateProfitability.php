<?php

class CalculateProfitability {

    const CRON_HOOK = 'update_profitability_compHardware';
    
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

        global $wpdb;

        // show db errors
        $wpdb->show_errors(false);
        $wpdb->print_error();
        
        /**
         * Get Data
         */
        $compHardware = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'Computer-Hardware',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => array('graphic-card', 'asic'),
                ),
            ),
        ));
        
        var_dump($compHardware);
         
        while ($compHardware->have_posts()) {
            $compHardware->the_post();
            $postId = get_the_ID();
            // $postCategory = get_the_category($postId);
            // $postCategory[0]->slug;
                    
            $whatToMineRes = $wpdb->get_results( "SELECT *
                FROM wp_whatToMine_API
                WHERE id IN(
                    SELECT max(id)
                    FROM wp_whatToMine_API
                    WHERE ALGORITHM = \"" . sanitize_text_field(get_field('algorithm', $postId)) . "\" and 
                    TAG = \"" . sanitize_text_field(get_field('tag', $postId)[0]) . "\"
                    GROUP BY id)  
                ORDER BY updated_at DESC
                LIMIT 1;" );
            
            $coinValueRes = $wpdb->get_results( "SELECT * 
                FROM {$wpdb->prefix}ticker t 
                INNER JOIN wp_coins c ON c.id = t.coin_id
                WHERE c.symbol = \"" . sanitize_text_field(get_field('tag', $postId)[0]) . "\"
                ORDER BY c.created_at ASC
                LIMIT 1;");
                       
            /**
             * Calculate Profitability
             */
            // get variables
            $selectedCoinPriceInUSD = floatval($coinValueRes[0]->price);
            $hashRate = floatval(get_field('hash_rate', $postId));

            $networkHashRate = floatval($whatToMineRes[0]->nethash);
            $numberOfEquipment = 1;
            $blockTime = floatval($whatToMineRes[0]->block_time);
            $blockReward = floatval($whatToMineRes[0]->block_reward);
            $wattofGPUs = get_field('watt_estimate', $postId)[0];
            $energyCosts = 0.1;
            
            // calculate revenue
            $userRatio = ($hashRate * $numberOfEquipment) / $networkHashRate;
            $blocksPerMinute = 60 / $blockTime;
            $rewardPerMinute = $blocksPerMinute * $blockReward;
 
            $revenuePerDayInUSD = $userRatio * $rewardPerMinute * 60 * 24 * $selectedCoinPriceInUSD;
            
            // calculate costs
            if($energyCosts === "" || $energyCosts === null || isset($energyCosts)) $energyCosts = 0.1;
            
            $powerCostsPerHourInUSD = (($wattofGPUs * $numberOfEquipment) / 1000) * $energyCosts;
            $powerCostsPerDayInUSD = $powerCostsPerHourInUSD * 24;
            
            // final results
            $earningsPerDayInUSD = $revenuePerDayInUSD - $powerCostsPerDayInUSD;
            
            /**
             * Insert into db
             */
            $res = array();
            $res = array(
                'post_id' => $postId,
                'coin_id' => $coinValueRes[0]->id,
                'whatToMine_id' => $whatToMineRes[0]->id,
                'daily_netProfit' => number_format(floatval($earningsPerDayInUSD), 12),
                'daily_grossProfit' => number_format(floatval($revenuePerDayInUSD), 12),
                'daily_costs' => number_format(floatval($powerCostsPerDayInUSD), 12),
            );
            
            try {
                $res['created_at'] = date('Y-m-d H:i:s');
                $res['updated_at'] = date('Y-m-d H:i:s');
                $wpdb->insert("{$wpdb->prefix}miningProfitability", $res);
            } catch (\Exception $ex) {
                // ...  
            }
        }
    }
}
new CalculateProfitability();
