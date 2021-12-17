<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true ){
    switch ($_SESSION["user_type"]) {
        case 'admin':
            header("location: ../index_pages/admin_indexpage.php");
            break;

        case 'company':
            header("location: ../index_pages/company_indexpage.php");
            break;
        
        case 'customer':
            header("location: ../index_pages/customer_indexpage.php");
            break;    

    }
}else{
    if(isset($_SESSION["user_id"])){
        session_destroy();
    }
}

require_once "../validation/validator.php";
require_once "../validation/error_messages.php";

$login_values = array("username" => "", "password" => "");
$err_messages = array("username_err" => "", "password_err" => "");
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $err_messages["username_err"] = $login_err_messages["username_empty"];

    } else{
        $login_values["username"] = valid_input($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $err_messages["password_err"] = $login_err_messages["password_empty"];

    } else{
        $login_values["password"] = valid_input($_POST["password"]);
    }
    
    if(empty($err_messages["username_err"]) && empty($err_messages["password_err"])){ 

        login_validator($login_values, $err_messages, $login_err_messages);

    }
    
    mysqli_close($datab);
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
    <a href="../index.php">
        <button id="back" class="btn btn-primary">
            <span class="glyphicon glyphicon-arrow-left"></span> Vissza
        </button>
    </a>
    <!-- Login -->
 
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form method="post" action="login.php">
                <div id="form_header"> BEJELENTKEZÉS </div>
                <!-- FELHASZNÁLÓNÉV -->
                <div class="form-group <?php echo (!empty($err_messages["username_err"])) ? 'has-error' : ''; ?>">
                    <label>Felhasználónév</label>
                    <input type="email" name="username" class="form-control" value="<?php echo $login_values["username"] ?>" placeholder="Add meg a felhasználóneved!">
                    <span class="help-block"><?php echo $err_messages["username_err"]; ?></span>
                </div> 
                <!-- JELSZÓ -->
                <div class="form-group <?php echo (!empty($err_messages["password_err"])) ? 'has-error' : ''; ?>">
                    <label>Jelszó</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $login_values["password"]; ?>" placeholder="Add meg a jelszavad!">
                    <span class="help-block"><?php echo $err_messages["password_err"]; ?></span>
                </div>
                <!-- LOGIN -->
                <div class="form-group">
                    <button type="submit" id="button" class="btn btn-warning" name="reg_user">Bejelentkezés</button>
                </div>
            </form>
                <p id="question">Elfelejtetted a jelszót?</p>
                <a href="newpassword.php"><button id="button" class="btn btn-primary">Új jelszó kérés</button></a>
                <p id="question">Még nincs fiókja? </p>
                <div class = register_button>
                    <a href="../registration/customer_reg.php"> <button id="button" class="btn btn-primary">Regisztrálj itt ügyfélként!</button></a>
                    <a href="../registration/company_reg.php"> <button id="button" class="btn btn-primary">Regisztrálj itt cégként!</button></a>
                </div>
        </div>
        <div class="col-sm-4" ></div>
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
                <i class='fas fa-car-alt ' style='font-size:90px;color:black'> </i>
                <p class="details">Minden jog fenntartva &copy; 2021</p>
            </div>
        </div>
    </section>
</body>
</html> 