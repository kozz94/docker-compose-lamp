<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
        header("location: ../login/login.php");
        exit;
    }
    
    
    require_once "../validation/validator.php";
    require_once "../registration/register.php";
    require_once "../validation/error_messages.php";
    require_once "update_user_datas.php";


    $error_messages = array("old_password_err" => "", "password_err" => "", "confirm_password_err" => "", "firstname_err" => "", "lastname_err" => "",
    "postcode_err"  => "", "settlement_name_err" => "", "address_name_err"  => "", "address_type_err"  => "", "address_number_err"  => "");

    $form_values = array("password" => "", "confirm_password" => "", "firstname" => "", "lastname" => "", "secondary_name" => "",
    "postcode" => "", "settlement_name" => "", "address_name" => "", "address_type" => "", "address_number" => "", "phone_number" => "", "email" => "");
    
    $user_datas = get_user_data($_SESSION["user_id"], $_SESSION["user_type"]);

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        foreach ($form_values as $key => $value) {
            $_POST[$key] = valid_input($_POST[$key]);
            if($key != "password" && $key != "confirm_password"){
                reg_validator($_POST[$key], $key, $error_messages, $reg_err_messages);

            }else{
                if(!(empty($_POST["old_password"]))){

                    if(password_verify($_POST["old_password"], $user_datas["password"])){
                        reg_validator($_POST[$key], $key, $error_messages, $reg_err_messages);

                    }else{
                        $error_messages["old_password_err"] = "A megadott régi jelszó helytelen!";
                    }

                }else{
                    if(!(empty($_POST["password"])) || !(empty($_POST["confirm_password"])) ){
                        $error_messages["old_password_err"] = "Csak akkor változtathatod meg a jelszavad, ha megadod a régit!";
                    }
                }
            }
        }


        if(empty($error_messages["old_password_err"]) && empty($error_messages["password_err"]) && empty($error_messages["confirm_password_err"]) && empty($error_messages["company_name_err"]) && 
        empty($error_messages["company_type_err"]) && empty($error_messages["company_registration_number_err"]) && empty($error_messages["rental_type"]) && empty($error_messages["postcode_err"]) &&
        empty($error_messages["settlement_name_err"]) && empty($error_messages["address_name_err"]) && empty($error_messages["address_type_err"]) && empty($error_messages["address_number_err"])){
            
            if(!empty($form_values["password"])){
                update_data("authentication_datas", array("password" => password_hash($form_values["password"], PASSWORD_DEFAULT)), "username", $_SESSION["email"]);
            }

            $values = select_value("users", array("address_id"), "id", $_SESSION["user_id"]);
            update_data("addresses", array("postcode" => $form_values["postcode"], "settlement_name" => $form_values["settlement_name"], "address_name" => $form_values["address_name"],
            "address_type" => $form_values["address_type"], "address_number" => $form_values["address_number"]), "id", $values["address_id"]);

            $values = select_value("admins", array("name_id"), "user_id", $_SESSION["user_id"]);
            update_data("names", array("firstname" => $form_values["firstname"], "lastname" => $form_values["lastname"], "secondary_name" => $form_values["secondary_name"]),
            "id", $values["name_id"]);
            
            if (!(empty($form_values["phone_number"]))) {
                if(!(empty($user_datas["phone_number_id"]))){
                    update_data("availabilities", array("availability_type" => "phone_number", "availability_value" => $form_values["phone_number"]), "id", $user_datas["phone_number_id"]);

                }else{
                    insert_value("availabilities", array("availability_type" => "phone_number", "availability_value" => $form_values["phone_number"], "user_id" => $_SESSION["user_id"]));
                }

            }else{
                if(!(empty($user_datas["phone_number_id"]))){
                    delete_data("availabilities", "id", $user_datas["phone_number_id"]);
                }
            }

            if (!(empty($form_values["email"]))) {
                if(!(empty($user_datas["email_id"]))){
                    update_data("availabilities", array("availability_type" => "email", "availability_value" => $form_values["email"]), "id", $user_datas["email_id"]);

                }else{
                    insert_value("availabilities", array("availability_type" => "email", "availability_value" => $form_values["email"], "user_id" => $_SESSION["user_id"]));
                }

            }else{
                if(!(empty($user_datas["email_id"]))){
                    delete_data("availabilities", "id", $user_datas["email_id"]);
                }
            }

            header("location: ../login/login.php");
        }    
    }

    mysqli_close($datab);

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
            <a class="navbar-brand" id="main_brand" href="../index.php">Bérbead kölcsönző</a>
            </div>
            <ul class="nav navbar-nav">
            <li class="options"><a class="color_to_black" href="../index.php">Kezdőlap</a></li>
            <li class="options"><a class="color_to_black" href="car_rent.php">Termék feltöltése</a></li>
            <li class="options"><a class="color_to_black" href="rolunk.php">Rólunk</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <li class="options "><a class="color_to_black"> Üdvözöljük, <?php echo $_SESSION["name"]; ?></a></li>
            <li class="active options"><a class="color_to_black" href="#"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
            <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
            </ul>
        </div>
    </nav>
    <!-- Visszagomb -->
    <a href="../index.php">
        <button id="back" class="btn btn-primary" >
            <span class="glyphicon glyphicon-arrow-left"></span> Vissza
        </button>
    </a>
    <!-- Login -->
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form method="post" action= "admin_settings.php">
                <div id="form_header">BEÁLLÍTÁSOK </div>
                <p id="info">A csillaggal jelölt mező nem módosítható!</p> 
                <!-- EMAIL -->
                <div class="form-group">
                    <label>Felhasználónév *</label>
                    <input type="email" name="email" class="form-control" disabled value="<?php echo $user_datas["username"]; ?>">
                </div>
                
                <div class="form-group <?php echo (!empty($error_messages["lastname_err"])) ? 'has-error' : ''; ?>">
                    <label>Vezetéknév</label>
                    <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü]{3,}" class="form-control" name="lastname"  value = "<?php echo $user_datas["lastname"]; ?>">
                    <span class="help-block"><?php echo $error_messages["lastname_err"]; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($error_messages["firstname_err"])) ? 'has-error' : ''; ?>">
                    <label>Keresztnév</label>
                    <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü]{3,}" class="form-control" name="firstname"  value = "<?php echo $user_datas["firstname"]; ?>">
                    <span class="help-block"><?php echo $error_messages["firstname_err"]; ?></span>
                </div>

                <div class="form-group">
                    <label>Utónév</label>
                    <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü]{3,}" class="form-control" name="secondary_name"  value = "<?php echo $user_datas["secondary_name"]; ?>">
                </div>

                <div class="form-group <?php echo (!empty($error_messages["postcode_err"])) ? 'has-error' : ''; ?>">
                    <label>Irányítószám</label>
                    <input type="text"  pattern="[0-9]{4,4}" class="form-control" name="postcode"  value = "<?php echo $user_datas["postcode"]; ?>">
                    <span class="help-block"><?php echo $error_messages["postcode_err"]; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($error_messages["settlement_name_err"])) ? 'has-error' : ''; ?>">
                    <label>Település neve</label>
                    <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü -]{4,}" class="form-control" name="settlement_name"  value = "<?php echo $user_datas["settlement_name"]; ?>">
                    <span class="help-block"><?php echo $error_messages["settlement_name_err"]; ?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($error_messages["address_name_err"])) ? 'has-error' : ''; ?>">
                    <label>Közterület neve</label>
                    <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü -]{4,}" class="form-control" name="address_name"  value = "<?php echo $user_datas["address_name"]; ?>">
                    <span class="help-block"><?php echo $error_messages["address_name_err"]; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($error_messages["address_type_err"])) ? 'has-error' : ''; ?>">
                    <label>Közterület típusa</label>
                    <input list="company_type" class="form-control" name="address_type" value = "<?php echo $user_datas["address_type"]; ?>">
                        <datalist id="company_type">
                        <option value="utca">
                        <option value="út">
                        <option value="köz">
                        <option value="tér">
                        <option value="sétány">
                        <option value="sor"> 
                        <option value="körút">  
                        </datalist> 
                    <span class="help-block"><?php echo $error_messages["address_type_err"]; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($error_messages["address_number_err"])) ? 'has-error' : ''; ?>">
                    <label>Közterület száma</label>
                    <input type="text"  pattern="[0-9]{1,}" class="form-control" name="address_number"  value = "<?php echo $user_datas["address_number"]; ?>">
                    <span class="help-block"><?php echo $error_messages["address_number_err"]; ?></span>
                </div>

                <div class="form-group">
                    <label>Telefonszám</label>
                    <input type="tel" class="form-control" name="phone_number"  value = "<?php echo $user_datas["phone_number"]; ?>">
                </div>

                <div class="form-group">
                    <label>Email cím</label>
                    <input type="email" name="email" class="form-control" value = "<?php echo $user_datas["email"]; ?>">
                </div> 
                
                <!-- RÉGI JELSZÓ -->
                <div class="form-group <?php echo (!empty($error_messages["old_password_err"])) ? 'has-error' : ''; ?>">
                    <label>Régi jelszó</label>
                    <input type="password" name="old_password" class="form-control" placeholder="Add meg a jelszavad!">
                    <span class="help-block"><?php echo $error_messages["old_password_err"]; ?></span>
                </div>
                <!-- ÚJ JELSZÓ -->
                <div class="form-group <?php echo (!empty($error_messages["password_err"])) ? 'has-error' : ''; ?>">
                    <label>Új jelszó</label>
                    <input type="password" name="password" class="form-control" placeholder="Add meg a jelszavad!">
                    <span class="help-block"><?php echo $error_messages["password_err"]; ?></span>
                </div>
                <!-- ÚJ JELSZÓ 2x -->
                <div class="form-group <?php echo (!empty($error_messages["confirm_password_err"])) ? 'has-error' : ''; ?>">
                    <label>Új jelszó megerősítése</label>
                    <input type="password" name="confirm_password" class="form-control"  placeholder="Add meg a jelszavad mégegyszer!">
                    <span class="help-block"><?php echo $error_messages["confirm_password_err"]; ?></span>
                </div>
                
                <!-- LOGIN -->
                <div class = "buttons">
                    <div class="form-group">
                        <button type="submit" id="button" class="btn btn-primary" name="update">Változtat</button>
                    </div>

                    <div class="form-group">
                        <button type="button" id="button" class="btn btn-danger" name="user_delet" data-toggle="modal" data-target="#popup">Felhasználó törlése</button>
                    </div> 
                </div> 
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popuplabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popuplabel">Felhasználó törlése</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Biztos benne, hogy törölni szeretné a fiókját?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                <!-- <button type="submit" class="btn btn-primary">Megerősítés</button>-->
                <a href="delete_user.php"> <button id="button" class="btn btn-primary">Megerősítés</button></a>
            </div>
            </div>
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