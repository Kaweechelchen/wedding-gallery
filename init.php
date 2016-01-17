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

function getPersons()
{
    $db = getDb();
    $query = $db->prepare('SELECT DISTINCT person FROM photo2person ORDER BY person ASC');
    $query->execute();
    return $query->fetchAll();
}

function generateQRCode($hash)
{
    $link = Config::HOST.'/hash/'.$hash;

    $imageBlob = (new Endroid\QrCode\QrCode())
        ->setText($link)
        ->setPadding(7)
        ->setErrorCorrection('high')
        ->get();

    $qrcode = new Imagick();
    $qrcode->readImageBlob($imageBlob);
    $qrcode->scaleImage(600, 600, true);

    $camera = new Imagick(__DIR__.'/camera.png');

    $qrcodeSize = $qrcode->getImageGeometry();
    $cameraSize = $camera->getImageGeometry();

    $qrcode->compositeImage($camera, Imagick::COMPOSITE_DEFAULT,
        round(($qrcodeSize['width'] - $cameraSize['width']) / 2),
        round(($qrcodeSize['height'] - $cameraSize['height']) / 2));

    $qrcode->setImageFormat('png');
    header('Content-Type: image/png');
    echo $qrcode->getImageBlob();

    $qrcode->clear();
    $qrcode->destroy();

    $camera->clear();
    $camera->destroy();
    exit;
}
