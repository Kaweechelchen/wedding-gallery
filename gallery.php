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

    $slideImages = array();
    $_SESSION['photos'] = array();
    foreach ($photos as $photo)
    {
        $_SESSION['photos'][] = $photo['photo'];
        $slideImages[] = array(
            'src' => 'photos/large/'.$photo['photo']
        );
    }
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/vegas.css">
    <script src="js/jquery.js"></script>
	<script src="js/vegas.js"></script>
    <style>
        .button {
        	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
        	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
        	box-shadow:inset 0px 1px 0px 0px #ffffff;
        	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
        	background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
        	background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
        	background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
        	background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
        	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
        	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);
        	background-color:#f9f9f9;
        	-moz-border-radius:6px;
        	-webkit-border-radius:6px;
        	border-radius:6px;
        	border:1px solid #dcdcdc;
        	display:inline-block;
        	cursor:pointer;
        	color:#666666;
        	font-family:Arial;
        	font-size:15px;
        	font-weight:bold;
        	padding:6px 24px;
        	text-decoration:none;
        	text-shadow:0px 1px 0px #ffffff;
        }
        .button:hover {
        	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));
        	background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
        	background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
        	background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
        	background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
        	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);
        	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);
        	background-color:#e9e9e9;
        }
        .button:active {
        	position:relative;
        	top:1px;
        }
        .buttonContainer {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
        .button {
            margin-left: 10px;
        }
    </style>
</head>
<body style="background-color: #000">
    <script>
    $("body").vegas({
        slides: <?php echo json_encode($slideImages); ?>,
        animation: 'random',
        cover: false
    });
    </script>
    <div class="buttonContainer">
        <a href="mailto:?body=<?php echo urlencode(Config::HOST.$_SERVER['REQUEST_URI']); ?>" class="button">Mail</a>
        <a href="download.php?hash=<?php echo $_GET['hash'] ?>" class="button">Download</a>
    </div>
</body>
</html>
