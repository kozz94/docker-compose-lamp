<?php
    require_once '../config/config.php';
    require_once "../validation/validator.php";

    function get_product_basic_datas($user_id){
        global $datab;
        $sql = 'SELECT * FROM products where company_id ='.'"'.$user_id.'"';

        $result = mysqli_query($datab, $sql);

        if ($result != false) {
            $products = Array();

            while($row = mysqli_fetch_assoc($result)) {
                switch ($row["status"]) {
                    case 'available':
                        $row["status"] = "Kölcsönözhető";
                        break;

                    case 'not_available':
                        $row["status"] = "Nem elérhető";
                        break;
                    
                    case 'rented':
                        $row["status"] = "Nem kölcsönözhető";
                        break;
                }

                array_push($products, $row);
            }

            return $products;

        }else{
            return 0;
        }
    }

    function get_product_pictures ($product_id){
        global $datab;
        $sql = 'SELECT * FROM product_pictures where product_id ='.'"'.$product_id.'"';

        $result = mysqli_query($datab, $sql);

        if ($result != false) {
            $pictures = Array();

            if(mysqli_num_rows($result) != 0){
                while($row = mysqli_fetch_assoc($result)) {
                    array_push($pictures, $row);
                }

                return $pictures;

            }else{
                return 0;
                
            }

        }else{
            return 0;

        }
    }

?>