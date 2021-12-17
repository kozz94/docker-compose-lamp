<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
        header("location: ../login/login.php");
        exit;
    }


    require_once "../validation/validator.php";
    require_once "../validation/error_messages.php";
    require_once "../registration/register.php";


    $error_messages = array("product_name_err" => "", "product_type_err" => "", "price_err" => "", "status_err" => "");

    $form_values = array("product_name" => "", "product_type" => "", "price" => "", "description" => "", "status" => "", "active_again_date" => "");
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        foreach ($form_values as $key => $value) {
            $_POST[$key] = valid_input($_POST[$key]);
            reg_validator($_POST[$key], $key, $error_messages, $product_download);

        }

        if(empty($error_messages["product_name_err"]) && empty($error_messages["product_type_err"]) && empty($error_messages["price_err"]) && empty($error_messages["status_err"])){
            $insert_datas = array("company_id" => $_SESSION["user_id"]);
            foreach ($form_values as $key => $value) {
                if(!(empty($value))){
                    $insert_datas = array_merge($insert_datas, array($key => $value));
                }
            }

            insert_value("products", $insert_datas);
            $product_id = select_last_id("products");

            $size = count($_FILES['upload']['name']);
    
            for( $i=0 ; $i < $size ; $i++ ) {
                $tmp_file_path = $_FILES['upload']['tmp_name'][$i];
        
                if ($tmp_file_path != ""){

                    if($i == 0){
                        mkdir("../images/".$_SESSION['user_id']."/products/".$product_id);
                    }

                    $picture_id = select_last_id("product_pictures") + 1;

                    $newFilePath = "../images/".$_SESSION['user_id']."/products/".$product_id."/".$picture_id.$_FILES['upload']['name'][$i];
                    move_uploaded_file($tmp_file_path, $newFilePath);
                    insert_value("product_pictures", array("product_id" => $product_id, "url" => $newFilePath));
                }
            }
        }
        header("location: ../index_pages/company_indexpage.php");
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
                    <a class="navbar-brand" id="main_brand" href="#">Bérbead kölcsönző</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="options"><a class="color_to_black" href="../index.php">Kezdőlap</a></li>
                    <li class="options"><a class="color_to_black" href="add_product.php">Termék feltöltése</a></li>
                    <li class="options"><a class="color_to_black" href="#">Rólunk</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="options"><a class="color_to_black" href="../functions/company_settings.php"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
                    <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
                </ul>
            </div>
        </nav>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form method="post" action="add_product.php" enctype="multipart/form-data">
                    <div id="form_header"> ADATOK FELTÖLTÉSE </div>
                    <div class="form-group <?php echo (!empty($error_messages["product_name_err"])) ? 'has-error' : ''; ?>">
                        <label>Termék megnevezése</label>
                        <input type="text" name="product_name" class="form-control" placeholder="Add meg a termék megnevezését!">
                        <span class="help-block"><?php echo $error_messages["product_name_err"]; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($error_messages["product_type_err"])) ? 'has-error' : ''; ?>">
                        <label>Áru típusa (pl.: Autó, ruha, munkagép)</label>
                        <input type="text" name="product_type" class="form-control" placeholder="Add meg a termék kategóriáját!">
                        <span class="help-block"><?php echo $error_messages["product_type_err"]; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($error_messages["price_err"])) ? 'has-error' : ''; ?>">
                        <label>Ár (Ft/nap)</label>
                        <input type="number" min="1" name="price" class="form-control" placeholder="Add meg a termék bérlési árát!">
                        <span class="help-block"><?php echo $error_messages["price_err"]; ?></span>
                    </div>
                    <div class="form-group ">
                        <label>Leírás</label>
                        <textarea class="form-control" name="description" rows="10" cols="40" placeholder="Add meg a termék leírását!"></textarea>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group <?php echo (!empty($error_messages["status_err"])) ? 'has-error' : ''; ?>">
                        <label>Termék státusza</label>
                        <label>Kölcsönözhető</label>
                        <input type="radio" class="form-control" name="status" checked="checked" value="available">
                        <label>Nem kölcsönözhető</label>
                        <input type="radio" class="form-control" name="status" value="rented">
                        <label>Nem elérhető</label>
                        <input type="radio" class="form-control" name="status" value="not_available">
                        <span class="help-block"><?php echo $error_messages["status_err"]; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Termék visszatérésének dátuma(Csak ha jövőben érkezik!)</label>
                        <input type="date" name="active_again_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Termék képek feltöltése</label>
                        <input type="file" id="button" class="custom-file-input" name="upload[]" multiple="">
                        
                    </div>
                    <div class="form-group">
                        <button type="submit" id="button" class="btn btn-warning">Feltölt</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-4"></div>
            
        </div>

    </body>
 </html>