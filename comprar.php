<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
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

        img {
            border-radius: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .menu {
            background-color: #343a40;
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }

        .logo {
            margin-right: 20px;
        }

        .logo img {
            vertical-align: middle;
            border-radius: 50%;
            max-width: 80px;
        }

        .menu-elementos {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .menu-elementos li {
            margin-right: 20px;
        }

        .menu-elementos li:last-child {
            margin-right: 0;
        }

        .menu-elementos li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s ease;
        }

        .menu-elementos li a:hover {
            background-color: #555;
        }

        h3 {
            text-align: center;
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            margin-bottom: 20px;
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

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer a {
            color: white;
            text-decoration: none;
            padding: 0 10px;
        }

        table img {
            border-radius: 0;
            display: block;
            margin: 0 auto; 
            max-width: 100%; 
            height: auto;
        }

        .btn-comprar, .btn-ver-carrito {
            background-color: black;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-comprar:hover, .btn-ver-carrito:hover {
            background-color: #0056b3;
        }

        .cantidad-input {
            width: 60px;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="menu">
    <div class="logo">
        <img src="logo2.png" width="75" height="85" alt="Logo">
    </div>
    <ul class="menu-elementos">
        <li><a href="principal.html">Inicio</a></li>
        <li><a href="comprar.php">Comprar</a></li>
        <li><a href="contacto.html">Contacto</a></li>
        <li><a href="nosotros.html">Nosotros</a></li>
         <li><a href="carrito.php" class="btn-ver-carrito">Ver pedidos</a></li>
        <li class="ml-auto"><a href="login.html">Iniciar sesión</a></li>
    </ul>
</nav>

    <?php 
        include('conexion.php');
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_producto = $_POST['id_producto'];
            $nombre_producto = $_POST['nombre_producto'];
            $precio_con_descuento = $_POST['precio_con_descuento'];
            $cantidad = $_POST['cantidad'];

            $producto = array(
                'id' => $id_producto,
                'nombre' => $nombre_producto,
                'precio' => $precio_con_descuento,
                'cantidad' => $cantidad
            );

            if (isset($_SESSION['carrito'])) {
                array_push($_SESSION['carrito'], $producto);
            } else {
                $_SESSION['carrito'] = array($producto);
            }

            echo "<script>alert('Producto añadido al carrito');</script>";
        }

        $sql="SELECT * FROM productos";
        $resultado = mysqli_query($conexion, $sql);
    ?>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Precio con Descuento</th>
                    <th>Imagen</th>
                    <th>Descuento</th>
                    <th>Stock</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>".$row['nombre']."</td>";
                        echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                        $precioConDescuento = $row['precio'] * (1 - $row['descuento'] / 100);
                        echo "<td>$" . number_format($precioConDescuento, 2) . "</td>";
                        echo "<td><img src='".$row['imagen']."' alt='Imagen' width='100'></td>";
                        echo "<td>".$row['descuento']."%</td>";
                        echo "<td>".$row['stock']."</td>";
                        if ($row['stock'] > 0) {
                            echo "<td>
                                <form method='post'>
                                    <input type='hidden' name='id_producto' value='".$row['id']."'>
                                    <input type='hidden' name='nombre_producto' value='".$row['nombre']."'>
                                    <input type='hidden' name='precio_con_descuento' value='".$precioConDescuento."'>
                                    <input type='number' name='cantidad' value='1' min='1' max='".$row['stock']."' class='cantidad-input'>
                                    <button type='submit' class='btn-comprar'>Añadir al Carrito</button>
                                </form>
                            </td>";
                        } else {
                            echo "<td>Agotado</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay productos disponibles</td></tr>";
                }
                mysqli_close($conexion);
            ?>
            </tbody>
        </table>
        <p>
        En Iberoamérica cuenta con tiendas en España, México y Brasil, además de tener distribuidores autorizados en Portugal, Colombia, Argentina, Chile, Estados Unidos, Honduras, El Salvador, Guatemala, Nicaragua, Canadá, Panamá, República Dominicana y Costa Rica.
        </p>
        <p>
            Efficitur velit accumsan et. Quisque fringilla nisi vitae nibh sollicitudin commodo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur venenatis, massa posuere tempus congue, tortor mauris consequat urna, vitae placerat enim nunc et libero. Sed sollicitudin, mauris nec aliquam molestie, diam tortor vestibulum erat, vitae ullamcorper odio arcu ac libero. In nec sapien a dui lacinia pretium. Nam pulvinar feugiat lacinia. Nam rutrum posuere molestie. Nullam sagittis est et elementum maximus.
        </p>
    </div>
<footer class="footer">
    <div class="logo">
        <img src="logo2.png" width="60" height="70" alt="Logo">
    </div>
    <ul class="menu-elementos">
        <li><a href="#Numero">4937362874</a></li>
        <li><a href="#Contacto">PORSCHE@gmail.com</a></li>
    </ul>
</footer>

</body>
</html>
