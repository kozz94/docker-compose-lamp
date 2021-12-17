<?php
    require_once "../config/config.php";
    
    function update_data($table_name, $new_datas, $clause_name, $clause_value){
        global $datab;
        $sql = 'UPDATE '.$table_name.' SET ';
        $datas_size = count($new_datas);
        $i = 0;

        foreach ($new_datas as $key => $value) {
            $i++;
            

            if($i == $datas_size){
                if($value == "NULL"){
                    $sql = $sql.$key.' = '.$value.' WHERE '.$clause_name.' = "'.$clause_value.'"';

                }else{
                    $sql = $sql.$key.' = "'.$value.'" WHERE '.$clause_name.' = "'.$clause_value.'"';

                }

            }else{
                if($value == "NULL"){
                    $sql = $sql.$key.' = '.$value.' WHERE '.$clause_name.' = "'.$clause_value.'"';

                }else{
                    $sql = $sql.$key.' = "'.$value.'", ';

                }
            }
        }

        if (!(mysqli_query($datab, $sql))){
            echo "Error: " . $sql . "<br>" . mysqli_error($datab);
        }else{
            var_dump($sql);
        }
    }

    function delete_data($table_name, $clause_name, $clause_value) {
        global $datab;
        $sql = 'DELETE FROM '.$table_name.' WHERE '.$clause_name.' = "'.$clause_value.'"';

        if (!(mysqli_query($datab, $sql))){
            echo "Error: " . $sql . "<br>" . mysqli_error($datab);
        }
    }
?>