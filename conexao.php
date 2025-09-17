<?php
$conn = new mysqli("localhost","root","","dbphp");

if ($conn->connect_error){
    echo "Error". $conn->connect_error;
    exit;
}

?>
