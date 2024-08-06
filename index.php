<?php 
    $hostname = "localhost";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($hostname,$username,$password);

    if(!$conn){
        die("connection error" . mysqli_connect_error());
    }
?>