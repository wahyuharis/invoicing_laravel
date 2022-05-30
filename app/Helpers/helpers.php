<?php
function print_r2($var)
{
    echo "<pre>";
    print_r($var);
    die();
}

function var_dump2($var)
{
    echo "<pre>";
    var_dump($var);
    die();
}
function header_json()
{
    header('Content-Type: application/json');
}

function header_text()
{
    header("Content-Type: text/plain");
}

function header_cross_domain()
{
    header("Access-Control-Allow-Origin: *");
}

function floatval2($param)
{
    $val = str_replace(',', '', $param);
    return floatval($val);
}
