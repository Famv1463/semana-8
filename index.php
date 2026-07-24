<?php
session_start();

require_once("conexion.php");


// Crear el carrito si no existe
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

// Inicio de sesion
if (isset($_POST["login"])) {

    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Usuario de prueba
    if ($usuario == "prueba" && $password == "12345") {

        $_SESSION["usuario"] = $usuario;

    } else {

        $mensaje = "Usuario o contraseña incorrectos.";

    }
}

// Cerrar sesion
if (isset($_POST["logout"])) {

    session_destroy();

    header("Location: index.php");

    exit();

}

// Agregar productos
if (isset($_POST["producto"]) && isset($_SESSION["usuario"])) {

    $producto = $_POST["producto"];

    // Si el producto ya existe aumenta la cantidad
    if (isset($_SESSION["carrito"][$producto])) {

        $_SESSION["carrito"][$producto]++;

    } else {

        $_SESSION["carrito"][$producto] = 1;

    }

}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Comercio Electronico</title>

</head>

<body>

<h1>Hardwares y otros</h1>

<?php

if (!isset($_SESSION["usuario"])) {

?>

<h2>Iniciar Sesion</h2>
<h4>id= prueba</h4>
<h4>password= 12345</h4>

<form method="post">

<label>Usuario</label><br>

<input type="text" name="usuario" required><br><br>

<label>Contraseña</label><br>

<input type="password" name="password" required><br><br>

<button type="submit" name="login">

Ingresar

</button>

</form>

<?php

if(isset($mensaje)){

    echo "<p style='color:red;'>$mensaje</p>";

}

?>

<?php

}else{

?>

<p>

Bienvenido <strong><?php echo $_SESSION["usuario"]; ?></strong>

</p>

<form method="post">

<button type="submit" name="logout">

Cerrar sesion

</button>

</form>

<hr>

<h2>Productos</h2>

<form method="post">

<?php

$sql = "SELECT * FROM PRODUCTO";

$stmt = $conexion->query($sql);

while($producto = $stmt->fetch(PDO::FETCH_ASSOC)){

?>

<p>

<strong><?php echo $producto["nombre"]; ?></strong>

<br>

<?php echo $producto["descripcion"]; ?>

<br>

Precio:
$<?php echo number_format($producto["precio"],0,",","."); ?>

<br>

Stock:
<?php echo $producto["stock"]; ?>

<br><br>

<button
type="submit"
name="producto"
value="<?php echo $producto["nombre"]; ?>">

Agregar al carrito

</button>

</p>

<hr>

<?php

}

?>

</form>

<hr>

<hr>

<p>

Productos en el carrito:

<strong>

<?php

echo array_sum($_SESSION["carrito"]);

?>

</strong>

</p>

<a href="carrito.php">

Ver carrito de compras

</a>

<?php

}

?>
<hr>

<h2>Administración</h2>

<p>

<a href="producto.php">

Registrar Productos

</a>

</p>

<p>

<a href="cliente.php">

Registrar Clientes

</a>

</p>
<section>
<h2>Promociones</h2>
    <div id="promociones"></div>
</section>
</body>

</html>
