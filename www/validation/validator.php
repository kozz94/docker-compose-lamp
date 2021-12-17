<?php
    require_once '../config/config.php';


    function valid_input($input_value){
        $input_value = trim($input_value);
        $input_value = stripslashes($input_value);
        $input_value = htmlspecialchars($input_value);
        return $input_value;
    }

    function select_value($table_name, $search_values, $clause, $clause_name){
        global $datab;
        $sql = 'SELECT ';

        $size = count($search_values);
        $i = 0;

        foreach ($search_values as $value) {
            $i++;

            if($i != $size){
                $sql = $sql.$value.",";
            }else{
                $sql = $sql.$value;
            }
        }

        $sql = $sql.' FROM '.$table_name.' WHERE '.$clause.' = "'.$clause_name.'"';

        $result = mysqli_query($datab, $sql);

        if ($result != false) {
            $row = mysqli_fetch_assoc($result);

            if($row != 0){
                return $row; 
            }else{
                return 0;
            }

        }else{
            return 0;
        }
    }

    function login_validator($login_datas, &$value_err_messages, $err_messages){
        global $datab;

        $sql = 'SELECT users.id, users.picture_id, users.user_type, users.status, authentication_datas.username, authentication_datas.password
        FROM (authentication_datas 
        INNER JOIN users ON authentication_datas.id = users.authentication_id) 
        where authentication_datas.username = "'.$login_datas["username"].'"';

        $result = mysqli_query($datab, $sql);

        if($result != false){
            if(mysqli_num_rows($result) == 0){
                $value_err_messages["username_err"] = $err_messages["username_not_exist"];
            }else{
                $row = mysqli_fetch_assoc($result);

                $user_id = $row["id"];
                $user_type = $row["user_type"];
                $status = $row["status"];
                $username = $row["username"];
                $password = $row["password"];
                $picture_id = $row["picture_id"];
                
                if($status == "active"){
                    if($user_type == "company"){
                        $name = get_company_name($user_id);
                    }else{
                        $name = get_name($user_id, $user_type);
                    }
                    
                    if($name == 0){
                        echo "Valami balul sült el probáld meg késöbb!";
                    }else{
                        if(password_verify($login_datas["password"], $password)){
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $username;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["user_type"] = $user_type;
                            $_SESSION["name"] = $name;

                            if(!empty($picture_id)){
                                $profile_picture = select_value("user_pictures", array("url"), "id", $picture_id);
                                $_SESSION["profile_picture_url"] = $profile_picture["url"];

                            }else{
                                $_SESSION["profile_picture_url"] = "../images/basic_picture.png";
                            }
                            
                            if($_SESSION["user_type"] == "admin"){
                                header("location: ../index_pages/admin_indexpage.php");
            
                            }elseif ($_SESSION["user_type"] == "company"){
                                header("location: ../index_pages/company_indexpage.php");
            
                            }else{
                                header("location: ../index_pages/customer_indexpage.php");
                            }

                        }else{
                            $value_err_messages["password_err"] = $err_messages["password_not_same"];
                        }
                    }
                }else{
                    $value_err_messages["username_err"] = $err_messages["user_deleted"];
                }
            }
        }else{
            echo "Valami balul sült el probáld meg késöbb!";
        }

    }

    function get_name($user_id, $user_type){
        global $datab;

        if($user_type == 'admin'){
            $sql = 'SELECT names.firstname
            FROM (admins 
            INNER JOIN names ON admins.name_id = names.id) 
            where admins.user_id = "'.$user_id.'"';

        }else{
            $sql = 'SELECT names.firstname
            FROM (customers 
            INNER JOIN names ON customers.name_id = names.id) 
            where customers.user_id = "'.$user_id.'"';
        }

        $result = mysqli_query($datab, $sql);

        if($result != false){
            $row = mysqli_fetch_assoc($result);

            return $row['firstname'];

        }else{
            return 0;
        }
    }

    function get_user_data($user_id, $user_type){
        global $datab;
        $get_datas;

        $sql = 'select * 
        from ((((users
        inner join authentication_datas on users.authentication_id = authentication_datas.id)
        inner join addresses on users.address_id = addresses.id) ';

        if($user_type == "admin"){
            $sql = $sql.'inner join admins on users.id = admins.user_id)
            inner join names on admins.name_id = names.id)
            where users.id = '.$user_id;

        }elseif ($user_type == "customer") {
            $sql = $sql.'inner join customers on users.id = customers.user_id)
            inner join names on customers.name_id = names.id)
            where users.id = '.$user_id;
            
        }else{
            $sql = $sql.'inner join companies on users.id = companies.user_id)
            inner join companies_name on companies.company_name_id = companies_name.id)
            where users.id = '.$user_id;
        }

        $result = mysqli_query($datab, $sql);

        if($result != false){
            $row = mysqli_fetch_assoc($result);

            $get_datas = $row;

            if($get_datas["user_type"] == "company"){
                $get_datas["company_type"] = company_type_converter($get_datas["company_type"]);
            }

            $get_datas["address_type"] = address_type_converter($get_datas["address_type"]);

            $get_datas = array_merge($get_datas, array("phone_number" => "", "email" => "", "fax" => ""));
            $get_datas = array_merge($get_datas, array("phone_number_id" => "", "email_id" => "", "fax_id" => ""));

            $sql = 'select * from availabilities where availabilities.user_id = '.$user_id;

            $result = mysqli_query($datab, $sql);            

            if($result != false){
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)) {
                        $get_datas[$row["availability_type"]] = $row["availability_value"];
                        if($row["availability_type"] == "phone_number"){
                            $get_datas["phone_number_id"] = $row["id"]; 

                        }elseif($row["availability_type"] == "email"){
                            $get_datas["email_id"] = $row["id"];

                        }else{
                            $get_datas["fax_id"] = $row["id"];

                        }
                        
                    }
                }

                return $get_datas;

            }else{
                return 0;
            }

        }else{
            return 0;
        }

    }

    function get_company_name($user_id){
        global $datab;
        $sql = 'SELECT companies_name.company_name
            FROM (companies 
            INNER JOIN companies_name ON companies.company_name_id = companies_name.id) 
            where companies.user_id = "'.$user_id.'"';

        $result = mysqli_query($datab, $sql);

        if($result != false){
            $row = mysqli_fetch_assoc($result);

            return $row['company_name'];

        }else{
            return 0;
        }

    }

    function company_type_converter($company_type){
        $result = "";

        switch ($company_type) {
            case 'bt':
                $result = "Bt.";
                break;

            case 'kft':
                $result = "Kft.";
                break;

            case 'nyrt':
                $result = "NyRt.";
                break;

            case 'zrt':
                $result = "ZRt.";
                break;
        }

        return $result;
    }

    function address_type_converter($address_type){
        $result = "";

        switch ($address_type) {
            case 'utca':
                $result = "utca";
                break;

            case 'ut':
                $result = "út";
                break;

            case 'korut':
                $result = "körút";
                break;

            case 'koz':
                $result = "köz";
                break;

            case 'ter':
                $result = "tér";
                break;

            case 'setany':
                $result = "sétány";
                break;

            case 'sor':
                $result = "sor";
                break;

        }

        return $result;
    }

    function username_validator($username, &$username_err, $err_messages){
        if(empty($username)){
            $username_err = $err_messages["username_empty"];
        }else{
            $username_id = select_value("authentication_datas", array("id"), "username", $username);
            
            if($username_id == 0){
                $username_err = $err_messages["username_not_exist"];

            }else{
                $user_datas = select_value("users", array("id", "user_type", "status"), "authentication_id", $username_id["id"]);

                if($user_datas == 0){
                    echo "Valami balul sült el próbálkozz később!";
    
                }else{
                    if($user_datas["status"] == "active"){
                        session_start();
                        $_SESSION["user_id"] = $user_datas["id"];
                        $_SESSION["user_type"] = $user_datas["user_type"];

                    }else{
                        $username_err = $err_messages["user_deleted"];
                    }
                }
            }
        }
    }

?>