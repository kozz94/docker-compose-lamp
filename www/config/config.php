<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define('DB_SERVER', 'database'); //átkell írni
    define('DB_USERNAME','root');
    define('DB_PASSWORD','tiger');
    define('DB_NAME', 'szakdoga');


    $datab = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_set_charset($datab,"utf8");

 

    if($datab === false) {
        die("ERROR: A kiszolgáló nem elérhető. " . mysqli_connect_error());
    }

?>