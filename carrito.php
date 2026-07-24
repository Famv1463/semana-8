<?php

session_start();

// =====================================
// VERIFICAR SESIÓN
// =====================================

if (!isset($_SESSION["usuario"])) {

    header("Location: index.php");

    exit();

}

// =====================================
// CREAR CARRITO SI NO EXISTE
// =====================================

if (!isset($_SESSION["carrito"])) {

    $_SESSION["carrito"] = [];

}

// =====================================
// VACIAR CARRITO
// =====================================

if (isset($_POST["vaciar"])) {

    $_SESSION["carrito"] = [];

}

// =====================================
// ELIMINAR UNA UNIDAD
// =====================================

if (isset($_POST["eliminarUno"])) {

    $producto = $_POST["eliminarUno"];

    if (isset($_SESSION["carrito"][$producto])) {

        $_SESSION["carrito"][$producto]--;

        if ($_SESSION["carrito"][$producto] <= 0) {

            unset($_SESSION["carrito"][$producto]);

        }

    }

}

// =====================================
// ELIMINAR PRODUCTO COMPLETO
// =====================================

if (isset($_POST["eliminarTodo"])) {

    $producto = $_POST["eliminarTodo"];

    if (isset($_SESSION["carrito"][$producto])) {

        unset($_SESSION["carrito"][$producto]);

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Carrito de Compras</title>

</head>

<body>

<h1>Carrito de Compras</h1>

<p>

Usuario conectado:

<strong>

<?php echo $_SESSION["usuario"]; ?>

</strong>

</p>

<hr>

<?php

if (empty($_SESSION["carrito"])) {

    echo "<h3>El carrito está vacío.</h3>";

} else {

?>

<table border="1" cellpadding="8">

<tr>

<th>Producto</th>

<th>Cantidad</th>

<th>Acciones</th>

</tr>

<?php

foreach ($_SESSION["carrito"] as $producto => $cantidad) {

?>

<tr>

<td>

<?php echo $producto; ?>

</td>

<td align="center">

<?php echo $cantidad; ?>

</td>

<td>

<form method="post" style="display:inline;">

<button

type="submit"

name="eliminarUno"

value="<?php echo $producto; ?>">

Quitar una unidad

</button>

</form>

<form method="post" style="display:inline;">

<button

type="submit"

name="eliminarTodo"

value="<?php echo $producto; ?>">

Eliminar producto

</button>

</form>

</td>

</tr>

<?php

}

?>

</table>

<br>

<h3>

Total de artículos:

<?php echo array_sum($_SESSION["carrito"]); ?>

</h3>

<?php

}

?>

<br>

<form method="post">

<button

type="submit"

name="vaciar">

Vaciar carrito

</button>

</form>

<br>

<a href="index.php">

Seguir comprando

</a>

</body>

</html>