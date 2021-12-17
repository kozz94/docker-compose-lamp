<?php 
    require_once "register.php";
    require_once "../validation/error_messages.php";
    require_once "../validation/validator.php";

    $error_messages = array("username_err" => "", "password_err" => "", "confirm_password_err" => "", "company_name_err" => "", "company_type_err" => "", "company_registration_number_err" => "",
    "rental_type_err" => "", "postcode_err"  => "", "settlement_name_err" => "", "address_name_err"  => "", "address_type_err"  => "", "address_number_err"  => "");

    $form_values = array("username" => "", "password" => "", "confirm_password" => "", "company_name" => "", "company_type" => "", "company_registration_number" => "",
    "rental_type" => "", "postcode" => "", "settlement_name" => "", "address_name" => "", "address_type" => "", "address_number" => "", "phone_number" => "", "email" => "", "fax" => "");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        foreach ($form_values as $key => $value) {
            $_POST[$key] = valid_input($_POST[$key]);
            reg_validator($_POST[$key], $key, $error_messages, $reg_err_messages);

        }
        
        if(empty($error_messages["username_err"]) && empty($error_messages["password_err"]) && empty($error_messages["confirm_password_err"]) && empty($error_messages["company_name_err"]) && 
        empty($error_messages["company_type_err"]) && empty($error_messages["company_registration_number_err"]) && empty($error_messages["rental_type"]) && empty($error_messages["postcode_err"]) &&
        empty($error_messages["settlement_name_err"]) && empty($error_messages["address_name_err"]) && empty($error_messages["address_type_err"]) && empty($error_messages["address_number_err"])){
            
            insert_value("authentication_datas", array("username" => $form_values["username"], "password" => $form_values["password"]));
            $authentication_id = select_last_id("authentication_datas");

            insert_value("addresses", array("postcode" => $form_values["postcode"], "settlement_name" => $form_values["settlement_name"], "address_name" => $form_values["address_name"],
            "address_type" => $form_values["address_type"], "address_number" => $form_values["address_number"]));
            $address_id = select_last_id("addresses");

            $tmp_file_path = $_FILES['upload']['tmp_name'];
            $user_id = select_last_id("users") + 1;

            mkdir("../images/".$user_id);
            mkdir("../images/".$user_id."/products");

            $user_type = "company";
            $status = "active";

            if ($tmp_file_path != ""){

                $picture_id = select_last_id("user_pictures") + 1;

                $newFilePath = "../images/".$user_id."/".$picture_id.$_FILES['upload']['name'];
                move_uploaded_file($tmp_file_path, $newFilePath);
                insert_value("user_pictures", array("url" => $newFilePath));

                insert_value("users", array("authentication_id" => $authentication_id, "address_id" => $address_id, "picture_id" => $picture_id, "user_type" => $user_type, "status" => $status));

            }else{
                insert_value("users", array("authentication_id" => $authentication_id, "address_id" => $address_id, "user_type" => $user_type, "status" => $status));

            }

            insert_value("companies_name", array("company_name" => $form_values["company_name"], "company_type" => $form_values["company_type"],
            "company_registration_number" => $form_values["company_registration_number"]));
            $company_name_id = select_last_id("companies_name");
            
            $rental_type = $form_values["rental_type"];

            insert_value("companies", array("user_id" => $user_id, "company_name_id" => $company_name_id, "rental_type" => $rental_type));

            if (!(empty($form_values["phone_number"]))) {
                insert_value("availabilities", array("availability_type" => "phone_number", "availability_value" => $form_values["phone_number"], "user_id" => $user_id));
            }

            if (!(empty($form_values["email"]))) {
                insert_value("availabilities", array("availability_type" => "email", "availability_value" => $form_values["email"], "user_id" => $user_id));
            }

            if (!(empty($form_values["fax"]))) {
                insert_value("availabilities", array("availability_type" => "fax", "availability_value" => $form_values["fax"], "user_id" => $user_id));
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
        <link rel="stylesheet" href="css/registration_login.css">
    </head>
        <body>
            <!-- Visszagomb -->
            <a href="../index.php">
                <button id="back" class="btn btn-primary">
                    <span class="glyphicon glyphicon-arrow-left"></span> Vissza
                </button>
            </a>
            <!-- Reg form -->
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <form method="post" action="company_reg.php" enctype="multipart/form-data">
                        <div id="form_header"> REGISZTRÁCIÓHOZ SZÜKSÉGES ADATOK </div>
                        <div class="form-group <?php echo (!empty($error_messages["username_err"])) ? 'has-error' : ''; ?>">
                            <label>Felhasználónév</label>
                            <input type="text" name="username" pattern=".{3,}" class="form-control" placeholder="Add meg a felhasználóneved!">
                            <span class="help-block"><?php echo $error_messages["username_err"]; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($error_messages["password_err"])) ? 'has-error' : ''; ?>">
                            <label>Jelszó</label>
                            <input type="password" name="password" class="form-control" placeholder="Add meg a jelszavad!">
                            <span class="help-block"><?php echo $error_messages["password_err"]; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($error_messages["confirm_password_err"])) ? 'has-error' : ''; ?>">
                            <label>Jelszó újra</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Add meg a jelszavad újra!">
                            <span class="help-block"><?php echo $error_messages["confirm_password_err"]; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($error_messages["company_name_err"])) ? 'has-error' : ''; ?>">
                            <label>Cégnév</label>
                            <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü ]{3,}" class="form-control" name="company_name"  placeholder="Add meg a cég nevét!">
                            <span class="help-block"><?php echo $error_messages["company_name_err"]; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($error_messages["company_type_err"])) ? 'has-error' : ''; ?>">
                            <label>Cégformátum</label>
                            <input list="company_type" class="form-control" name="company_type" placeholder="Add meg a cégformátumát!">
                                <datalist id="company_type">
                                <option value="Bt.">
                                <option value="Kft.">
                                <option value="NyRt.">
                                <option value="ZRt.">
                                </datalist> 
                            <span class="help-block"><?php echo $error_messages["company_type_err"]; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($error_messages["company_registration_number_err"])) ? 'has-error' : ''; ?>">
                            <label>Cégjegyzékszám</label>
                            <input type="text"  pattern="[0-9]{2}-[0-9]{2}-[0-9]{6}" class="form-control" name="company_registration_number" placeholder="Add meg a cégjegyzékszámot!">
                            <span class="help-block"><?php echo $error_messages["company_registration_number_err"]; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($error_messages["rental_type_err"])) ? 'has-error' : ''; ?>">
                            <label>Kölcsönző kategória (pl.: autókölcsönző, ruhakölcsönző)</label>
                            <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü -]{4,}" class="form-control" name="rental_type"  placeholder="Add meg a cégkölcsönző kategóriáját!">
                            <span class="help-block"><?php echo $error_messages["rental_type_err"]; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($error_messages["postcode_err"])) ? 'has-error' : ''; ?>">
                            <label>Irányítószám</label>
                            <input type="text"  pattern="[0-9]{4,4}" class="form-control" name="postcode"  placeholder="Add meg a cég írányítószámát!">
                            <span class="help-block"><?php echo $error_messages["postcode_err"]; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($error_messages["settlement_name_err"])) ? 'has-error' : ''; ?>">
                            <label>Település neve</label>
                            <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü -]{4,}" class="form-control" name="settlement_name"  placeholder="Add meg a cég település nevét!">
                            <span class="help-block"><?php echo $error_messages["settlement_name_err"]; ?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($error_messages["address_name_err"])) ? 'has-error' : ''; ?>">
                            <label>Közterület neve</label>
                            <input type="text"  pattern="[a-zA-ZáéíÁÉÍóÓöÖőŐúÚűŰÜü -]{4,}" class="form-control" name="address_name"  placeholder="Add meg a cég közterület nevét!">
                            <span class="help-block"><?php echo $error_messages["address_name_err"]; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($error_messages["address_type_err"])) ? 'has-error' : ''; ?>">
                            <label>Közterület típusa</label>
                            <input list="company_type" class="form-control" name="address_type" placeholder="Add meg a cég közterület típusát!">
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
                            <input type="text"  pattern="[0-9]{1,}" class="form-control" name="address_number"  placeholder="Add meg a cég közterület számát!">
                            <span class="help-block"><?php echo $error_messages["address_number_err"]; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Telefonszám</label>
                            <input type="tel" class="form-control" name="phone_number"  placeholder="Add meg a cég telefonszámát!">
                        </div>

                        <div class="form-group">
                            <label>Email cím</label>
                            <input type="email" name="email" class="form-control" placeholder="Add meg a cég e-mail címét!">
                        </div> 

                        <div class="form-group">
                            <label>Faxszám</label>
                            <input type="text" name="fax" class="form-control" placeholder="Add meg a cég faxszámát!">
                        </div>
                        
                        <div class="form-group">
                            <label>Profil kép feltöltése</label>
                            <input type="file" id="button" class="custom-file-input" name="upload" multiple="">
                        </div> 

                        <div class="form-group">
                            <button type="submit" id="button" class="btn btn-warning" name="reg_user">Regisztráció</button>
                        </div>
                    </form>
                        <p id="question">Már van fiókja? </p>
                        <a href="../login/login.php"> <button id="button" class="btn btn-primary">Jelentkezzen be itt!</button></a>
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
                        <p class="details">E-mail cím: autokolcsonzo@gmail.com</p>
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