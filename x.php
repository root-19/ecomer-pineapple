<?php
$usr = "root";
$serv = "localhost";
$pass = "";

$conn = new mysqli($serv, $usr, $pass);


if($conn->connect_error){
    die("Tanga");
}else{
    echo 1;
}