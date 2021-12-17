<?php 
require_once "register.php";

                
/*insert into szakdoga.authentication_datas (username, password) values ('ko.z.i@hotmail.com', 'Xyhzq3999');
insert into szakdoga.addresses (postcode, settlement_name, address_name, address_type, address_number) values ('9300', 'Csorna', 'Gárdonyi Géza', "utca", "21");
insert into szakdoga.users (authentication_id, address_id, user_type, status) values ('5', '5', 'admin', 'active');
insert into szakdoga.names (firstname, lastname) values ('Ádám', 'Kozma');
insert into szakdoga.admins (user_id, name_id) values ('5', '3');
insert into szakdoga.availabilities (availability_type, availability_value) values ('phone_number', '06307865822');*/

$value = "xyhzq3999";

insert_value("authentication_datas", array("username" => 'ko.z.i@hotmail.com', "password" => (password_hash($value, PASSWORD_DEFAULT))));
$authentication_id = select_last_id("authentication_datas");

insert_value("addresses", array("postcode" => "9300", "settlement_name" => "Csorna", "address_name" => "Gárdonyi Géza",
"address_type" => "utca", "address_number" => "21"));
$address_id = select_last_id("addresses");

$user_type = "admin";
$status = "active";

insert_value("users", array("authentication_id" => $authentication_id, "address_id" => $address_id, "user_type" => $user_type, "status" => $status));
$user_id = select_last_id("users");

insert_value("names", array("firstname" => "Ádám", "lastname" => "Kozma"));
$name_id = select_last_id("names");

insert_value("admins", array("user_id" => $user_id, "name_id" => $name_id));

insert_value("availabilities", array("availability_type" => "phone_number", "availability_value" => "06307865822", "user_id" => $user_id));

$value = "rebike";

insert_value("authentication_datas", array("username" => 'rebike@gmail.com', "password" => (password_hash($value, PASSWORD_DEFAULT))));
$authentication_id = select_last_id("authentication_datas");

insert_value("addresses", array("postcode" => "4321", "settlement_name" => "Valahol-kanada", "address_name" => "Canadian street",
"address_type" => "utca", "address_number" => "96"));
$address_id = select_last_id("addresses");

$user_type = "admin";
$status = "active";

insert_value("users", array("authentication_id" => $authentication_id, "address_id" => $address_id, "user_type" => $user_type, "status" => $status));
$user_id = select_last_id("users");

insert_value("names", array("firstname" => "Rebeka", "lastname" => "Lőrinczki"));
$name_id = select_last_id("names");

insert_value("admins", array("user_id" => $user_id, "name_id" => $name_id));

insert_value("availabilities", array("availability_type" => "phone_number", "availability_value" => "06307777777", "user_id" => $user_id));

mysqli_close($datab);

?>