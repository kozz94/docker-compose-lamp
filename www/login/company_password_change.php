<?php
    session_start();

    require_once "../validation/validator.php";
    require_once "../validation/error_messages.php";
    require_once "../registration/register.php";
    require_once "../functions/update_user_datas.php";

    $error_messages = array("password_err" => "", "confirm_password_err" => "", "company_name_err" => "");

    $form_values = array("password" => "", "confirm_password" => "", "company_name" => "");

    $user_datas = get_user_data($_SESSION["user_id"], $_SESSION["user_type"]);

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        foreach ($form_values as $key => $value) {
            $_POST[$key] = valid_input($_POST[$key]);
            reg_validator($_POST[$key], $key, $error_messages, $reg_err_messages);
    
        }

        if(empty($error_messages["company_name_err"]) && $user_datas["company_name"] != $form_values["company_name"]){
            $error_messages["company_name_err"] = "A megadott cégnév nem helyes!";
        }

        if(empty($error_messages["password_err"]) && empty($error_messages["confirm_password_err"]) && empty($error_messages["company_name_err"])){
            if(!empty($form_values["password"])){
                update_data("authentication_datas", array("password" => password_hash($form_values["password"], PASSWORD_DEFAULT)), "username", $user_datas["username"]);
            }
            header("location: logout.php");
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
        <a href="newpassword.php">
            <button id="back" class="btn btn-primary">
                <span class="glyphicon glyphicon-arrow-left"></span> Vissza
            </button>
        </a>
        <!-- Login -->
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form method="post" action="company_password_change.php">
                    <div id="form_header"> ÚJ JELSZÓ BEÁLLÍTÁSA </div>
                    <p id="info">Az új jelszó beállítása érdekében töltse ki az alábbi adatokat!</p>
                    <!-- CÉGNÉV -->
                    <div class="form-group <?php echo (!empty($error_messages["company_name_err"])) ? 'has-error' : ''; ?>">
                        <label>Cégnév</label>
                        <input type="text" name="company_name" pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü ]{3,}" class="form-control" placeholder="Add meg a cég nevét!">
                        <span class="help-block"><?php echo $error_messages["company_name_err"]; ?></span>
                    </div> 
                    <!-- ÚJ JELSZÓ -->
                    <div class="form-group <?php echo (!empty($error_messages["password_err"])) ? 'has-error' : ''; ?>">
                        <label>Új jelszó</label>
                        <input type="password" name="password" class="form-control" placeholder="Add meg a jelszavad!">
                        <span class="help-block"><?php echo $error_messages["password_err"]; ?></span>
                    </div>
                    <!-- ÚJ JELSZÓ -->
                    <div class="form-group <?php echo (!empty($error_messages["confirm_password_err"])) ? 'has-error' : ''; ?>">
                        <label>Új jelszó megerősítése</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Add meg a jelszavad mégegyszer!">
                        <span class="help-block"><?php echo $error_messages["confirm_password_err"]; ?></span>
                    </div>
                    <!-- LOGIN -->
                    <div class="form-group">
                        <button type="submit" id="button" class="btn btn-warning" name="reg_user">Jelszó beállítása</button>
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