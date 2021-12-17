<?php

    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false || $_SESSION["profile_picture_url"] == "../images/basic_picture.png"){
        header("location: ../index.php");
        exit;
    }

    require_once "../functions/update_user_datas.php";
    require_once "../validation/validator.php";

    $picture_id = select_value("users", array("picture_id"), "id", $_SESSION["user_id"]);

    if($picture_id == 0){
        echo "Valami balul sült el!";

    }else{

        update_data("users", array("picture_id" => "NULL"), "id", $_SESSION["user_id"]);
        delete_data("user_pictures", "id", $picture_id["picture_id"]);
        unlink($_SESSION["profile_picture_url"]);

        $_SESSION["profile_picture_url"] = "../images/basic_picture.png";
        header("location: ../index.php");
    }

    mysqli_close($datab);
?>