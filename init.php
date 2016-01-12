<?php

require __DIR__.'/config.php';
require __DIR__.'/vendor/autoload.php';
session_start();

function getDb()
{
    return new PDO('mysql:dbname='.Config::DB_NAME.';host='.Config::DB_HOST, Config::DB_USER, Config::DB_PASS);
}

function login()
{
    if (!empty($_SESSION['login']) || php_sapi_name() == 'cli')
        return true;

    if (!isset($_SERVER['PHP_AUTH_USER'])
            || $_SERVER['PHP_AUTH_USER'] != \Config::ADMIN_USER
            || $_SERVER['PHP_AUTH_PW'] != \Config::ADMIN_PASS) {
        header('WWW-Authenticate: Basic realm="Backend"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

    $_SESSION['login'] = true;
}
