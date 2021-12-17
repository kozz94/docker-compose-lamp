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
                    <li class="options"><a class="color_to_black"> <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profile_picture" data-toggle="modal" data-target="#popup"> Üdvözöljük, <?php echo $username; ?></a></li>
                    <li class="options"><a class="color_to_black" href="../functions/customer_settings.php"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
                    <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
                </ul>
            </div>
        </nav>
        <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popuplabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="popuplabel">Profil kép feltöltése vagy törlése</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profil_picture_details" >                            
                        </div>
                        <div class="modal-body">
                                <label>Új profilkép feltöltése</label>
                                <input type="file" id="button" class="custom-file-input" name="upload" multiple="">
                        </div>
                                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                            <!-- <button type="submit" class="btn btn-primary">Megerősítés</button>-->
                            <a href="delete_user.php"> <button id="popupbutton" class="btn btn-primary">Megerősítés</button></a>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html> 