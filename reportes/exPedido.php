<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el pedido';

} elseif ($_SESSION['compras']==1) {
  //Incluímos el archivo Pedido.php
  require('Pedido.php');

  //Establecemos los datos de la empresa
  $logo = "logo.jpg";
  $ext_logo = "jpg";
  $empresa = "FarmaSalud S.A.";
  $documento = "20477157772";
  $direccion = "Bella Vista, José Gálvez 1368";
  $telefono = "931742904";
  $email = "farmasalud@gmail.com";

  //Obtenemos los datos de la cabecera del pedido actual
  require_once "../modelos/Pedido.php";
  $pedido= new Pedido();
  $rsptap = $pedido->cabeceraPedido($_GET["id"]);
  //Recorremos todos los valores obtenidos
  $regp = $rsptap->fetch_object();

  //Establecemos la configuración del pedido
  $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
  $pdf->AddPage();

  //Enviamos los datos de la empresa al método addSociete de la clase Pedido
  $pdf->addSociete(
    utf8_decode($empresa),
    $documento."\n" .
    utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
    utf8_decode("Teléfono: ").$telefono."\n" .
    "Email : ".$email,$logo,$ext_logo
  );
  $pdf->fact_dev( "Pedido ", $regp->referencia_pedido );
  // $pdf->temporaire( "Prueba" );
  $pdf->addDate( $regp->fecha_pedido);

  //Enviamos los datos del proveedor al método addClientAdresse de la clase Pedido
  $pdf->addClientAdresse(
    utf8_decode($regp->nombre),
    "Domicilio: ".utf8_decode($regp->direccion),
    "CUIT/CUIL: ".$regp->cuit_cuil,
    "Email: ".$regp->email,
    "Telefono: ".$regp->telefono,
    "Dirección de destino: " . utf8_decode($regp->direccion_destino)
  );

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles del pedido
  $cols=array(
    "CODIGO"=>26,
    "DESCRIPCION"=>90,
    "CANTIDAD"=>22,
    "PRECIO U."=>26,
    "SUBTOTAL"=>26
  );
  $pdf->addCols( $cols);
  $cols=array( 
    "CODIGO"=>"L",
    "DESCRIPCION"=>"L",
    "CANTIDAD"=>"C",
    "PRECIO U."=>"R",
    "SUBTOTAL"=>"C"
  );
  $pdf->addLineFormat( $cols);
  $pdf->addLineFormat($cols);
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y= 89;

  //Obtenemos todos los detalles del pedido actual
  $rsptad = $pedido->detallePedido($_GET["id"]);

  while ($regd = $rsptad->fetch_object()) {
    $line = array(
      "CODIGO"=> "$regd->codigo_barra",
      "DESCRIPCION"=> utf8_decode("$regd->producto"),
      "CANTIDAD"=> "$regd->cantidad",
      "PRECIO U."=> "$regd->precio_unitario",
      "SUBTOTAL"=> "$regd->subtotal"
    );
    $size = $pdf->addLine( $y, $line );
    $y   += $size + 2;
  }

  //Convertimos el total en letras
  require_once "Letras.php";
  $V=new EnLetras(); 
  $con_letra=strtoupper($V->ValorEnLetras($regp->total,"PESOS"));
  $pdf->addCadreTVAs("---".$con_letra);

  //Mostramos el impuesto
  $pdf->addTVAs( $regp->total_impuesto, $regp->total,"AR$ ");
  $pdf->addCadreEurosFrancs("IVA"." $regp->total_impuesto %");
  $pdf->Output('P'.$regp->referencia_pedido,'I');

} else {
  echo 'No tiene permiso para visualizar el pedido';
}

ob_end_flush();
?>