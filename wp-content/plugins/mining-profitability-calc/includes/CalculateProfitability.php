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
                        
            $algorithmRes = $wpdb->get_results( "SELECT *
                FROM wp_whatToMine_API
                WHERE id IN(
                    SELECT max(id)
                    FROM wp_whatToMine_API
                    WHERE ALGORITHM = \"" . sanitize_text_field(get_field('algorithm', $postId)) . "\" and 
                    TAG = \"" . sanitize_text_field(get_field('tag', $postId)) . "\"
                    GROUP BY id)  
                ORDER BY updated_at DESC
                LIMIT 1;" );
            
            $coinValueRes = $wpdb->get_results( "SELECT * 
                FROM {$wpdb->prefix}ticker t 
                INNER JOIN wp_coins c ON c.id = t.coin_id
                WHERE c.symbol = \"" . sanitize_text_field($get_field('symbol', $postId)) . "\"
                ORDER BY c.created_at ASC
                LIMIT 1;");
            
            var_dump($algorithmRes);
            var_dump($coinValueRes);

                
            // Get GPU & ASIC Parts
            
            // 1. get all gpu && asic parts
            // 2. calculate profitability and save to db
        }
        
    }
}
new CalculateProfitability();
