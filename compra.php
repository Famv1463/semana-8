<?php
require_once("conexion.php");
// REGISTRAR COMPRA
if(isset($_POST["guardar"])){
    $id_cliente = $_POST["id_cliente"];
    $id_producto = $_POST["id_producto"];
    $cantidad = $_POST["cantidad"];
    $fecha = date("Y-m-d");
    try{
        // Obtener precio y stock del producto
        $sqlProducto = "SELECT precio, stock
                        FROM PRODUCTO
                        WHERE id_producto=?";
        $stmtProducto = $conexion->prepare($sqlProducto);
        $stmtProducto->execute([$id_producto]);
        $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);
        if($producto){
            if($cantidad <= $producto["stock"]){
                $total = $producto["precio"] * $cantidad;
                // Registrar compra
                $sqlCompra = "INSERT INTO COMPRA
                (cantidad,total,fecha,id_producto,id_cliente)
                VALUES
                (?,?,?,?,?)";
                $stmtCompra = $conexion->prepare($sqlCompra);
                $stmtCompra->execute([
                    $cantidad,
                    $total,
                    $fecha,
                    $id_producto,
                    $id_cliente
                ]);
                // Actualizar stock
                $nuevoStock = $producto["stock"] - $cantidad;
                $sqlStock = "UPDATE PRODUCTO
                             SET stock=?
                             WHERE id_producto=?";
                $stmtStock = $conexion->prepare($sqlStock);
                $stmtStock->execute([
                    $nuevoStock,
                    $id_producto
                ]);
                $mensaje = "Compra registrada correctamente.";
            }else{
                $mensaje = "No existe stock suficiente.";
            }
        }
    }catch(PDOException $e){
        $mensaje = "Error al registrar la compra.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Compras</title>
<script>
function validarCompra(){
    let cliente=document.getElementById("id_cliente").value;
    let producto=document.getElementById("id_producto").value;
    let cantidad=document.getElementById("cantidad").value;
    if(cliente==""){
        alert("Seleccione un cliente");
        return false;
    }
    if(producto==""){
        alert("Seleccione un producto");
        return false;
    }
    if(cantidad<=0){
        alert("Ingrese una cantidad válida");
        return false;
    }
    return true;
}
</script>
</head>
<body>
<h1>Registro de Compras</h1>
<?php
if(isset($mensaje)){
    echo "<p style='color:green;'>$mensaje</p>";
}
?>
<form method="post" onsubmit="return validarCompra();">
<label>Cliente</label>
<br>
<select name="id_cliente" id="id_cliente" required>
<option value="">Seleccione</option>
<?php
$sql="SELECT * FROM CLIENTE ORDER BY nombre";
$stmt=$conexion->query($sql);
while($fila=$stmt->fetch(PDO::FETCH_ASSOC)){
?>
<option value="<?php echo $fila["id_cliente"]; ?>">
<?php echo $fila["nombre"]; ?>
</option>
<?php
}
?>
</select>
<br><br>
<label>Producto</label>
<br>
<select name="id_producto" id="id_producto" required>
<option value="">Seleccione</option>
<?php
$sql="SELECT * FROM PRODUCTO
WHERE stock>0
ORDER BY nombre";
$stmt=$conexion->query($sql);
while($fila=$stmt->fetch(PDO::FETCH_ASSOC)){
?>
<option value="<?php echo $fila["id_producto"]; ?>">
<?php
echo $fila["nombre"];
echo " | Stock: ".$fila["stock"];
echo " | $".$fila["precio"];
?>
</option>
<?php
}
?>
</select>
<br><br>
<label>Cantidad</label>
<br>
<input
type="number"
name="cantidad"
id="cantidad"
min="1"
required>
<br><br>
<input
type="submit"
name="guardar"
value="Registrar Compra">
</form>
<hr>
<h2>Compras Registradas</h2>
<table border="1" cellpadding="8">
<tr>
<th>ID Compra</th>
<th>Cliente</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Total</th>
<th>Fecha</th>
</tr>
<?php
try{
    $sql = "SELECT
            C.id_compra,
            CL.nombre AS cliente,
            P.nombre AS producto,
            C.cantidad,
            C.total,
            C.fecha
            FROM COMPRA C
            INNER JOIN CLIENTE CL
            ON C.id_cliente = CL.id_cliente
            INNER JOIN PRODUCTO P
            ON C.id_producto = P.id_producto
            ORDER BY C.id_compra";
    $stmt = $conexion->query($sql);
    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>";
        echo "<td>".$fila["id_compra"]."</td>";
        echo "<td>".$fila["cliente"]."</td>";
        echo "<td>".$fila["producto"]."</td>";
        echo "<td>".$fila["cantidad"]."</td>";
        echo "<td>$".$fila["total"]."</td>";
        echo "<td>".$fila["fecha"]."</td>";
        echo "</tr>";
    }
}catch(PDOException $e){
    echo "<tr>";
    echo "<td colspan='6'>No existen compras registradas.</td>";
    echo "</tr>";
}
?>
</table>
<hr>
<h2>Clientes con más de dos compras</h2>
<table border="1" cellpadding="8">
<tr>
<th>Cliente</th>
<th>Total de Compras</th>
</tr>
<?php
try{
    $sql = "SELECT
            CL.nombre,
            COUNT(C.id_compra) AS total_compras
            FROM CLIENTE CL
            INNER JOIN COMPRA C
            ON CL.id_cliente = C.id_cliente
            GROUP BY
            CL.id_cliente,
            CL.nombre
            HAVING COUNT(C.id_compra) > 2
            ORDER BY total_compras DESC";
    $stmt = $conexion->query($sql);
    $encontro = false;
    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
        $encontro = true;
        echo "<tr>";
        echo "<td>".$fila["nombre"]."</td>";
        echo "<td>".$fila["total_compras"]."</td>";
        echo "</tr>";
    }
    if(!$encontro){
        echo "<tr>";
        echo "<td colspan='2'>No existen clientes con más de dos compras.</td>";
        echo "</tr>";
    }
}catch(PDOException $e){
    echo "<tr>";
    echo "<td colspan='2'>Error al ejecutar la consulta.</td>";
    echo "</tr>";
}
?>
</table>
<hr>
<h2>Disponibilidad de Productos</h2>
<table border="1" cellpadding="8">
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Stock</th>
<th>Estado</th>
</tr>
<?php
try{
    $sql = "SELECT
            nombre,
            precio,
            stock,
            CASE
                WHEN stock > 0 THEN 'Disponible'
                ELSE 'Sin Stock'
            END AS estado
            FROM PRODUCTO
            ORDER BY nombre";
    $stmt = $conexion->query($sql);
    while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>";
        echo "<td>".$fila["nombre"]."</td>";
        echo "<td>$".$fila["precio"]."</td>";
        echo "<td>".$fila["stock"]."</td>";
        echo "<td>".$fila["estado"]."</td>";
        echo "</tr>";
    }
}catch(PDOException $e){
    echo "<tr>";
    echo "<td colspan='4'>Error al consultar los productos.</td>";
    echo "</tr>";
}
?>
</table>
<br><br>
<a href="index.php">
Volver al inicio
</a>
</body>
</html>