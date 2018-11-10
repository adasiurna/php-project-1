<?php
include 'includes/header.php';
require_once 'functions.php';
require_once 'config.php';
checkLoginAndRedirect();
?>

<div class="container">
    <?php listFiles(FILE_FOLDER);?>
</div>


<?php include 'includes/footer.php'?>


<!-- ką dar galima padaryti:
 - vidiniuose puslapiuose paspaudus logo turi grąžinti atgal į main psl. -->