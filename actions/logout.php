<?php

require_once '../functions.php';
logout();
header('Location:' . BASE_URL . '/login.php');
