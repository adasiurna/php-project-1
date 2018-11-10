<?php include_once 'includes/header.php'?>
    <div class="back"><a href="index.php"><< Atgal</a></div>


<?php

$file = $_GET['file'];
$extension = pathinfo($file)['extension'];

if ($extension == 'php') {
    highlight_file($file);
} elseif ($extension == 'txt') {
    $myfile = fopen($file, 'r') or die("Unable to open file!!!");
    echo '<p>';
    echo fread($myfile, filesize($file));
    echo '</p>';
    fclose($myfile);
}

?>
<?php include_once 'includes/footer.php'?>