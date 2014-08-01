<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../libs/model/apicaller_test.php');

$apicaller = new ApiCaller('http://localhost/emp/myapi/');

$params = array();
$params['name'] = 'Hendrik';
$params['surname'] = 'Kekana';
$params['number'] = '082 459 7895';
$api_call = $apicaller->sendRequest($params);

var_dump($api_call);

//echo $api_call;