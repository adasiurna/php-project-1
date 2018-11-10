<?php require_once 'functions.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet"  type="text/css" media="screen" href="css/font-awesome.min.css">
    <title>File reader</title>
</head>
<body>
<header>
    <nav>
        <a href="#">File Reader</a>
    </nav>
    <?php if (isLoggedIn()) {?>
        <a id="logout" href="<?php echo BASE_URL; ?>/actions/logout.php">Logout</a>
    <?php }?>
</header>

<?php

if (isset($_SESSION['flash_messages'])) {
    foreach ($_SESSION['flash_messages'] as $message) {
        $messageType = $message['type'];
        $message = $message['text'];
        include 'flashMessage.php';
    }

    unset($_SESSION['flash_messages']);
}
?>

<main>

