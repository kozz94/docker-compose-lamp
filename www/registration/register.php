<?php
    require_once "../config/config.php";

    function reg_validator($form_value, $form_data_name, &$error_messages, &$reg_err_messages){
        global $datab;
        global $form_values;
        if($form_data_name == "username"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            } else{                
                $sql = 'SELECT id FROM authentication_datas WHERE username = "'.trim($_POST["username"]).'"';

                $result = mysqli_query($datab, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_reserved")];
                } else {
                    $form_values[$form_data_name] = trim($form_value);
                }
            }
    
        }elseif($form_data_name == "password"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            } elseif(strlen(trim($_POST["password"])) < 6){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_leng")];
            } else{
                $form_values[$form_data_name] = trim($form_value);
            }

        }elseif($form_data_name == "confirm_password"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            } else{
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($error_messages["password_err"]) && ($form_values["password"] != $form_value)){
                    $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_same")];
                }
            }

        }elseif($form_data_name == "company_type"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            }else{
                switch ($form_value) {
                    case 'Bt.':
                        $form_values[$form_data_name] = "bt";
                        break;

                    case 'Kft.':
                        $form_values[$form_data_name] = "kft";
                        break;

                    case 'NyRt.':
                        $form_values[$form_data_name] = "nyrt";
                        break;

                    case 'ZRt.':
                        $form_values[$form_data_name] = "zrt";
                        break;
                }
            }

        }elseif($form_data_name == "address_type"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            }else{
                switch ($form_value) {
                    case 'utca':
                        $form_values[$form_data_name] = "utca";
                        break;

                    case 'út':
                        $form_values[$form_data_name] = "ut";
                        break;

                    case 'körút':
                        $form_values[$form_data_name] = "korut";
                        break;

                    case 'köz':
                        $form_values[$form_data_name] = "koz";
                        break;

                    case 'tér':
                        $form_values[$form_data_name] = "ter";
                        break;

                    case 'sétány':
                        $form_values[$form_data_name] = "setany";
                        break;

                    case 'sor':
                        $form_values[$form_data_name] = "sor";
                        break;

                }
            }

        }elseif($form_data_name != "phone_number" && $form_data_name != "email" && $form_data_name != "fax" && $form_data_name != "secondary_name" && $form_data_name != "description" &&
        $form_data_name != "active_again_date"){
            if(empty(trim($form_value))){
                $error_messages[($form_data_name."_err")] = $reg_err_messages[($form_data_name."_empty")];
            }else{
                $form_values[$form_data_name] = trim($form_value);
            }
        }else{
            $form_values[$form_data_name] = trim($form_value);
        }
    }


    function insert_value($table_name, $record){
        global $datab;
        $sql = "INSERT INTO ".$table_name." (";
        $i = 0;
        $values_size = count($record);
        $values = "";

        foreach ($record as $column_name => $value) {
            $i++;

            if ($column_name == "password") {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            if($i == $values_size){
                $sql = $sql.$column_name.') VALUES (';
                $values = $values.'"'.$value.'")';
            }else{
                $sql = $sql.$column_name.', ';
                $values = $values.'"'.$value.'", ';
            }
        }

        $sql = $sql.$values;

        //echo $sql."\n"; --> teszthez

        if (!(mysqli_query($datab, $sql))){
            echo "Error: " . $sql . "<br>" . mysqli_error($datab);
            exit;
        }

        /*if (mysqli_query($datab, $sql)) {
            header("location: ../login/login.php");
        }else{
            echo "Error: " . $sql . "<br>" . mysqli_error($datab);
        }*/

    }

    function select_last_id($table_name){
        global $datab;

        $sql = 'SELECT MAX(id) FROM '.$table_name;
        $result = mysqli_query($datab, $sql);

        $row = mysqli_fetch_assoc($result);

        return $row["MAX(id)"];
    }

?>