<?php

class CalculateProfitability
{

    const CRON_HOOK = 'update_profitability_compHardware';

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action(self::CRON_HOOK, array($this, 'updateProfitabilityCompHardware'));
    }

    public function setupCronJob($scheduleTime)
    {
        //Use wp_next_scheduled to check if the event is already scheduled
        $timestamp = wp_next_scheduled(self::CRON_HOOK);

        //If $timestamp == false schedule daily backups since it hasn't been done previously
        if ($timestamp == false) {
            //Schedule the event for right now, then to repeat hourly using the hook 'update_whatToMine_api'
            wp_schedule_event(time(), $scheduleTime, self::CRON_HOOK);
        }
    }

    public static function unsetCronJob()
    {
        // Get the timestamp for the next event.
        $timestamp = wp_next_scheduled(self::CRON_HOOK);
        wp_unschedule_event($timestamp, self::CRON_HOOK);
    }

    public function updateProfitabilityCompHardware()
    {

        global $wpdb;

        // show db errors
        $wpdb->show_errors(false);
        $wpdb->print_error();

        /**
         * Get Data
         */
        /*
        $compHardware = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'Computer-Hardware',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    //'terms' => array('graphic-card', 'asic'), // TODO open when when you also add ASIC miners!!!
                    //'terms' => array('asic'),
                    'terms' => array('graphic-card', 'asic'),
                ),
            ),
        ));
        */
        /**
         * TESTING FOR CERTAIN POST IDs
         */

        // TODO use this for testing !!!
        $asicIDs = array(6403);
        // $gpuIDs = array(391, 175);
        // $ids = array_merge($asicIDs, $gpuIDs);
        $ids = array_merge($asicIDs);
        
        $compHardware = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'Computer-Hardware',
            'post__in' => $ids,
        ));


        // Get current BTC price
        $coinSymbol = "BTC"; //Get BTC to USD price        
        $coinValueRes = $wpdb->get_results("SELECT *
            FROM {$wpdb->prefix}ticker t
            INNER JOIN wp_coins c ON c.id = t.coin_id
            WHERE c.symbol = \"" . $coinSymbol . "\"
            ORDER BY t.updated_at DESC
            LIMIT 1;");
            
        if (!isset($coinValueRes)) {
            error_log("No  BTC Price available");
            return;
        }

        while ($compHardware->have_posts()) {
            $compHardware->the_post();
            $postId = get_the_ID();
            
            if (!isset($postId)) {
                continue; // skip current iteration within loop
            }

            // get meta coin information
            //$posts = get_field('related_coins', $postId);

            //if ($posts):
                //returns FIRST listed coin symbol!!!!
                // $coinSymbol = get_field('symbol', $posts['0']->ID); // is for what to mine the TAG
                // $coinAlgorithm = get_field('coin_algorithm', $posts['0']->ID);
            // endif;

            $compHardwareAlgorithm = get_field('algorithm', $postId);
            
            try {
                // get the latest entry for the last 24h, where the highest profitability is the first entry
                $whatToMineRes = $wpdb->get_results("SELECT *
                    FROM {$wpdb->prefix}whattomine_api
                    WHERE id IN(
                        SELECT max(id)
                        FROM {$wpdb->prefix}whattomine_api
                        WHERE ALGORITHM = \"" . $compHardwareAlgorithm . "\"" . "
                        GROUP BY id)
                        AND updated_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
                    ORDER BY profitability24 DESC
                    LIMIT 1;");
            } catch (\Exception $ex) {
                error_log($ex);
            }
            
            // check if both values are set
            if (!isset($whatToMineRes)) {
                continue; // skip current iteration within loop
            }
            
            // set IDs
            $coin_id = intval($coinValueRes[0]->id);
            $whatToMine_id = intval($whatToMineRes[0]->id);

            /**
             * Calculate Profitability
             */
            // get variables
            $Exchange_Rate_24_in_BTC = $whatToMineRes[0]->exchange_rate24;
            $BTCPriceInUSD = floatval($coinValueRes[0]->price); //Bitcoin Price
            $hashRate = floatval(get_field('hash_rate', $postId));

            $networkHashRate = floatval($whatToMineRes[0]->nethash);
            $numberOfEquipment = 1;
            $blockTime = floatval($whatToMineRes[0]->block_time);
            $blockReward = floatval($whatToMineRes[0]->block_reward);
            $wattofGPUs = floatval(get_field('watt_estimate', $postId));
            $energyCosts = 0.1;

            // calculate revenue
            $userRatio = ($hashRate * $numberOfEquipment) / $networkHashRate;
            $blocksPerMinute = 60 / $blockTime;
            $rewardPerMinute = $blocksPerMinute * $blockReward;

            $revenuePerDayInBTC = $userRatio * $rewardPerMinute * 60 * 24 * $Exchange_Rate_24_in_BTC;
            $revenuePerDayInUSD = $revenuePerDayInBTC * $BTCPriceInUSD;
            // calculate costs
            if ($energyCosts === "" || $energyCosts === null || isset($energyCosts)) {
                $energyCosts = 0.1;
            }

            $powerCostsPerHourInUSD = (($wattofGPUs * $numberOfEquipment) / 1000) * $energyCosts;
            $powerCostsPerDayInUSD = $powerCostsPerHourInUSD * 24;

            // final results
            $earningsPerDayInUSD = $revenuePerDayInUSD - $powerCostsPerDayInUSD;

            /**
             * Insert into db
             */
            $res = array();
            $res = array(
                'post_id' => intval($postId),
                'coin_id' => intval($coin_id),
                'whatToMine_id' => intval($whatToMine_id),
                'daily_netProfit' => number_format(floatval($earningsPerDayInUSD), 12),
                'daily_grossProfit' => number_format(floatval($revenuePerDayInUSD), 12),
                'daily_costs' => number_format(floatval($powerCostsPerDayInUSD), 12),
            );

            try {
                $res['created_at'] = date('Y-m-d H:i:s');
                $res['updated_at'] = date('Y-m-d H:i:s');
                $wpdb->insert("{$wpdb->prefix}miningprofitability", $res);
            } catch (\Exception $ex) {
                error_log($ex);
            }
        }
    }
}
new CalculateProfitability();
