<?php

    session_start();
    if(!(isset($_SESSION["loggedin"])) && $_SESSION["loggedin"] !== true){
        header('location: ../index.php');
    }else{
        $username = $_SESSION["name"];
    }

    require_once "../functions/get_product_datas.php";

    $max_product_per_page = 10;

    $products = get_product_basic_datas($_SESSION["user_id"]);

    $total_pages = ceil(count($products) / $max_product_per_page);

    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }

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
                    <li class="options"><a class="color_to_black"> <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profile_picture" data-toggle="modal" data-target="#popup"> Üdvözöljük, <?php echo $username; ?></a></li>
                    <li class="options"><a class="color_to_black" href="../functions/company_settings.php"><span class="glyphicon glyphicon-cog"></span> Beállítások</a></li>
                    <li class="options"><a class="color_to_black" href="../login/logout.php"><span class="glyphicon glyphicon-log-in"></span> Kijelentkezés</a></li>
                </ul>
            </div>
        </nav>
        <section>
            <div class="row">
                <p id="p1">Saját termékek</p>
            </div>
        </section>

        <section>
            <div class="row">
                <!-- Keresés -->
                <div class="col-sm-2" id="serching">
                    <form method="post" name="search_form" action="car_rent.php">
                        <div id="form_header"> Keresés </div>
                        <div class="form-group">
                            <label>Autónév</label>
                            <input type="text" name="name" class="form-control" value="<?php if(!empty($keyword)){echo $keyword;} ?>">
                        </div>
                        <div class="form-group">
                            <label>Hány forinttól?</label>
                            <input type="number" name="price_min" class="form-control" value="<?php if(!empty($price_min)){echo $price_min;} ?>">
                        </div>
                        <div class="form-group">
                            <label>Hány forintig?</label>
                            <input type="number" name="price_max" class="form-control" value="<?php if(!empty($price_max)){echo $price_max;} ?>">
                        </div> 
                        <div class="form-group">
                            <label ><input type="checkbox" name="is_free" value="0000" <?php if(!empty($is_free)){?> checked <?php } ?>> Csak az elérhető autók</label>
                        </div> 
                        <div class="form-group">
                            <button type="submit" id="button" class="btn btn-warning" name="reg_user">Keres</button>
                        </div>
                    </form>
                    <form method="post" action="search_delete.php">
                        <div class="form-group">
                            <button type="submit" id="button" class="btn btn-danger" name="reg_user">Keresés törlése</button>
                        </div>
                    </form>
                </div>

                <div class="col-sm-10">
                    <?php 
                        if($products == 0){
                            echo "Valami balul sült el próbálja meg késöbb!";
                            
                        }else{
                            for($i = ($pageno - 1) * $max_product_per_page; $i < count($products) && $i < ($pageno * $max_product_per_page); $i++){
                                $actual_product = $products[$i];

                                if(get_product_pictures($actual_product["id"]) == 0){
                                    $product_profil_picture = "../images/basic_product_picture.png";

                                }else{
                                    $product_profil_picture = get_product_pictures($actual_product["id"])[0]["url"];

                                }?>
                                <a href="<?php echo '../functions/product_details.php?product_id='.$actual_product["id"] ?>">
                                    <div class="car_mini_details">
                                        <img src="<?php echo $product_profil_picture ?>" alt="Termék kép" width="160px" height="100px"> 
                                        <p class="car_details"> <?php  echo $actual_product["product_name"]; ?> </p>
                                        <p class="car_details"> <?php  echo number_format($actual_product["price"], 0, " ", "  "); ?> Ft/nap </p>
                                        <p class="car_details"> <?php  echo $actual_product["status"]; ?> </p>
                                    </div>
                                </a>
                            <?php }
                        }?>
                    <ul class="pagination">
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Előző</a>
                        </li>
                        <?php
                            for($i=1;$i<=$total_pages;$i++){
                                ?>
                                    <li><a href="?pageno=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php  
                            }

                            mysqli_close($datab);
                        ?>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Következő</a>
                        </li>
                    </ul>
                </div>
            </div> 
        </section>
        <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popuplabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="popuplabel">Profilkép</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <img src="<?php echo $_SESSION["profile_picture_url"] ?>" alt="Profilkép" class="profil_picture_details" >                            
                        </div>  
                        <div class="modal-footer">
                            <a href="delete_profile_picture.php"> <button type="button" id="profile_picture_delete" class="btn btn-danger" name="user_delet" >Profilkép törlése</button></a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html> 