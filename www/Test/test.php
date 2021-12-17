<?php
    require_once '../validation/validator.php';
    require_once '../registration/register.php';
    require_once '../functions/update_user_datas.php';

    /*$values = select_value("authentication_datas", array('id', 'password'), "username", "ko.z.i@hotmail.com");

    var_dump($values);

    echo $values['id'].$values["password"];*/


    $value = "rebike";

    $password = password_hash($value, PASSWORD_DEFAULT);

    $sql = "UPDATE authentication_datas SET password='".$password."' WHERE id=4";

    mysqli_query($datab, $sql);

    /*if (password_verify($value, $password)) {
        echo 'Password is valid!';
    } else {
        echo 'Invalid password.';
    }



    mysqli_query($datab, $sql);

    mysqli_close($datab);*/

    /*$value = "Kozma<script>location\\.href('http://www.hacked.com')</script> Ádám  ";
    echo valid_input($value);*/

    /*$array = get_user_data(5, "admin");

    var_dump($array);*/

    /*$new_datas = array("test_column1" => "a", "test_column2" => "b", "test_column3" => "c");
    $clause_name = "id";
    $clause_value = "5";
    $table_name = "test_table";*/

    //update_data($table_name, $new_datas, $clause_name, $clause_value);

    /*
    $test = password_hash($password, PASSWORD_DEFAULT);

    echo $test;
    echo '<br>';
    echo password_hash($test, PASSWORD_DEFAULT);*/

    /*if($_SERVER["REQUEST_METHOD"] == "POST"){
        var_dump($_FILES);
        
        $file_name = $_FILES['upload']['tmp_name'];
    
        if ($file_name != ""){

            $newFilePath = "../images/".$_FILES['upload']['name'];
            move_uploaded_file($file_name, $newFilePath);
            $sql = 'INSERT INTO user_pictures (url) VALUES ("'.$newFilePath.'")';

            if (!(mysqli_query($datab, $sql))){
                echo "Error: " . $sql . "<br>" . mysqli_error($datab);
            }

        }

        $tmp_file_path = $_FILES['upload']['tmp_name'];

        if ($tmp_file_path != ""){

            $newFilePath = "../images/".$_FILES['upload']['name'];
            move_uploaded_file($tmp_file_path, $newFilePath);

            $sql = 'INSERT INTO user_pictures (url) VALUES ("'.$newFilePath.'")';

            if (!(mysqli_query($datab, $sql))){
                echo "Error: " . $sql . "<br>" . mysqli_error($datab);
            }
        }

        $tmp_file_path = $_FILES['upload']['tmp_name'];
        $user_id = select_last_id("users") + 1;

        mkdir("../images/".$user_id);
        mkdir("../images/".$user_id."/products");

        if ($tmp_file_path != ""){

            $picture_id = select_last_id("user_pictures") + 1;

            $newFilePath = "../images/".$user_id."/".$picture_id.$_FILES['upload']['name'];
            move_uploaded_file($tmp_file_path, $newFilePath);
            insert_value("user_pictures", array("url" => $newFilePath));


        }else{

        }

        mysqli_close($datab);
    }*/

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
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form method="post" action="test.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Termék képek feltöltése</label>
                        <input type="file" id="button" class="custom-file-input" name="upload" multiple="">
                        
                    </div>

                    <div class="form-group ">
                            <label>Cégjegyzékszám</label>
                            <input type="text"  pattern="[0-9]{2}-[0-9]{2}-[0-9]{6}" class="form-control" name="company_registration_number" placeholder="Add meg a cégjegyzékszámot!">
                    </div>
                    <div class="form-group">
                        <button type="submit" id="button" class="btn btn-warning">Feltölt</button>
                    </div>
                </form>
                <p id="question">Már van fiókja? </p>
                <a href="../login/login.php"> <button id="button" class="btn btn-primary">Jelentkezzen be itt!</button></a>
            </div>
            <div class="col-sm-4"></div>
            
        </div>

    </body>
 </html>