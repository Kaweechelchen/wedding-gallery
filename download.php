<?php
    require_once __DIR__.'/init.php';

    if (empty($_GET['hash']))
        exit;

    $db = getDb();
    $query = $db->prepare('SELECT DISTINCT photo FROM photo2person AS p2p INNER JOIN person2hash p2h ON p2p.person = p2h.person WHERE hash = :hash ORDER BY photo');
    $query->execute(array(
        'hash' => $_GET['hash']
    ));
    $photos = $query->fetchAll();

    if (empty($photos))
        exit;
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <style>
        html, body {
            margin: 0px;
            padding: 0px;
            background-color: #000;
        }
        body {
            margin: 10px 0 0 10px;
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 0 10px 10px 0;
            text-decoration: none;
        }
        a img {
            border: 0;
        }
        @media (max-width: 600px) {
            body {
                margin: 0px 10px 10px 10px;
            }
            a {
                width: 100%;
                margin: 10px 0 0 0;
            }
            a img {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    foreach ($photos as $photo)
    {
        echo '<a href="'.Config::BASE_URI.'photos/'.$photo['photo'].'" download="'.$photo['photo'].'"><img src="'.Config::BASE_URI.'photos/thumb/'.$photo['photo'].'" /></a>';
    }
    ?>
</body>
</html>
