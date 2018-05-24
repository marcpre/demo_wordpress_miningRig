<?php
/**
Plugin Name: Mining Rig Profitability Calculator
description: Get Data via APIs
Version: 1.0
Author: Batman
License: GPLv2 or later
Text Domain: mining-profitability-calc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'http://httpbin.org',
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

$response = $client->request('GET', 'test');