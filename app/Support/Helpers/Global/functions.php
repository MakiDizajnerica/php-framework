<?php

use Core\Config;
use Core\Application;

function config($key)
{
    return Config::get($key);
}

function request()
{
    return Application::container()->getService('request');
}

function response()
{
    return Application::container()->getService('response');
}

function hasher()
{
    return Application::container()->getService('hasher');
}

function cookie()
{
    return Application::container()->getService('cookie');
}

function session()
{
    return Application::container()->getService('session');
}

function location($key, $default = 'unknown')
{
    return Application::container()->getService('location')->get($key, $default);
}






function dd($value, $die = true)
{
    if (!is_array($value)) {
        $value = (array) $value;
    }
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    if ($die) die;
}

function memoryUsage()
{
    $mem_usage = memory_get_usage();
    $mem_peak = memory_get_peak_usage();
    echo '<br><br>The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
    echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
}
?>