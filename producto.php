<?php

require_once("conexion.php");

// ===========================================
// GUARDAR PRODUCTO
// ===========================================

if(isset($_POST["guardar"])){

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = trim($_POST["precio"]);
    $stock = trim($_POST["stock"]);

    try{

        $sql = "INSERT INTO PRODUCTO
                (nombre, descripcion, precio, stock)
                VALUES
                (?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $stock
        ]);

        $mensaje = "Producto registrado correctamente.";

    }catch(PDOException $e){

        $mensaje = "Error al registrar el producto.";

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Registro de Productos</title>

<script>

function validarProducto(){

    let nombre=document.getElementById("nombre").value.trim();
    let descripcion=document.getElementById("descripcion").value.trim();
    let precio=document.getElementById("precio").value;
    let stock=document.getElementById("stock").value;

    if(nombre==""){

        alert("Ingrese el nombre del producto");

        return false;

    }

    if(descripcion==""){

        alert("Ingrese la descripción");

        return false;

    }

    if(precio=="" || precio<=0){

        alert("Ingrese un precio válido");

        return false;

    }

    if(stock=="" || stock<0){

        alert("Ingrese un stock válido");

        return false;

    }

    return true;

}

</script>

</head>

<body>

<h1>Registro de Productos</h1>

<?php

if(isset($mensaje)){

    echo "<p style='color:green;'>$mensaje</p>";

}

?>

<form method="post" onsubmit="return validarProducto();">

<label>Nombre</label>

<br>

<input
type="text"
id="nombre"
name="nombre"
required>

<br><br>

<label>Descripción</label>

<br>

<textarea
id="descripcion"
name="descripcion"
required></textarea>

<br><br>

Precio<br>
<input type="number"
       id="precio"
       name="precio"
       min="0"
       step="0.01"
       required><br><br>

<label>Stock</label>

<br>

<input
type="number"
id="stock"
name="stock"
min="0"
required>

<br><br>

<input
type="submit"
name="guardar"
value="Guardar Producto">

</form>

<hr>

<h2>Productos Registrados</h2>

<table border="1" cellpadding="8">

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Descripción</th>

<th>Precio</th>

<th>Stock</th>

</tr>

<?php

try{

    $sql = "SELECT * FROM PRODUCTO";

    $stmt = $conexion->query($sql);

    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){

        echo "<tr>";

        echo "<td>".$fila["id_producto"]."</td>";

        echo "<td>".$fila["nombre"]."</td>";

        echo "<td>".$fila["descripcion"]."</td>";

        echo "<td>$".$fila["precio"]."</td>";

        echo "<td>".$fila["stock"]."</td>";

        echo "</tr>";

    }

}catch(PDOException $e){

    echo "<tr>";

    echo "<td colspan='5'>Error al recuperar los datos.</td>";

    echo "</tr>";

}

?>

</table>

<br>

<a href="index.php">

Volver al inicio

</a>

</body>

</html>