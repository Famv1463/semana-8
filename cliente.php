<?php

require_once("conexion.php");

// ===========================================
// GUARDAR CLIENTE
// ===========================================

if(isset($_POST["guardar"])){

    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $direccion = trim($_POST["direccion"]);

    try{

        $sql = "INSERT INTO CLIENTE
                (nombre, email, direccion)
                VALUES
                (?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $nombre,
            $email,
            $direccion
        ]);

        $mensaje = "Cliente registrado correctamente.";

    }catch(PDOException $e){

        $mensaje = "Error al registrar el cliente.";

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Registro de Clientes</title>

<script>

function validarCliente(){

    let nombre = document.getElementById("nombre").value.trim();
    let email = document.getElementById("email").value.trim();
    let direccion = document.getElementById("direccion").value.trim();

    if(nombre==""){

        alert("Ingrese el nombre del cliente");

        return false;

    }

    if(email==""){

        alert("Ingrese el correo electrónico");

        return false;

    }

    let expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!expresion.test(email)){

        alert("Ingrese un correo electrónico válido");

        return false;

    }

    if(direccion==""){

        alert("Ingrese la dirección");

        return false;

    }

    return true;

}

</script>

</head>

<body>

<h1>Registro de Clientes</h1>

<?php

if(isset($mensaje)){

    echo "<p style='color:green;'>$mensaje</p>";

}

?>

<form method="post" onsubmit="return validarCliente();">

<label>Nombre</label>

<br>

<input
type="text"
id="nombre"
name="nombre"
required>

<br><br>

<label>Correo Electrónico</label>

<br>

<input
type="email"
id="email"
name="email"
required>

<br><br>

<label>Dirección</label>

<br>

<input
type="text"
id="direccion"
name="direccion"
required>

<br><br>

<input
type="submit"
name="guardar"
value="Guardar Cliente">

</form>

<hr>

<h2>Clientes Registrados</h2>

<table border="1" cellpadding="8">

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Correo Electrónico</th>

<th>Dirección</th>

</tr>

<?php

try{

    $sql = "SELECT * FROM CLIENTE";

    $stmt = $conexion->query($sql);

    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){

        echo "<tr>";

        echo "<td>".$fila["id_cliente"]."</td>";

        echo "<td>".$fila["nombre"]."</td>";

        echo "<td>".$fila["email"]."</td>";

        echo "<td>".$fila["direccion"]."</td>";

        echo "</tr>";

    }

}catch(PDOException $e){

    echo "<tr>";

    echo "<td colspan='4'>Error al recuperar los datos.</td>";

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