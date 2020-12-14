<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>Gracias por su compra</h1>
	<h3>Detalles de la compra</h3>
	<table>
		<thead>
			<tr>
				<td>Nombre del producto</td>
				<td>Precio</td>
				<td>Cantidad</td>
				<td>Subtotal</td>
			</tr>
		</thead>
		<tbody>
			<?php
				$total = 0;
				
				$arregloCarrito = $_SESSION['carrito'];
				for($i=0;$i<count($arregloCarrito);$i++){
					$total = $total + ($arregloCarrito[$i]['Precio'] * $arregloCarrito[$i]['Cantidad'] );
			?>		
			<tr>
				<td><?php echo $arregloCarrito[$i]['Nombre']; ?></td>
				<td><?php echo $arregloCarrito[$i]['Precio']; ?></td>
				<td><?php echo $arregloCarrito[$i]['Cantidad']; ?></td>
				<td><?php echo $arregloCarrito[$i]['Precio'] * $arregloCarrito[$i]['Cantidad']; ?></td>
			</tr>
			<?php 	} ?>
		</tbody>
	</table>
	<h2>Total de la compra : <?php echo $total ?></h2>
	
	<a href="http://localhost/carrito/verpedido.php?id_venta='$id_venta'">Ver status del pedido</a>
</body>
</html>