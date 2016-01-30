<?php

require_once __DIR__.'/../init.php';
login();
$db = getDb();

if (!empty($_GET['hash'])) {
    generateQRCode($_GET['hash']);
}

if (!empty($_POST['persons'])) {
    $hash = hash('sha1', uniqid(rand(), true));

    // insert new persons
    $persons = array_filter(explode(',', $_POST['persons']));
    $query = $db->prepare('INSERT INTO person2hash (hash, person) VALUES (:hash, :person)');
    foreach ($persons as $person) {
        $query->execute(array(
            'hash' => $hash,
            'person' => $person
        ));
    }

    header('Location: '.Config::HOST.Config::BASE_URI.'manage/generateQRCode.php?hash='.$hash);
    exit;
}

if (!empty($_GET['delete'])) {
    $query = $db->prepare('DELETE FROM person2hash WHERE hash = :hash');
    $query->execute(array(
        'hash' => $_GET['delete']
    ));
}

$persons = getPersons();

// get active hashes
$query = $db->prepare('SELECT GROUP_CONCAT(person ORDER BY person ASC SEPARATOR ", ") as persons, hash FROM person2hash GROUP BY hash');
$query->execute();
$hashes = $query->fetchAll();

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/selectize.css">
    <script src="../js/jquery.js"></script>
	<script src="../js/selectize.js"></script>
</head>
<body>
    <?php
    foreach ($hashes as $hash)
    {
        echo '<div><a href="?hash='.$hash['hash'].'">'.$hash['persons'].'</a> <a href="?delete='.$hash['hash'].'">delete</a></div>';
    }
    ?>
    <form method="POST">
        <input type="text" id="persons" name="persons" /><br />
        <button type="submit">Generate</button>
    </form>
    <script>
        $('#persons').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'person',
            labelField: 'person',
            searchField: 'person',
            create: true,
            options: <?php echo json_encode($persons); ?>
        });
    </script>
</body>
</html>
