<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if (defined('RH_GRANDCHILD_DIR')) {
    include RH_GRANDCHILD_DIR . 'rh-grandchild-func.php';
}

require get_theme_file_path('/inc/computerHardwareRoute.php');
require get_theme_file_path('/inc/miningRigsRoute.php');
require get_theme_file_path('/inc/miningProfRoute.php');

//require_once 'vendor/autoload.php';
if (file_exists(get_theme_file_path() . '/vendor/autoload.php')) {
    require get_theme_file_path() . '/vendor/autoload.php';
}

add_action('wp_enqueue_scripts', 'enqueue_parent_theme_style');
function enqueue_parent_theme_style()
{
    if (!defined('RH_MAIN_THEME_VERSION')) {
        define('RH_MAIN_THEME_VERSION', '7.2.1');
    }

    $parentStyle = 'parent-style';

    wp_enqueue_style($parentStyle, get_template_directory_uri() . '/style.css');

    if (is_page('Rig Builder')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('sweetalert', get_stylesheet_directory_uri() . '/css/sweetalert.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('sweetalert', get_theme_file_uri('/js/libs/sweetalert.min.js'), null, '1.0', true);
        wp_enqueue_script('main-mining-rig-js', get_theme_file_uri('/js/scripts-bundled.js'), null, '1.0', true);

        wp_localize_script('main-mining-rig-js', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }

    if (is_singular('computer-hardware')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('morris', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('popper-1.12.9', get_theme_file_uri('/js/libs/popper.min.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);
        wp_enqueue_script('raphael', get_theme_file_uri('/js/libs/raphael-min.js'), null, '1.0', true);
        wp_enqueue_script('morris', get_theme_file_uri('/js/libs/morris.min.js'), null, '1.0', true);
        wp_enqueue_script('computerHardwareChart', get_theme_file_uri('/js/charts/ComputerHardwareTemplate.js'), null, '1.0', true);

        wp_localize_script('computerHardwareChart', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }

    if (is_page('Hardware Overview')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-bootstrap-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));

        //js
        //wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('dataTables', get_theme_file_uri('/js/libs/jquery.dataTables.min.js'), null, '1.0', true);
        wp_enqueue_script('hardware-overview', get_theme_file_uri('/js/overview/HardwareOverview.js'), null, '1.0', true);

        wp_localize_script('hardware-overview', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }

    if (is_page('Mining Rigs')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('dataTables', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css', array($parentStyle));
        wp_enqueue_style('dataTables-1.10.16', get_stylesheet_directory_uri() . '/css/dataTables.bootstrap4.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));

        //js
        wp_enqueue_script('font-awesome', get_theme_file_uri('/js/libs/fontawesome-all.js'), null, '1.0', true);
        wp_enqueue_script('bootstrap-4.0.0', get_theme_file_uri('/js/libs/bootstrap.min.js'), null, '1.0', true);
        wp_enqueue_script('jquery', get_theme_file_uri('/js/libs/jquery.min.js'), null, '1.0', true);
        wp_enqueue_script('dataTables', get_theme_file_uri('/js/libs/jquery.dataTables.min.js'), null, '1.0', true);
        wp_enqueue_script('mining-overview', get_theme_file_uri('/js/overview/MiningRigs.js'), null, '1.0', true);

        wp_localize_script('mining-overview', 'miningRigData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }

    if (is_page('Mining List')) {

        //css
        wp_enqueue_style('bootstrap-4.0.0', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array($parentStyle));
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array($parentStyle));

        //js
        // ..
    }
}

//////////////////////////////////////////////////////////////////
// Translation
//////////////////////////////////////////////////////////////////
add_action('after_setup_theme', 'rehubchild_lang_setup');
function rehubchild_lang_setup()
{
    load_child_theme_textdomain('rehubchild', get_stylesheet_directory() . '/lang');
}

//////////////////////////////////////////////////////////////////
// Advanced Custom Fields Filters
//////////////////////////////////////////////////////////////////
add_filter('acf/load_field/name=related_coins', 'register_algorithm_value_filter');
function register_algorithm_value_filter($field)
{
    if (!isset($field['filters'])) {
        return $field;
    }

    $field['filters'][] = 'algorithm_value';

    return $field;
}

add_action('acf/create_field/type=relationship', 'create_algorithm_value_filter_menu');
function create_algorithm_value_filter_menu($field)
{
    if ('acf-field-related_coins' !== $field['id']) {
        return;
    }

    global $wpdb;

    $choices = [
        'any' => 'Filter by Algorithm',
    ];

    $values = $wpdb->get_col(
        "SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm " .
        "INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id " .
        "WHERE pm.meta_key = 'coin_algorithm' AND p.post_type = 'coin' " .
        "ORDER BY pm.meta_value ASC"
    );
    foreach ($values as $value) {
        $choices[$value] = $value;
    }
    unset($values);

    create_field([
        'type' => 'select',
        'name' => 'algorithm_value',
        // The select-algorithm_value class is required by the JS script.
        // You should also keep the hide-if-js class.
        'class' => 'select-algorithm_value hide-if-js',
        'value' => '',
        'choices' => $choices,
    ]);
}

add_action('admin_print_footer_scripts', 'print_algorithm_value_filter_script', 11);
function print_algorithm_value_filter_script()
{
    $screen = get_current_screen();

    // Add the script only on the wp-admin/post.php page, and only if the post
    // being edited is of the "coin" or "computer-hardware" type.
    if ('computer-hardware' === $screen->id || 'coin' === $screen->id):
        ?>
        <script>
            jQuery(function ($) {
                var field_name = 'algorithm_value',
                    $div = jQuery('#acf-related_coins:has(.has-' + field_name + ')');

                // Moves the Algorithm filter menu to below the Post Type filter menu.
                $div.find('.select-' + field_name).insertAfter(
                    $div.find('.select-post_type')
                    // And this does the AJAX filtering..
                ).on('change', function () {
                    $div.find('.has-' + field_name)
                        .attr('data-' + field_name, this.value);

                    $div.find('.select-post_type').trigger('change'); // Run the AJAX.
                });

                // Shows the Algorithm filter menu only if the chosen Post Type is 'coin'.
                // Keep the event's namespace! (but you may use name other than post_type).
                $div.find('.select-post_type').on('change.post_type', function () {
                    if ('coin' === this.value) {
                        $div.find('.select-' + field_name).show();
                    } else {
                        $div.find('.select-' + field_name).hide();
                    }
                });
            });
        </script>
    <?php
    endif;
}

add_action('acf/fields/relationship/query/name=related_coins', 'query_posts_by_algorithm_value');
function query_posts_by_algorithm_value($options)
{
    // Filters only if the Post Type is exactly 'coin'.
    if ('coin' !== $options['post_type']) {
        return $options;
    }

    if (isset($options['algorithm_value'])) {
        $value = $options['algorithm_value'];

        if ($value && 'any' !== $value) {
            if (!isset($options['meta_query'])) {
                $options['meta_query'] = [];
            }

            $options['meta_query'][] = [
                'key' => 'coin_algorithm',
                'value' => $value,
                'compare' => '=',
            ];
        }

        // Don't pass to WP_Query.
        unset($options['algorithm_value']);
    }

    return $options;
}

//////////////////////////////////////////////////////////////////
// MAILSTER - Custom Tags
//////////////////////////////////////////////////////////////////

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string
 */
function trim_text($input, $length, $ellipses = true, $strip_html = true)
{
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

/**
 * Get profitable miners
 */
if (function_exists('mailster_add_tag')) {
    function mytag_function($option, $fallback, $campaignID = NULL, $subscriberID = NULL)
    {

        global $wpdb;

        $query = $wpdb->get_results(
            "SELECT * 
    FROM wp_posts p
    INNER JOIN wp_miningprofitability m ON m.post_id = p.ID
    LEFT JOIN wp_term_relationships rel ON rel.object_id = p.ID
    LEFT JOIN wp_term_taxonomy tax ON (tax.term_taxonomy_id = rel.term_taxonomy_id AND tax.taxonomy = 'category')
    LEFT JOIN wp_terms t ON (t.term_id = tax.term_id AND t.name != 'uncategorized')
    WHERE
        m.created_at =(
        SELECT MAX(pp2.created_at)
        FROM wp_miningprofitability pp2
        WHERE pp2.post_id = m.post_id
    )
    ORDER BY m.daily_netProfit
    DESC LIMIT 5;"
        );

        $table = "
        <table class='center'>
        <thead>
          <th>#</th>
          <th>Model</th>
          <th>Type</th>
          <th>Profitability</th>
        </thead>";

        //$row = "";
        $i = 0;
        foreach ($query as $key => $value) {
            $i++;
            $imgUrl = get_the_post_thumbnail_url($value->ID, 'thumbnail');

            $table .= "<tr class='strippedTag'>
            <td class='strippedTag'>" . $i . "</td>
            <td class='strippedTag'><img src='" . $imgUrl . "' height='42' width='42'/> 
            <a href= '" . $value->guid . "' target='_blank'>
            " . trim_text($value->post_title, 20, true, false) . "
             </a></td>
            <td align='middle' class='strippedTag'>" . $value->name . "</td>
            <td align='right' class='strippedTag'>$" . number_format($value->daily_netProfit, 2) . "/day</td>
          </tr>";
        }
        $table .= "</table>";

        return $table;
    }

    mailster_add_tag('mytag', 'mytag_function');

    /**
     * Get weekly posts
     **/
    function weeklyPosts_function($option, $fallback, $campaignID = NULL, $subscriberID = NULL)
    {

        try {
            $client = new \Google_Client();
        } catch (Exception $e) {
            print_r($e);
        }

        // $jsonAuth = getenv('credentials.json');
        // $client->setAuthConfig(json_decode($jsonAuth, true));
        // $client->setAuthConfig(get_theme_file_path() . '/credentials.json');
        // $jsonAuth = getenv(get_theme_file_path() . '/credentials.json');
        // $client->setAuthConfig(json_decode($jsonAuth, true));

        // $sheets = new \Google_Service_Sheets($client);
        $client = getClient();
        $sheets = new Google_Service_Sheets($client);

        $data = [];
        $currentRow = 2;
        $spreadsheetId = getenv('1773823838');
        $range = 'A2:H';
        $rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);
        if (isset($rows['values'])) {
            foreach ($rows['values'] as $row) {

                if (empty($row[0])) {
                    break;
                }
                $data[] = [
                    'col-a' => $row[0],
                    'col-b' => $row[1],
                    'col-c' => $row[2],
                    'col-d' => $row[3],
                    'col-e' => $row[4],
                    'col-f' => $row[5],
                    'col-g' => $row[6],
                    'col-h' => $row[7],
                ];

                $updateRange = 'I' . $currentRow;
                $updateBody = new \Google_Service_Sheets_ValueRange([
                    'range' => $updateRange,
                    'majorDimension' => 'ROWS',
                    'values' => ['values' => date('c')],
                ]);
                $sheets->spreadsheets_values->update(
                    $spreadsheetId,
                    $updateRange,
                    $updateBody,
                    ['valueInputOption' => 'USER_ENTERED']
                );
                $currentRow++;
            }
        }

//        print_r($data);

        $test = "lolo lolo lolonator11112234!";
        return $test;
    }

    mailster_add_tag('myWeeklyPosts', 'weeklyPosts_function');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    // $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = get_theme_file_path() . '/credentials.json';
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        try {
            $client->setAccessToken($accessToken);
        } catch (Exception $e) {
            print_r($e);
        }
    } else {
        return "File not found.";
    }

    // Refresh the token if it's expired.
    /*
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }*/
    return $client;
}

add_action('cron_request', 'so_add_cron_xdebug_cookie', 10, 2);


/**
 * Allow debugging of wp_cron jobs
 *
 * @param array $cron_request_array
 * @param string $doing_wp_cron
 *
 * @return array $cron_request_array with the current XDEBUG_SESSION cookie added if set
 */
function
so_add_cron_xdebug_cookie($cron_request_array, $doing_wp_cron)
{
    if (empty ($_COOKIE['XDEBUG_SESSION'])) {
        return ($cron_request_array);
    }

    if (empty ($cron_request_array['args']['cookies'])) {
        $cron_request_array['args']['cookies'] = array();
    }
    $cron_request_array['args']['cookies']['XDEBUG_SESSION'] = $_COOKIE['XDEBUG_SESSION'];

    return ($cron_request_array);
}

/****************
 * Custom Button*
 ****************/
add_action('media_buttons', 'add_my_media_button', 99);

function add_my_media_button()
{
    echo '<a href="#" id="insert-my-media" class="button">Own content</a>';
}

