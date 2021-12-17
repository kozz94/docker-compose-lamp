<?php
    session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $user_type = $_SESSION['user_type'];
        switch ($user_type) {
            case 'admin':
                header('location: ../index_pages/admin_indexpage.php');
                break;

            case 'company':
                header('location: ../index_pages/company_indexpage.php');
                break;

            case 'customer':
                header('location: ../index_pages/customer_indexpage.php');
                break;
        }

    }else{
        if(isset($_SESSION["user_id"])){
            session_destroy();
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Bérbead kölcsönző</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <!--<link rel="stylesheet" type="text/css" href="css/index.css">-->
</head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" id="main_brand" href="#">Bérbead kölcsönző</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="options"><a class="color_to_black" href="registration/company_reg.php">Cég regisztráció</a></li>
                <li class="options"><a class="color_to_black" href="registration/customer_reg.php">Ügyfél regisztráció</a></li>
            </ul>
            <div>
            <ul class="nav navbar-nav navbar-right">
                <li class="options"><a class="color_to_black" href="login/login.php"><span class="glyphicon glyphicon-log-in"></span> Bejelentkezés</a></li>
           </ul>
            </div>
        </nav>
    </body>
</html> 