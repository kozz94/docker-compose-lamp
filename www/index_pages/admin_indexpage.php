<?php
session_start();
if(!(isset($_SESSION["loggedin"])) && $_SESSION["loggedin"] !== true){
    header('location: ../index.php');
}else{
    $username = $_SESSION["name"];
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
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" id="main_brand" href="#">Bérbead kölcsönző</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                    <li class="options"><a class="color_to_black"> <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profile_picture"> Üdvözöljük, <?php echo $username; ?></a></li>
                    <li class="options"><a class="color_to_black" href="../functions/admin_settings.php"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
                    <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
                </ul>
            </div>
        </nav>
    </body>
</html> 