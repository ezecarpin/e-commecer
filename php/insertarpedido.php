<?php
session_start();
include './conexion.php';
if(!isset($_SESSION['carrito'])){header("Location: ../index.php");}
$arreglo = $_SESSION['carrito'];
$total = 0;
for($i=0; $i<count($arreglo);$i++){
  $total = $total + ($arreglo[$i]['Precio'] * $arreglo[$i]['Cantidad']);
}
$password="";
if(isset($_POST['c_account_password'])){
  if($_POST['c_account_password']!=""){
    $password = $_POST['c_account_password'];
  }
}
$conexion->query("INSERT INTO usuario (nombre,telefono,email,password,img_perfil,nivel)
  VALUES(
    '".$_POST['c_fname']." ".$_POST['c_lname']."',
    '".$_POST['c_phone']."',
    '".$_POST['c_email_address']."',
    '".sha1($password)."', 
    'default.jpg',
    'cliente'
     )
  ")or die($conexion->error);
  $id_usuario = $conexion->insert_id;

$fecha = date("Y-m-d h:m:s");
$conexion->query("INSERT INTO ventas(id_usuario,total,fecha) VALUES($id_usuario,'$total','$fecha') ")or die($conexion->error);
$id_venta = $conexion->insert_id;

for($i=0; $i<count($arreglo);$i++){
  $conexion->query("INSERT INTO productos_venta(id_venta,id_producto,cantidad,precio,subtotal) 
    VALUES(
      $id_venta,
      ".$arreglo[$i]['Id'].",
      ".$arreglo[$i]['Cantidad'].",
      ".$arreglo[$i]['Precio'].",
      ".$arreglo[$i]['Cantidad']*$arreglo[$i]['Precio']."
    ) ")or die($conexion->error);
  $conexion->query("UPDATE productos SET inventario = inventario -".$arreglo[$i]['Cantidad']." WHERE id=".$arreglo[$i]['Id'] ) or die($conexion->error);
}

$conexion->query("INSERT INTO envios(pais,company,direccion,estado,cp,id_venta)   VALUES
  (
    '".$_POST['country']."',
    '".$_POST['c_companyname']."',
    '".$_POST['c_address']."',
    '".$_POST['c_state_country']."',
    '".$_POST['c_postal_zip']."',
    $id_venta
     )
     ") or die($conexion->error);
//include './php/mail.php';
unset($_SESSION['carrito']);
header("Location: ../pagar.php?id_venta=".$id_venta);
?>