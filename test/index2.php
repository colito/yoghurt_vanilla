<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../libs/model/apicaller_test.php');

$apicaller = new ApiCaller('http://localhost/emp/myapi/');

$params = array();
$params['name'] = 'Hendrik';
$params['surname'] = 'Kekana';
$params['number'] = '082 459 7895';

$api_call = json_decode($apicaller->curl_post_async($params));
var_dump($api_call);

//var_dump(json_decode($api_call->extra));
//echo $api_call->data;
//echo '<br><br>';
//echo $api_call->extra;
