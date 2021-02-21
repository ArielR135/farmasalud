<?php
// Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {

require 'header.php';

if ($_SESSION['compras']===1) {
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">              
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Pedidos
                            <button class="btn btn-success" id="btnAgregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                            <button class="btn btn-warning" id="btnEditar" onclick="editar()" style="display: none;"><i class="fa fa-exclamation-circle"></i> Editar</button>
                            <!-- <a target="_blank" id="btnPedido"><button class="btn btn-info"><i class="fa fa-file"></i> Pedido</button></a> -->
                          </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Referencia Pedido</th>
                            <th>Fecha Pedido</th>
                            <th>Documento Origen</th>
                            <th>Total</th>
                            <th>Proveedor</th>
                            <th>Estado Pedido</th>
                          </thead>
                          <tbody>
                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Referencia Pedido</th>
                            <th>Fecha Pedido</th>
                            <th>Documento Origen</th>
                            <th>Total</th>
                            <th>Proveedor</th>
                            <th>Estado Pedido</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Referencia(*):</label>
                            <input type="hidden" name="idpedido" id="idpedido">
                            <input type="text" class="form-control" name="referencia_pedido" id="referencia_pedido" maxlength="6" required readonly value="000001">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Fecha de pedido(*):</label>
                            <input type="date" class="form-control" name="fecha_pedido" id="fecha_pedido" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dirección destino(*):</label>     
                            <input type="text" class="form-control" name="direccion_destino" id="direccion_destino" maxlength="256" placeholder="Calle 123" required value="Bella Vista, Avenida Siempre Viva 123">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Documento origen:</label>     
                            <input type="text" class="form-control" name="documento_origen" id="documento_origen" maxlength="20">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Estado del pedido(*):</label>
                            <select id="estado_pedido" name="estado_pedido" class="form-control selectpicker" required>
                            <!-- <option value="Pedido de presupuesto">Pedido de presupuesto</option>
                            <option value="Pedido presup. enviado">Pedido de presupuesto enviado</option>
                            <option value="Pedido de compra">Pedido de compra</option>
                            <option value="Anulado">Anulado</option> -->
                            <option value="Pendiente">Pendiente</option>
                            <option value="Aceptado">Aceptado</option>
                            <option value="Anulado">Anulado</option>
                            </select>
                            <!-- <input type="text" class="form-control" name="estado_pedido" id="estado_pedido" maxlength="20" value="Pedido de presupuesto" readonly> -->
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Proveedor(*):</label>
                            <select id="idproveedor" name="idproveedor" class="form-control selectpicker" required></select>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-top:25px;">
                            <a data-toggle="modal" href="#myModal">
                              <button id="btnAgregarProd" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Productos</button>
                            </a>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                <th>Opciones</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Impuesto</th>
                                <th>Subtotal</th>
                              </thead>
                              <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                  <h5 style="text-align: right;">Impuestos:</h5> <br>
                                  <h5 style="text-align: right;">
                                    <strong>TOTAL:</strong>
                                  </h5>
                                </th>
                                <th>
                                  <h5 id="impuestos">AR$ 0,00</h5> </br>
                                  <input type="hidden" name="total_impuesto" id="total_impuesto">
                                  <h4 id="total">AR$ 0,00</h4>
                                  <input type="hidden" name="total_compra" id="total_compra">
                                </th>
                              </tfoot>
                              <tbody>

                              </tbody>
                            </table>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione los productos</h4>
        </div>
        <div class="modal-body">
          <table id="tblproductos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Sustancia</th>
                <th>Stock</th>
                <th>Proveedor</th>
                <th>Laboratorio</th>
                <th>Presentacion</th>
                <th>Imagen</th>
            </thead>
            <tbody>
              
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Sustancia</th>
              <th>Stock</th>
              <th>Proveedor</th>
              <th>Laboratorio</th>
              <th>Presentacion</th>
              <th>Imagen</th>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>        
      </div>
    </div>
  </div>
  <!-- Fin Modal -->
<?php
} else {
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/pedido.js"></script>
<?php
}
ob_end_flush();
?>