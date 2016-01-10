<?php

// load config
require_once __DIR__.'/../config.php';

// all images
$images = glob(__DIR__.'/../photos/*.jpg');

// get image number and name
$number = empty($_GET['number'])? 0 : intval($_GET['number']);
if ($number < 0 || $number >= count($images))
    $number = 0;
$imageName = basename($images[$number]);

// connect to the database
$pdo = new PDO('mysql:dbname='.Config::DB_NAME.';host='.Config::DB_HOST, Config::DB_USER, Config::DB_PASS);

if (isset($_POST['persons'])) {
    // delete old entries
    $query = $pdo->prepare('DELETE FROM photo2person WHERE photo = :photo');
    $query->execute(array(
        'photo' => $imageName
    ));

    // insert new persons
    $persons = array_filter(explode(',', $_POST['persons']));
    $query = $pdo->prepare('INSERT INTO photo2person (photo, person) VALUES (:photo, :person)');
    foreach ($persons as $person) {
        $query->execute(array(
            'photo' => $imageName,
            'person' => $person
        ));
    }
}

// load persons
$query = $pdo->prepare('SELECT person FROM photo2person WHERE photo = :photo ORDER BY person ASC');
$query->execute(array(
    'photo' => $imageName
));
$persons = array_map(function($person) { return $person['person']; }, $query->fetchAll());

// load all persons
$query = $pdo->prepare('SELECT DISTINCT person FROM photo2person ORDER BY person ASC');
$query->execute();
$allPersons = $query->fetchAll();

// get preview image
$image = __DIR__.'/../photos/large/'.$imageName;

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/selectize.css">
    <script src="../js/jquery.js"></script>
	<script src="../js/selectize.js"></script>
</head>
<body>
    <img style="max-width: 100%;" src="data:image/jpg;base64,<?php echo base64_encode(file_get_contents($image)); ?>" />
    <form method="POST">
        <input type="text" id="persons" name="persons" /><br />
        <button type="submit">Save</button>
    </form>
    <hr />
    <a href="?number=<?php echo $number+1; ?>">Next</a>
    <script>
        $('#persons').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'person',
            labelField: 'person',
            searchField: 'person',
            create: true,
            items: <?php echo json_encode($persons); ?>,
            options: <?php echo json_encode($allPersons); ?>
        });
    </script>
</body>
</html>
