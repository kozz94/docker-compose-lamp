<?php

    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
        header("location: ../login/login.php");
        exit;
    }else{
        $username = $_SESSION["name"];
    }

    require_once "../validation/validator.php";
    require_once "get_product_datas.php";

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    } else {
        header("location: ../login/login.php");
        exit;
    }

    $product_datas = select_value("products", array("*"), "id", $product_id);
    $product_pictures = get_product_pictures($product_id);

    if($product_datas == 0){
        echo "Valami balul sült el kérem probálja meg késöbb!";
    }else{
        //var_dump($product_datas);
        //var_dump($product_pictures);
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
                        <li class="options"><a class="color_to_black" href="../functions/add_product.php">Termék feltöltése</a></li>
                        <li class="options"><a class="color_to_black" href="rolunk.php">Rólunk</a></li>
                    </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="options"><a class="color_to_black"> <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profile_picture" data-toggle="modal" data-target="#popup"> <?php echo $username; ?></a></li>
                    <li class="options"><a class="color_to_black" href="../functions/company_settings.php"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
                    <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
                </ul>
            </div>
        </nav>

        <section>
            <div class="row">
                <div class="col-sm-4">
                    <a href="../index.php">
                        <button id="back2" class="btn btn-primary">
                            <span class="glyphicon glyphicon-arrow-left"></span> Vissza
                        </button>
                    </a>
                </div>
                <div class="col-sm-4">
                    <p id="p1">Termék adatok!</p>
                    
                </div>
                <div class="col-sm-4"></div>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4">
                    <img width="100%" height="270px" src="<?php if($product_pictures == 0){echo "../images/basic_product_picture.png"; }else{echo $product_pictures[0]["url"]; }?>" alt="Termék kép">
                    <?php 
                        if($product_pictures != 0){
                            for($i=0; $i<count($product_pictures); $i++){?>
                                <img width="100px" height="100px" id="car_img<?php echo $i; ?>" class="car_img" src="<?php echo $product_pictures[$i]["url"]; ?>" alt="Termék kép">
                        <?php
                            } 
                        }?>
                    <div id="car_modal" class="modal">
                        <span class="close">&times;</span>
                        <img class="modal-content" id="img01">
                        <div id="caption"></div>
                    </div>

                </div>
                <div class="col-sm-4">
                    <a href= "<?php echo "../validation/product_update.php?product_id=".$product_id ?>" >
                        <button class="btn btn-danger" id="button">Autó adatainak módosítás</button>
                    </a>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-sm-4">
                    <p id="p2">Termék alapadatai</p>
                    <p class = "description">Termék neve: <?php echo $product_datas["product_name"]; ?></p>
                    <p class = "description">Termék típusa: <?php echo $product_datas["product_type"]; ?></p>
                    <p class = "description"> Termék napi nettó ára: <?php echo $product_datas["price"]; ?>Ft</p>
                    <p class = "description"> Termék státusza: 
                    <?php 
                        switch ($product_datas["status"]) {
                            case 'available':
                                $product_datas["status"] = "Kölcsönözhető";
                                break;
        
                            case 'not_available':
                                $product_datas["status"] = "Nem elérhető";
                                break;
                            
                            case 'rented':
                                $product_datas["status"] = "Nem kölcsönözhető";
                                break;
                        }
                        echo $product_datas["status"]; 
                    ?></p>
                    <?php
                        if($product_datas["status"] != "available" && !empty($product_datas["active_again_date"])){
                    ?>
                            <p class = "description">Visszatérésének dátuma: <?php echo $product_datas["active_again_date"]; ?></p>
                    <?php
                        }
                    ?>
                </div>
                <div class="col-sm-6">
                    <p class = "description2"><?php echo $product_datas["description"]; ?></p>
                </div>
            </div>
        </section>
    </body>
</html> 