<?php
$server="localhost";
$db="caterinservices";
$user="root";
$pass="";
$con=mysqli_connect($server,$user,$pass,$db);
if(!$con) echo "<script>alert('connection error')</script>";
?>