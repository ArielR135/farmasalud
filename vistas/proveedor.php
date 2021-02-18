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
                          <h1 class="box-title">Proveedores <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Cuit-Cuil</th>
                            <th>E-Mail</th>
                            <th>Sitio Web</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Provincia</th>
                            <th>Código Postal</th>
                            <th>País</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>
                            
                          </tbody>
                          <tfoot>
                          <th>Opciones</th>
                            <th>Nombre</th>
                            <th>CUIT-CUIL</th>
                            <th>E-Mail</th>
                            <th>Sitio Web</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Provincia</th>
                            <th>Código Postal</th>
                            <th>País</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre(*):</label>
                            <input type="hidden" name="idproveedor" id="idproveedor">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>CUIT-CUIL(*):</label>     
                            <input type="text" class="form-control" name="cuit_cuil" id="cuit_cuil" maxlength="20" placeholder="CUIT-CUIL" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>E-Mail:</label>     
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="ejemplo@mail.com">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Sitio Web:</label>     
                            <input type="text" class="form-control" name="sitio_web" id="sitio_web" maxlength="70" placeholder="www.ejemplo.com">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Teléfono:</label>     
                            <input type="tel" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dirección(*):</label>     
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" placeholder="Calle" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Ciudad(*):</label>     
                            <input type="text" class="form-control" name="ciudad" id="ciudad" maxlength="50" placeholder="Ciudad" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Provincia(*):</label>     
                            <input type="text" class="form-control" name="provincia" id="provincia" maxlength="50" placeholder="Provincia" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Código Postal(*):</label>     
                            <input type="number" class="form-control" name="codigo_postal" id="codigo_postal" placeholder="Código Postal" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>País(*):</label>     
                            <input type="text" class="form-control" name="pais" id="pais" maxlength="50" placeholder="País" required>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
<?php
} else {
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/proveedor.js"></script>
<?php
}
ob_end_flush();
?>