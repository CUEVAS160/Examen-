<?php
include('conexion.php');
session_start();

if (isset($_POST['eliminar'])) {
    $id_producto = $_POST['id_producto'];
    foreach ($_SESSION['carrito'] as $key => $producto) {
        if ($producto['id'] == $id_producto) {
            unset($_SESSION['carrito'][$key]);
            break;
        }
    }
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

if (isset($_POST['comprar'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $id_producto = $producto['id'];
        $cantidad = $producto['cantidad'];
        $sql_update = "UPDATE productos SET stock = stock - $cantidad WHERE id = '$id_producto' AND stock >= $cantidad";
        $resultado_update = mysqli_query($conexion, $sql_update);
    }
    $_SESSION['carrito'] = array();
    echo "<script>alert('Compra realizada con éxito');</script>";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Gowun+Batang:wght@400;700&family=Honk&family=Single+Day&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: white;
            font-family: 'Gowun Batang', sans-serif;
            color: #333;
        }

        .container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-eliminar, .btn-comprar {
            background-color: black;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-eliminar:hover, .btn-comprar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Compras</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $total_general = 0;
                if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                    foreach ($_SESSION['carrito'] as $producto) {
                        $total = $producto['precio'] * $producto['cantidad'];
                        $total_general += $total;
                        echo "<tr>";
                        echo "<td>".$producto['nombre']."</td>";
                        echo "<td>$" . number_format($producto['precio'], 2) . "</td>";
                        echo "<td>".$producto['cantidad']."</td>";
                        echo "<td>$" . number_format($total, 2) . "</td>";
                        echo "<td>
                            <form method='post'>
                                <input type='hidden' name='id_producto' value='".$producto['id']."'>
                                <button type='submit' name='eliminar' class='btn-eliminar'>Eliminar</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                    echo "<tr>
                            <td colspan='3'><strong>Total General</strong></td>
                            <td><strong>$" . number_format($total_general, 2) . "</strong></td>
                            <td>
                                <form method='post'>
                                    <button type='submit' name='comprar' class='btn-comprar'>Comprar</button>
                                </form>
                            </td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='5'>No hay pedidos</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>

