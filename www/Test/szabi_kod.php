<?php 

session_start();

require_once "config.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["is_admin"] == "1"){
    $loggedin_admin = true;
}elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $loggedin = true;
}else{
    $loggedin = false;
}

if(!empty($loggedin)){
    $nickname_id = $_SESSION["id"];
    $sql_nickname = "SELECT nickname FROM user WHERE id = $nickname_id";
    $result_nickname = $link->query($sql_nickname);
    $nickname="";
    if ($result_nickname->num_rows > 0) {

        while($row = $result_nickname->fetch_assoc()) {
            $nickname = $row["nickname"] ;
        }
    }
}

$name = $km = $performance = $release = $price = $how_many_rents = $description  = $img[] = $counter = $is_under_rent = $is_coming_in_future = $when_coming = $is_inactive = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $carid=trim($_POST["id"]);
    $_SESSION['carid'] = $carid; 
    
    $sql_select = "SELECT * FROM cars WHERE id = $carid";
    $result = $link->query($sql_select);

    $sql_select_img = "SELECT path FROM cars, pictures WHERE cars.id = pictures.car_id && cars.id=$carid";
    $result_img = $link->query($sql_select_img);

    if ($result->num_rows > 0) {
  
        while($row = $result->fetch_assoc()) {
          $name = $row["name"];
          $performance = $row["performance"];
          $release = $row["car_release_date"];
          $price = $row["price"];
          $description = $row["description"];
          $is_under_rent = $row["is_under_rent"];
          $is_coming_in_future = $row["is_coming_in_future"];
          $when_coming = $row["when_coming"];
          $is_inactive = $row["is_inactive"];
          $how_many_rents = $row["how_many_rents"];
          $km = $row["km"];
        }
    }
    if ($result_img->num_rows > 0) {
  
        while($row_img = $result_img->fetch_assoc()) {
          $img[] = $row_img["path"];
          $counter++;
        }
    }

    $_SESSION['when_coming'] = $when_coming;

    $nickname = $firstname = $lastname = $rent_from = $rent_to = $phone_number = $email = "";
 
    $sql = "SELECT nickname, email, firstname, lastname, rent_from, rent_to, phone_number FROM cars, user, rents WHERE rents.user_id = user.id && rents.cars_id = cars.id && cars.id = $carid ORDER BY rent_from DESC LIMIT 1";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
  
        while($row = $result->fetch_assoc()) {
          $nickname = $row["nickname"];
          $firstname = $row["firstname"];
          $lastname = $row["lastname"];
          $rent_from = $row["rent_from"];
          $rent_to = $row["rent_to"];
          $phone_number = $row["phone_number"];
          $email = $row["email"];
        }
    }

    $nickname_all[] = $firstname_all[] = $lastname_all[] = $rent_from_all[] = $rent_to_all[] = $phone_number_all[] = $email_all[] = "";
 
    $sql = "SELECT nickname, email, firstname, lastname, rent_from, rent_to, phone_number FROM cars, user, rents WHERE rents.user_id = user.id && rents.cars_id = cars.id && cars.id = $carid ORDER BY rent_from DESC";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
  
        while($row = $result->fetch_assoc()) {
          $nickname_all[] = $row["nickname"];
          $firstname_all[] = $row["firstname"];
          $lastname_all[] = $row["lastname"];
          $rent_from_all[] = $row["rent_from"];
          $rent_to_all[] = $row["rent_to"];
          $phone_number_all[] = $row["phone_number"];
          $email_all[] = $row["email"];

        }
    }

}

$sql = "SELECT COUNT(nickname) as name_count FROM cars, user, rents WHERE rents.user_id = user.id && rents.cars_id = cars.id && cars.id = $carid";
$result = $link->query($sql);
$name_count = "";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $name_count = $row["name_count"];
    }
}


?>


<!DOCTYPE html>
<html>
<head>
    <title>Autókölcsönző</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="css/rolunk.css">
    <link rel="stylesheet" href="css/car_rent.css">
    <link rel="stylesheet" href="css/car_images.css">
</head>
<body>
<div class="lightbox">
  <div class="multi-carousel">
    <div class="multi-carousel-inner">
      <div class="multi-carousel-item">
        <img
          src="https://mdbootstrap.com/img/Photos/Thumbnails/Slides/1.jpg"
          data-mdb-img="https://mdbootstrap.com/img/Photos/Slides/1.jpg"
          alt="Table Full of Spices"
          class="w-100"
        />
      </div>
      <div class="multi-carousel-item">
        <img
          src="https://mdbootstrap.com/img/Photos/Thumbnails/Slides/2.jpg"
          data-mdb-img="https://mdbootstrap.com/img/Photos/Slides/2.jpg"
          alt="Winter Landscape"
          class="w-100"
        />
      </div>
      <div class="multi-carousel-item">
        <img
          src="https://mdbootstrap.com/img/Photos/Thumbnails/Slides/3.jpg"
          data-mdb-img="https://mdbootstrap.com/img/Photos/Slides/3.jpg"
          alt="View of the City in the Mountains"
          class="w-100"
        />
      </div>
      <div class="multi-carousel-item">
        <img
          src="https://mdbootstrap.com/img/Photos/Thumbnails/Slides/4.jpg"
          data-mdb-img="https://mdbootstrap.com/img/Photos/Slides/4.jpg"
          alt="Place Royale Bruxelles"
          class="w-100"
        />
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      tabindex="0"
      data-mdb-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      tabindex="0"
      data-mdb-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>
</div>
</body>
</html> 