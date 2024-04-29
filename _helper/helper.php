<?php
//need config
define("X_TOKEN",$x_token);
define("API_URL",$api_url);

function get_user($app){
    $x_token = $_COOKIE[X_TOKEN];
    $url = API_URL."/user/";
    $res = $app->grab_data_auth($url,$x_token);
    return $res;
}

function my_token(){
    $x_token = $_COOKIE[X_TOKEN];
    return $x_token;
}