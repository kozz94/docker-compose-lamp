<?php
    if(isset($_SESSION["user_id"])){
        session_destroy();
    }
    
    require_once "../validation/validator.php";
    require_once "../validation/error_messages.php";

    $username_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $_POST["username"] = valid_input($_POST["username"]);

        username_validator($_POST["username"], $username_err, $login_err_messages);

        if($username_err == ""){
            if($_SESSION["user_type"] == "company"){
                header("location: company_password_change.php");
            }elseif($_SESSION["user_type"] == "customer"){
                header("location: customer_password_change.php");
            }else{
                $username_err = "A megadott felhasználónév egy admin felhasználóhoz tartozik!";
            }
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
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <!-- Visszagomb -->
        <a href="login.php">
            <button id="back" class="btn btn-primary">
                <span class="glyphicon glyphicon-arrow-left"></span> Vissza
            </button>
        </a>
        <!-- Login -->
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form method="post" action="newpassword.php">
                    <div id="form_header"> ÚJ JELSZÓ BEÁLLÍTÁSA </div>
                    <p id="info">Az új jelszó beállítása érdekében töltse ki az alábbi adatokat!</p>
                    <!-- EMAIL -->
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Felhasználónév</label>
                        <input type="email" name="username" class="form-control" placeholder="Add meg a felhasználóneved!">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>
                    <!-- LOGIN -->
                    <div class="form-group">
                        <button type="submit" id="button" class="btn btn-warning" name="reg_user">Felhasználónév megadása</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>

        <!-- footer -->
        <section id="footer">
            <div class="row">
                <div class="col-sm-4">
                    <p class="details">Bérbead kölcsönző</p>
                    <p class="details"><a href="#">Adatkezelési szabályzat</a></p>
                    <p class="details"><a href="#">Általános szerződési feltételek</a></p>
                </div>
                <div class="col-sm-4">
                    <p class="details">E-mail cím: berbeadKolcsonzo@gmail.com</p>
                    <p class="details">Telefonszám: +36 30/000-0000</p>
                    <p class="details">Győr, 9025, Fő utca 1.</p>
                </div>
                <div class="col-sm-4">
                    <i class="fas fa-car-alt " style="font-size:90px;color:black"> </i>
                    <p class="details">Minden jog fenntartva © 2021</p>
                </div>
            </div>
        </section>
    </body>
 </html>