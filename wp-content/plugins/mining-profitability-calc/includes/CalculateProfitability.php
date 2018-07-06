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
        $compHardware = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'Computer-Hardware',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    //'terms' => array('graphic-card', 'asic'), // TODO open when when you also add ASIC miners!!!
                    'terms' => array('asic'),
                    //'terms' => array('graphic-card', 'asic'),
                ),
            ),
        ));

        while ($compHardware->have_posts()) {
            $compHardware->the_post();
            $postId = get_the_ID();
            
            if (!isset($postId)) {
                continue; // skip current iteration within loop
            }

            // get meta coin information
            $posts = get_field('related_coins', $postId);

            if ($posts):
                // foreach ($posts as $post): // variable must be called $post (IMPORTANT)
                // setup_postdata($post);
//returns FIRST listed coin symbol!!!!
                $coinSymbol = get_field('symbol', $posts['0']->ID);
                $coinAlgorithm = get_field('coin_algorithm', $posts['0']->ID);
                // endforeach;
                // wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
            endif;

            try {
                $whatToMineRes = $wpdb->get_results("SELECT *
                    FROM {$wpdb->prefix}whattomine_api
                    WHERE id IN(
                        SELECT max(id)
                        FROM {$wpdb->prefix}whattomine_api
                        WHERE ALGORITHM = \"" . sanitize_text_field(get_field('algorithm', $postId)) . "\" and
                        TAG = \"" . sanitize_text_field(get_field('tag', $postId)[0]) . "\"
                        GROUP BY id)
                    ORDER BY updated_at DESC
                    LIMIT 1;");

                $coinValueRes = $wpdb->get_results("SELECT *
                    FROM {$wpdb->prefix}ticker t
                    INNER JOIN wp_coins c ON c.id = t.coin_id
                    WHERE c.symbol = \"" . sanitize_text_field(get_field('tag', $postId)[0]) . "\"
                    ORDER BY c.created_at ASC
                    LIMIT 1;");

            } catch (\Exception $ex) {
                error_log($ex);
            }

            // check it the values are null
            if (!isset($coinValueRes[0]->id)) {
                $coin_id = 99999999;
            } else {
                $coin_id = intval($coinValueRes[0]->id);
            }

            // check it the values are null
            if (!isset($whatToMineRes[0]->id)) {
                $whatToMine_id = 99999999;
            } else {
                $whatToMine_id = intval($whatToMineRes[0]->id);
            }

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
            $wattofGPUs = floatval(get_field('watt_estimate', $postId));
            $energyCosts = 0.1;

            // calculate revenue
            $userRatio = ($hashRate * $numberOfEquipment) / $networkHashRate;
            $blocksPerMinute = 60 / $blockTime;
            $rewardPerMinute = $blocksPerMinute * $blockReward;

            $revenuePerDayInUSD = $userRatio * $rewardPerMinute * 60 * 24 * $selectedCoinPriceInUSD;

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
