<?php
// require __FILE__  . "../vendor/autoload.php";
// TODO
// include __FILE__ . "src/Spintax/Spintax.php";

// error_reporting(E_ALL ^ E_NOTICE);

use eftec\bladeone;
use DaveChild\TextStatistics as TS;
use Noodlehaus\Config;
// use Spintax;
use Noodlehaus\Exception;

class SinglePostContent
{

    public function __construct()
    {
        //...
    }

    public function main()
    {
        $views = __DIR__ . '\views';
        $cache = __DIR__ . '\cache';
        define("BLADEONE_MODE", 1); // (optional) 1=forced (test),2=run fast (production), 0=automatic, default value.
        $blade = new bladeone\BladeOne($views, $cache);

        $textStatistics = new TS\TextStatistics;
        $conn = "";
        global $wpdb;

        $posts = "SELECT  wp_posts.* 
        FROM wp_posts  
        LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1  AND ( 
             wp_term_relationships.term_taxonomy_id = 43 OR wp_term_relationships.term_taxonomy_id = 55 
        ) AND wp_posts.post_type = 'computer-hardware' AND (wp_posts.post_status = 'publish') 
        GROUP BY wp_posts.ID 
        ORDER BY wp_posts.post_date";

        $result = $wpdb->get_results($posts);
        /**
         * Create FINAL RESULT array
         */
        $data = array();
        $i = 0;
        if (count($result) > 0) {
            // output data of each row
            foreach($result as $key => $row) {
                // array_push($data, $row);

                // TODO remove after finish
                if ($row->ID == 4204) {

                    echo "Get variables: " . $row->ID . "\n";

                    $manufacturer = $wpdb->get_results(SinglePostContent::createMetaQuery($row->ID, 'manufacturer'))[0]->meta_value;
                    $algorithm = $wpdb->get_results(SinglePostContent::createMetaQuery($row->ID, 'algorithm'))[0]->meta_value;
                    $hashRate = $wpdb->get_results(SinglePostContent::createMetaQuery($row->ID, 'hash_rate'))[0]->meta_value;
                    $powerConsumption = $wpdb->get_results(SinglePostContent::createMetaQuery($row->ID, 'watt_estimate'))[0]->meta_value;
                    $modelName = $row->post_title;
                    $category = $wpdb->get_results(SinglePostContent::createPostIDQuery($row->ID))[0]->name;
                    $coins = SinglePostContent::getCoinList($row->ID);
                    $averageMiningCosts30 = SinglePostContent::getMiningCosts($row->ID);
                    $averageMiningProfit30 = SinglePostContent::getMiningProfitability($row->ID);
                    $miningModelsByCompany = SinglePostContent::getminingModelsByCompany($row->ID, $manufacturer);
                    $currentPrice = SinglePostContent::getAmazon($row->ID, 'price');
                    $comparisonTableArray = SinglePostContent::getComparisonTable($row->ID, $manufacturer);
                    $daysUntilProfitable = SinglePostContent::getDaysUntilProfitable($row->ID, $currentPrice)[0]->daysUntilProfitable;
                    $numberOfMiningModels = SinglePostContent::getArrayOFMiningModelsByCompany($row->ID, $manufacturer);

                    $i++;
                    array_push($data, array(
                        'postId' => $row->ID,
                        'company' => $manufacturer,
                        'category' => $category,
                        'algorithm' => $algorithm,
                        'hashRate' => number_format($hashRate),
                        'powerConsumption' => number_format($powerConsumption),
                        'model' => $modelName,
                        'modelWithoutManufacturer' => trim(str_replace($manufacturer, "", $modelName)),
                        'listOfAlgorithms' => $algorithm,
                        'listOfCryptocurrencies' => $coins,
                        'miningCosts' => number_format((float)$averageMiningCosts30, 2, '.', ''),
                        'miningModel' => $miningModelsByCompany,
                        'dailyProfitOfMiner' => number_format((float)$averageMiningProfit30, 5, '.', ''),
                        'numberOfMiningModels' => $miningModelsByCompany,
                        'currentPrice' => number_format((float)$currentPrice),
                        'dayToday' => date('F jS, Y', strtotime("now")),
                        'monthToday' => date('F, Y', strtotime("now")),
                        'comparisonTableArray' => $comparisonTableArray,
                        'daysUntilProfitable' => number_format((float)$daysUntilProfitable[0]["daysUntilProfitable"], 0, '.', ''),
                        'numberOfMiningModels' => count($numberOfMiningModels),
                    ));

                    // TODO remove after finish
                    break;
                }
                // TODO also remove this!
            }
        } else {
            echo "0 results";
        }

        echo "Fill template \n";

        /**
         * Fill template
         **/
        $finalOutput = '';
        //$spintax = new Spintax();
        foreach ($data as $key => $value) {
            // print_r($data[$key]);

            $output = '';

            $output = $blade->run("singlePost", $data[$key]);
            // create spintax
            $output = str_replace("</synonym>", "}", $output);
            $output = str_replace("\">", "|", $output);
            $output = str_replace("<synonym words=\"", "{", $output);

            // replace tags
            $output = str_replace("<insertdata>", "", $output);
            $output = str_replace("</insertdata>", "", $output);
            $output = str_replace("<ifelse>", "", $output);
            $output = str_replace("</ifelse>", "", $output);

            // &nbsp;
            $output = str_replace("&nbsp;", " ", $output);
            $output = str_replace("  +", " ", $output); // replace 1 or more spaces
/*
 * TODO
            $spintaxOutput = "";
            $spintaxOutput .= $spintax->process($output);
            $spintaxOutput .= "\n ######################### \n";

            $spintaxOutput .= preg_replace('/\s{1,}/', ' ', $spintaxOutput); // replace 1 or more spaces

            $finalOutput .= $spintaxOutput;
*/
            echo $finalOutput;
            // echo 'Flesch-Kincaid Reading Ease: ' . $textStatistics->fleschKincaidReadingEase($output) . "\n";
        }

        file_put_contents("./SINGLE_CONTENT_OUTPUT.html", $finalOutput);

    }

    /**
     * ***********************************************
     * ***********************************************
     * ***********************************************
     * *******************Functions*******************
     * ***********************************************
     * ***********************************************
     * ***********************************************
     **/

    public function createMetaQuery($postID, $metaValue)
    {
        $str = "SELECT * FROM wp_postmeta WHERE post_id = " . $postID . " and meta_key = '" . $metaValue . "' LIMIT 1;";
        return $str;
    }

    public function getMetaQuery($postID, $metaValue)
    {
        global $wpdb;
        $str = "SELECT * FROM wp_postmeta WHERE post_id = " . $postID . " and meta_key = '" . $metaValue . "' LIMIT 1;";
        $res = $wpdb->get_results($str);

        return $res;
    }

    public function createPostIDQuery($postID)
    {
        $str = "SELECT t.* 
    FROM `wp_terms` t
    JOIN `wp_term_taxonomy` tt ON(t.`term_id` = tt.`term_id`)
    JOIN `wp_term_relationships` ttr ON(ttr.`term_taxonomy_id` = tt.`term_taxonomy_id`)
    WHERE tt.`taxonomy` = 'category'
    AND ttr.`object_id` = " . $postID;

        return $str;
    }

    public function getCoinList($postID)
    {
        global $wpdb;
        $coins = $wpdb->get_results(SinglePostContent::createMetaQuery($postID, 'related_coins'))[0]->meta_value;

        if (empty($coins)) {
            return "";
        }

        if (is_serialized($coins)) {
            $coins = unserialize($coins);
            $para = "";
            foreach ($coins as $key => $value) {
                $para .= $coins[$key] . ", ";
            }
            $para = preg_replace("/,\s$/", '', $para); //remove last , from string
        } else {
            $para = $coins;
        }

        $str = "SELECT * FROM `wp_posts` WHERE ID IN (" . $para . ")";

        $res = $wpdb->get_results($str);
        $dat = "";
        foreach($res as $key => $ro) {
            $dat .= $ro->post_title . ", ";
        }
        $dat = preg_replace("/,\s$/", '', $dat); //remove last , from string
        return $dat;
    }

    public function getMiningCosts($postID)
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT avg(daily_costs) as daily_costs FROM wp_miningprofitability WHERE created_at >= NOW() - INTERVAL 30 DAY AND post_id = " . $postID . ";")[0]->daily_costs;
        return $res;
    }

    public function getMiningProfitability($postID)
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT avg(daily_grossProfit) as daily_grossProfit FROM wp_miningprofitability WHERE created_at >= NOW() - INTERVAL 30 DAY AND post_id = " . $postID . ";")[0]->daily_grossProfit;
        return $res;
    }

    public function getminingModelsByCompany($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = "";
        foreach($res as $key => $ro) {
            $dat .= $ro->post_title . ", ";
        }
        $dat = preg_replace("/,\s$/", '', $dat); //remove last , from string
        $dat = str_replace($manufacturer, '', $dat); //remove last , from string
        $dat = trim($dat);
        return $dat;
    }

    public function getArrayOFMiningModelsByCompany($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = [];
        foreach($res as $key => $ro) {
            array_push($dat, array(
                'ID' => $ro->ID,
                'post_title' => $ro->post_title,
                'meta_value' => $ro->meta_value,
            ));
        }
        return $dat;
    }

    public function getComparisonTable($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value, P.guid
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = array();
        foreach($res as $key => $ro) {

            $watt = SinglePostContent::getMetaQuery($ro->ID, 'watt_estimate')[0];
            $hashRate = SinglePostContent::getMetaQuery($ro->ID, 'hash_rate')[0];
            $amzLink = SinglePostContent::getAmazon($ro->ID, 'url')[0];
            $image = SinglePostContent::getAmazon($ro->ID, 'img');

            array_push($dat, array(
                'model' => $ro->post_title,
                'image' => $image,
                'watt' => $watt->meta_value,
                'hashRate' => $hashRate->meta_value,
                'link' => $ro->guid,
                'amzLink' => $amzLink,
            ));
        }

        return $dat;
    }

    public function getDaysUntilProfitable($postID, $price)
    {
        global $wpdb;

        $query = "SELECT *
FROM wp_miningprofitability
WHERE post_id = " . $postID . "  
ORDER BY wp_miningprofitability.created_at DESC
LIMIT 1";

        $res = $wpdb->get_results($query);
        $dat = array();
        foreach($res as $key => $ro) {

            $daily_netProfit = $ro->daily_netProfit;
            $daily_grossProfit = $ro->daily_grossProfit;
            $daily_costs = $ro->daily_costs;
            $currentPrice = $price;

            $daysUntilProfitable = $currentPrice / $daily_netProfit;

            array_push($dat, array(
                'daily_netProfit' => $daily_netProfit,
                'daily_grossProfit' => $daily_grossProfit,
                'daily_costs' => $daily_costs,
                'currentPrice' => $currentPrice,
                'daysUntilProfitable' => $daysUntilProfitable,
            ));
        }

        return $dat;
    }

    public function getAmazon($postID, $tag)
    {
        global $wpdb;

        $wpdb->query("set names 'utf8';");
        $arr = $wpdb->get_results(SinglePostContent::createMetaQuery($postID, '_cegg_data_Amazon'))[0]->meta_value;
        // TODO The problem are the special characters! That's why unserialize is not working correctly!!!
        // $arr = urldecode($arr);
        // $arr = unserialize($arr);
        // print_r($arr);
        // file_put_contents("./lolonator.txt", $arr);

        if (empty($arr)) {
            return "";
        }

        if (!unserialize($arr)) {
            $arr = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $arr);
            $arr = unserialize($arr);
            $arr = reset($arr);
            return $arr[$tag];
        } elseif (is_serialized($arr)) {
            $arr = unserialize($arr);
            $arr = reset($arr);
            return $arr[$tag];
        } else {
            // $dat = $tag . " not available.";
            $dat = null;
        }
        return $dat;
    }

    public function is_serialized($data)
    {
        // if it isn't a string, it isn't serialized
        if (!is_string($data))
            return false;
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }
        return false;
    }
}
