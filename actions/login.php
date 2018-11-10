<?php

require_once '../functions.php';

if (isLoggedIn()) {
    header('Location:' . BASE_URL . '/index.php');
    exit();
}

if (isset($_POST['password'])) {
    if (checkPassword($_POST['password'])) {
        login();
        header('Location:' . BASE_URL . '/index.php');
        exit();
    }
}

addFlashMessage('danger', 'Password incorrect!');
header('Location:' . BASE_URL . '/login.php');
