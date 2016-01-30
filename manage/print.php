<?php

require_once __DIR__.'/../init.php';
login();

$db = getDb();
$query = $db->prepare('SELECT GROUP_CONCAT(person ORDER BY person ASC SEPARATOR ", ") as persons, hash FROM person2hash GROUP BY hash');
$query->execute();
$hashes = $query->fetchAll();

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/print.css">
</head>
<body>
    <?php
    foreach ($hashes as $hash)
    {
        echo '<div class="box">';
        echo '<img src="'.Config::BASE_URI.'manage/generateQRCode.php?hash='.$hash['hash'].'" />';
        echo '<div class="persons">'.$hash['persons'].'</div>';
        echo '</div>';
    }
    ?>
</body>
</html>
