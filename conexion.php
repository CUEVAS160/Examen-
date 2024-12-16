<?php
$servername="localhost";
$username="root";
$password="root";
$database="SEMESTRAL";

$conexion = mysqli_connect($servername,$username,$password,$database);
if (!$conexion) {
	die("no se pudo conectar: " .mysqli_connect_error());
} else {
	echo "";
}
?>
