<?php
// require __FILE__  . "../vendor/autoload.php";
// TODO
// include __FILE__ . "src/Spintax/Spintax.php";

// error_reporting(E_ALL ^ E_NOTICE);

// require_once 'vendor/autoload.php';

use eftec\bladeone;
use DaveChild\TextStatistics as TS;

class SinglePostContent
{

    public function __construct()
    {
        //...
    }

    public function main($postID)
    {

//        include_once( AutoContentCreator_DIR . 'includes\views' );
        // $views = __DIR__ . '\views';
        // $cache = __DIR__ . '\cache';
        $views = AutoContentCreator_DIR . 'includes/views';
        $cache = AutoContentCreator_DIR . 'includes/cache';

        define("BLADEONE_MODE", 1); // (optional) 1=forced (test),2=run fast (production), 0=automatic, default value.
        $blade = new bladeone\BladeOne($views, $cache);

        $textStatistics = new TS\TextStatistics;
        $conn = "";
        global $wpdb;

        if (!empty($postID)) {
            // return only the post with the id
            $posts = "SELECT  wp_posts.* 
                FROM wp_posts  
                LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1  AND ( 
                     wp_term_relationships.term_taxonomy_id = 43 OR wp_term_relationships.term_taxonomy_id = 55 
                ) AND wp_posts.post_type = 'computer-hardware' AND (wp_posts.post_status = 'publish') AND ID = " . $postID . "
                GROUP BY wp_posts.ID 
                ORDER BY wp_posts.post_date";
        } else {
            // gets all posts
            $posts = "SELECT  wp_posts.* 
                FROM wp_posts  
                LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1  AND ( 
                     wp_term_relationships.term_taxonomy_id = 43 OR wp_term_relationships.term_taxonomy_id = 55 
                ) AND wp_posts.post_type = 'computer-hardware' AND (wp_posts.post_status = 'publish') 
                GROUP BY wp_posts.ID 
                ORDER BY wp_posts.post_date";
        }


        $result = $wpdb->get_results($posts);
        /**
         * Create FINAL RESULT array
         */
        $data = array();
        $i = 0;
        if (count($result) > 0) {
            // output data of each row
            foreach ($result as $key => $row) {
                // array_push($data, $row);

                // TODO remove after finish
                // if ($row->ID == 605) {

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
                $daysUntilProfitable = SinglePostContent::getDaysUntilProfitable($row->ID, $currentPrice)[0]["daysUntilProfitable"];
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
                    'miningModelsByCompany' => $miningModelsByCompany,
                    'dailyProfitOfMiner' => number_format((float)$averageMiningProfit30, 5, '.', ''),
                    'numberOfMiningModels' => $miningModelsByCompany,
                    'currentPrice' => number_format((float)$currentPrice),
                    'dayToday' => date('F jS, Y', strtotime("now")),
                    'monthToday' => date('F, Y', strtotime("now")),
                    'comparisonTableArray' => $comparisonTableArray,
                    'daysUntilProfitable' => number_format((float)$daysUntilProfitable, 0, '.', ''),
                    'numberOfMiningModels' => (mt_rand(0, 10) < 5 ? count($numberOfMiningModels) : lcfirst(SinglePostContent::number_to_word(count($numberOfMiningModels)))),
                ));

                // TODO remove after finish
                // break;
                // }
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
        $spintax = new Spintax();
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

            $spintaxOutput = "";
            $spintaxOutput .= $spintax->process($output);
            // $spintaxOutput .= "\n ######################### \n";

            $spintaxOutput = preg_replace('/\s{1,}/', ' ', $spintaxOutput); // replace 1 or more spaces

            SinglePostContent::insertContentIntoWordpress($data[$key]["postId"], $spintaxOutput);

            $finalOutput .= $spintaxOutput;

            // echo $finalOutput;
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

    public static function insertContentIntoWordpress($id, $content)
    {
        if (empty($id) || empty($content)) {
            return;
        }

        $post = array(
            'ID' => $id,
            'post_content' => $content,
        );

        // Update the post into the database
        wp_update_post($post);
    }

    public static function createMetaQuery($postID, $metaValue)
    {
        $str = "SELECT * FROM wp_postmeta WHERE post_id = " . $postID . " and meta_key = '" . $metaValue . "' LIMIT 1;";
        return $str;
    }

    public static function getMetaQuery($postID, $metaValue)
    {
        global $wpdb;
        $str = "SELECT * FROM wp_postmeta WHERE post_id = " . $postID . " and meta_key = '" . $metaValue . "' LIMIT 1;";
        $res = $wpdb->get_results($str);

        return $res;
    }

    public static function createPostIDQuery($postID)
    {
        $str = "SELECT t.* 
    FROM `wp_terms` t
    JOIN `wp_term_taxonomy` tt ON(t.`term_id` = tt.`term_id`)
    JOIN `wp_term_relationships` ttr ON(ttr.`term_taxonomy_id` = tt.`term_taxonomy_id`)
    WHERE tt.`taxonomy` = 'category'
    AND ttr.`object_id` = " . $postID;

        return $str;
    }

    public static function getCoinList($postID)
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
        foreach ($res as $key => $ro) {
            $dat .= $ro->post_title . ", ";
        }
        $dat = preg_replace("/,\s$/", '', $dat); //remove last , from string
        return $dat;
    }

    public static function getMiningCosts($postID)
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT avg(daily_costs) as daily_costs FROM wp_miningprofitability WHERE created_at >= NOW() - INTERVAL 30 DAY AND post_id = " . $postID . ";")[0]->daily_costs;
        return $res;
    }

    public static function getMiningProfitability($postID)
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT avg(daily_grossProfit) as daily_grossProfit FROM wp_miningprofitability WHERE created_at >= NOW() - INTERVAL 30 DAY AND post_id = " . $postID . ";")[0]->daily_grossProfit;
        return $res;
    }

    public static function getminingModelsByCompany($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = "";
        foreach ($res as $key => $ro) {
            $dat .= $ro->post_title . ", ";
        }
        $dat = preg_replace("/,\s$/", '', $dat); //remove last , from string
        $dat = str_replace($manufacturer, '', $dat); //remove last , from string
        $dat = trim($dat);
        return $dat;
    }

    public static function getArrayOFMiningModelsByCompany($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = [];
        foreach ($res as $key => $ro) {
            array_push($dat, array(
                'ID' => $ro->ID,
                'post_title' => $ro->post_title,
                'meta_value' => $ro->meta_value,
            ));
        }
        return $dat;
    }

    public static function getComparisonTable($postID, $manufacturer)
    {
        global $wpdb;

        $query = "SELECT P.ID, P.post_title, P.post_content, P.post_author, meta_value, P.guid
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID
WHERE P.post_type = 'computer-hardware' and P.post_status = 'publish' and meta_value = '" . $manufacturer . "' 
ORDER BY P.post_date DESC";

        $res = $wpdb->get_results($query);
        $dat = array();
        foreach ($res as $key => $ro) {

            $watt = SinglePostContent::getMetaQuery($ro->ID, 'watt_estimate')[0];
            $hashRate = SinglePostContent::getMetaQuery($ro->ID, 'hash_rate')[0];
            $amzLink = SinglePostContent::getAmazon($ro->ID, 'url');
            $image = SinglePostContent::getAmazon($ro->ID, 'img');

            array_push($dat, array(
                'id' => $ro->ID,
                'model' => $ro->post_title,
                'image' => $image,
                'watt' => number_format($watt->meta_value),
                'hashRate' => number_format((float)$hashRate->meta_value),
                'link' => get_permalink($ro->ID),
                'amzLink' => $amzLink,
            ));
        }

        return $dat;
    }

    public static function getDaysUntilProfitable($postID, $price)
    {
        global $wpdb;

        $query = "SELECT *
FROM wp_miningprofitability
WHERE post_id = " . $postID . "  
ORDER BY wp_miningprofitability.created_at DESC
LIMIT 1";

        $res = $wpdb->get_results($query);
        $dat = array();
        foreach ($res as $key => $ro) {

            $daily_netProfit = $ro->daily_netProfit;
            $daily_grossProfit = $ro->daily_grossProfit;
            $daily_costs = $ro->daily_costs;
            $currentPrice = $price;

            $daysUntilProfitable = $currentPrice / $daily_netProfit;

            if ($daysUntilProfitable < 0) {
                $daysUntilProfitable = 0;
            }

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

    public static function getAmazon($postID, $tag)
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

    public static function is_serialized($data)
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

    public static function number_to_word($num = '')
    {
        $num = ( string )(( int )$num);

        if (( int )($num) && ctype_digit($num)) {
            $words = array();

            $num = str_replace(array(',', ' '), '', trim($num));

            $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
                'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
                'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');

            $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
                'seventy', 'eighty', 'ninety', 'hundred');

            $list3 = array('', 'thousand', 'million', 'billion', 'trillion',
                'quadrillion', 'quintillion', 'sextillion', 'septillion',
                'octillion', 'nonillion', 'decillion', 'undecillion',
                'duodecillion', 'tredecillion', 'quattuordecillion',
                'quindecillion', 'sexdecillion', 'septendecillion',
                'octodecillion', 'novemdecillion', 'vigintillion');

            $num_length = strlen($num);
            $levels = ( int )(($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);

            foreach ($num_levels as $num_part) {
                $levels--;
                $hundreds = ( int )($num_part / 100);
                $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
                $tens = ( int )($num_part % 100);
                $singles = '';

                if ($tens < 20) {
                    $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                } else {
                    $tens = ( int )($tens / 10);
                    $tens = ' ' . $list2[$tens] . ' ';
                    $singles = ( int )($num_part % 10);
                    $singles = ' ' . $list1[$singles] . ' ';
                }
                $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
            }

            $commas = count($words);

            if ($commas > 1) {
                $commas = $commas - 1;
            }

            $words = implode(', ', $words);

            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words = trim(str_replace(' ,', ',', SinglePostContent::trim_all(ucwords($words))), ', ');
            if ($commas) {
                $words = SinglePostContent::str_replace_last(',', ' and', $words);
            }

            return $words;
        } else if (!(( int )$num)) {
            return 'Zero';
        }
        return '';
    }

    public static function trim_all($str, $what = NULL, $with = ' ')
    {
        if ($what === NULL) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim(preg_replace("/[" . $what . "]+/", $with, $str), $what);
    }

    public static function str_replace_last($search, $replace, $str)
    {
        if (($pos = strrpos($str, $search)) !== false) {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }
}
