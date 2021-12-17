<?php

session_start();

require_once "update_user_datas.php";

update_data("users", array("status" => "deleted"), "id", $_SESSION["user_id"]);


header("location: ../login/logout.php");
?>