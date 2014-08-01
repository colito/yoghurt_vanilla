<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../libs/model/apicaller_test.php');

$apicaller = new ApiCaller('http://localhost/emp/myapi/');

$params = array();
$params['name'] = 'Hendrik';
$params['surname'] = 'Kekana';
$params['number'] = '082 459 7895';

//$api_call = json_decode($apicaller->curl_post_async2($params));
$api_call = $apicaller->curl_post_async3($params);
//$api_call = json_decode($api_call);

//var_dump($api_call);
//var_dump($api_call['result']);
$api_result = $api_call['result'];
$api_result2 = json_decode($api_result);
$api_result_extra = json_decode($api_result2->extra);

$extra_name = $api_result_extra->name;

var_dump($api_result_extra);

var_dump($extra_name);

//var_dump($api_call->data);
//var_dump($api_call->success);