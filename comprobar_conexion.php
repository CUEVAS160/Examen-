<?php
$servername = "localhost";
$username = "root";
$password = "root";
$database = "SEMESTRAL";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa!";
}

\
$conn->close();
?>
