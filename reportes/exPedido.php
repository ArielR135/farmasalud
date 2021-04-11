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
  $direccion = "Bella Vista, Av. Siempre Viva 123";
  $telefono = "931742904";
  $email = "farmasalud@gmail.com";

  //Obtenemos los datos de la cabecera del pedido actual
  require_once "../modelos/Pedido.php";
  $pedido= new Pedido($_GET["id"]);
  $rsptap = $pedido->cabeceraPedido();
  //Recorremos todos los valores obtenidos
  $regp = $rsptap->fetch_object();

  //Establecemos la configuración del pedido
  $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
  $pdf->AddPage();

  //Enviamos los datos de la empresa al método addSociete de la clase Pedido
  $pdf->addSociete(
    utf8_decode($empresa),
    $documento."\n" .
    "Casa Matriz: ".utf8_decode($direccion)."\n".
    utf8_decode("Teléfono: ").$telefono."\n" .
  "Email : ".$email. "\n\n" .
    utf8_decode("Destino: ").utf8_decode($regp->direccion_destino),
    $logo,
    $ext_logo
  );
  $pdf->fact_dev( "Pedido ", $regp->referencia_pedido );
  $pdf->temporaire( "Prueba" );
  $pdf->addDate( $regp->fecha_pedido);

  //Enviamos los datos del proveedor al método addClientAdresse de la clase Pedido
  $pdf->addClientAdresse(
    utf8_decode($regp->proveedor),
    "Domicilio: ".utf8_decode($regp->direccion),
    "CUIT/CUIL: ".$regp->cuit_cuil,
    "Email: ".$regp->email,
    "Telefono: ".$regp->telefono
  );

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles del pedido
  $cols=array(
    "DESCRIPCION"=>86,
    "CANTIDAD"=>26,
    "PRECIO U."=>26,
    "IMPUESTO"=>26,
    "SUBTOTAL"=>26
  );
  $pdf->addCols( $cols);
  $cols=array( 
    "DESCRIPCION"=>"L",
    "CANTIDAD"=>"C",
    "PRECIO U."=>"R",
    "IMPUESTO"=>"R",
    "SUBTOTAL"=>"C"
  );
  $pdf->addLineFormat( $cols);
  $pdf->addLineFormat($cols);
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y= 89;

  //Obtenemos todos los detalles del pedido actual
  $rsptad = $pedido->detallePedido();

  while ($regd = $rsptad->fetch_object()) {
    $line = array(
      "DESCRIPCION"=> utf8_decode("$regd->producto"),
      "CANTIDAD"=> "$regd->cantidad",
      "PRECIO U."=> "$regd->precio_unitario",
      "IMPUESTO"=> "$regd->impuesto",
      "SUBTOTAL"=> "$regd->subtotal"
    );
    $size = $pdf->addLine( $y, $line );
    $y += $size + 2;
  }

  //Convertimos el total en letras
  require_once "Letras.php";
  $V=new EnLetras(); 
  $con_letra=strtoupper($V->ValorEnLetras($regp->total,"PESOS"));
  $pdf->addCadreTVAs("---".$con_letra);

  //Mostramos el impuesto
  $pdf->addTVAs($regp->total_impuesto, $regp->total,"AR$ ");
  $pdf->addCadreEurosFrancs("IMPUESTOS");
  $pdf->Output('P'.$regp->referencia_pedido,'I');

} else {
  echo 'No tiene permiso para visualizar el pedido';
}

ob_end_flush();
?>