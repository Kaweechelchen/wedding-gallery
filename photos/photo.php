<?php

require_once __DIR__.'/../init.php';

$photo = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF']))+1);
$photoName = basename($photo);

if (empty($_SESSION['photos']) || !in_array($photoName, $_SESSION['photos']))
    login();

header('Content-Length: '.filesize(__DIR__.'/'.$photo));
header('Content-Type: image/jpg');
readfile(__DIR__.'/'.$photo);
