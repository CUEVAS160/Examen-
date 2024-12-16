<?php

include ('conexion.php');

$usuario = $_GET['usuario'];
$contrasena = $_GET['contrasena'];

$consulta = "INSERT INTO usuarios (usuario, contrasena) VALUES ('$usuario', '$contrasena')";

$resultado = mysqli_query($conexion, $consulta);

if ($resultado == true) {
    echo "Registro guardado correctamente";
} else {
    echo "Hubo un error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
