<?php
include "./php/conexion.php";
if(!isset($_GET['id_venta'])){
  header("Location: ./");
}
$datos = $conexion->query("SELECT 
        ventas.*,
        usuario.nombre,usuario.telefono,usuario.email
        FROM ventas
        INNER JOIN usuario on ventas.id_usuario = usuario.id
        WHERE ventas.id=".$_GET['id_venta'])or die($conexion->error);
$datosUsuario = mysqli_fetch_row($datos);
$datos2 = $conexion->query("SELECT * FROM envios WHERE id_venta=".$_GET['id_venta'])or die($conexion->error);
$datosEnvio = mysqli_fetch_row($datos2);

$datos3 = $conexion->query("SELECT productos_venta.*,
                    productos.nombre as nombre_producto, productos.imagen 
                    FROM productos_venta inner join productos on productos_venta.id_producto = productos.id 
                    WHERE id_venta =".$_GET['id_venta'])or die($conexion->error);
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// Agrega credenciales
MercadoPago\SDK::setAccessToken('TEST-4514881908606655-121403-5317476de6820f5aef148c28d125c8f4-687031525'); //token de 1803
// token real TEST-7538496564754037-121303-a3d98bfd93a8ae3543b939ca191fd976-6824490
// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();
$preference->back_urls = array(
	"success" => "https://localhost/carrito/thankyou.php?id_venta=".$_GET['id_venta']."&metodo=mercado_pago",
	"failure" => "https://localhost/carrito/errorpago.php?error=failure",
	"pending" => "https://localhost/carrito/errorpago.php?error=pending"
);
// Crea un ítem en la preferencia
$datos = array();
while($f = mysqli_fetch_array($datos3)){
	$item = new MercadoPago\Item();
	$item->title = $f['nombre_producto'];;
	$item->quantity = $f['cantidad'];;
	$item->unit_price = $f['precio'];;
	$datos[] = $item;
}

$preference->items = $datos;
$preference->save();

/*
curl -X POST 
-H "Content-Type: application/json" 
"https://api.mercadopago.com/users/test_user?access_Token= TEST-7538496564754037-121303-a3d98bfd93a8ae3543b939ca191fd976-6824490" 
-d '{"site_id":"MLA"}'

curl -X POST "Content-Type: application/json" 'accesstoken: TEST-7538496564754037-121303-a3d98bfd93a8ae3543b939ca191fd976-6824490' "https://api.mercadopago.com/users/test_user""{'site_id':'MLA'}"

curl -X POST -H "Content-Type: application/json" "https://api.mercadopago.com/users/test_user?access_token=TEST-7538496564754037-121303-a3d98bfd93a8ae3543b939ca191fd976-6824490" -d "{'site_id':'MLA'}"

"id":687036232,"nickname":"TESTY2Z0OWTL","password":"qatest6110","site_status":"active","email":"test_user_64035052@testuser.com"} COMPRADOR

{"id":687031525,"nickname":"TETE9951803","password":"qatest9290","site_status":"active","email":"test_user_22751437@testuser.com"} este es el mio - VENDEDOR
*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>Elije metodo de pago</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">


    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
	
	
  <div class="site-wrap">
  <?php include("./layouts/header.php"); ?> 

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="h3 mb-3 text-black">Elige metodo de pago</h2>
          </div>
          <div class="col-md-7">

            <form action="#" method="post">
              
              <div class="p-3 p-lg-5 border">
                
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Venta #<?php echo $_GET['id_venta']; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Nombre: <?php echo $datosUsuario[6]; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Email: <?php echo $datosUsuario[8]; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Telefono: <?php echo $datosUsuario[7]; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Company: <?php echo $datosEnvio[2]; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Direccion: <?php echo $datosEnvio[3]; ?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_fname" class="text-black">Provincia: <?php echo $datosEnvio[4]; ?></label>
                  </div>
                </div>
                
              </div>
            </form>
          </div>
          <div class="col-md-5 ml-auto">
            
            <h4 class="h1">Total: <?php echo $datosUsuario[2];?></h4>
            <form action="https://localhost/carrito/thankyou.php?id_venta=<?php echo $_GET['id_venta']?>&metodo=mercado_pago" method="POST">
            	<h2>Mercado pago</h2>
				<script
					src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
					data-preference-id="<?php echo $preference->id; ?>">
				</script>
			</form>
          </div>
        </div>
      </div>
    </div>

    <?php include("./layouts/footer.php"); ?> 
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
</body>
</html>


