<?php
  //instancia de la clase controlador
  $controller_ventas = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_ventas->verificarLoginController();

?>
  <head>
    <title>Gestion de Ventas</title>
  </head>
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Gestión de Ventas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Gestión de Ventas</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
              <form role="form">
                            <div class="card card-primary">
                <div class="card-body">
                  <div class="form-group">
                    <input type="button" class="btn btn-primary" name="btn_back" value="Registrar venta" onclick="window.location = 'index.php?action=registro_venta'" style="float: right;">
                    <br><br>
                  </div>
                  <div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Reporte de ventas</h3>
                      </div>
                    <div class="card-body p-0">
                      <br>
                    <div class="table-responsive">
                    <table width="100%" id="ventasTable" class="table table-bordered table-striped">
                      <thead>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                         <th>Tienda</th>
                        <th>Total</th>
                        <th>Ver detalles</th>
                         <th>Eliminar</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado de ventas
                        $controller_ventas->getVentasController(); 
                         ?>
                      </tbody>
                    </table>
                    </div>
                  </div>
                  </div>
                </div>
              </div>




              </form>

    </div>
          </div>
        </div>
        </section>
      </div>

