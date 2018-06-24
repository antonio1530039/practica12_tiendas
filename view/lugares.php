<?php
  //instancia de la clase controlador
  $controller_lugares = new MVC();
  //se verifica que el usuario haya iniciado sesion
  //$controller_lugares->verificarLoginController();

?>
  <head>
    <title>Lugares</title>
  </head>
  <body>
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Lugares</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Lugares</li>
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
            <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">
                  <div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title"></h3>
                      </div>
                    <div class="card-body p-0">
                      <br>
                    <div class="table-responsive">
                    <table width="100%" id="lugaresTable" class="table table-bordered table-striped">
                      <!-- Id, grupo, nombre mama, fecha pago, fecha de envio, comprobante, folio, VER COMPROBANTE
 -->
                      <thead>
                        <th>Lugar</th>
                        <th>Grupo</th>
                        <th>Nombre alumna</th>
                        <th>Nombre mama</th>
                        <th>Fecha de pago</th>
                        <th>Fecha de envio</th>
                        <th>Folio</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado de grupos
                        $controller_lugares->getPagosSortedController(); 
                         ?>
                      </tbody>
                    </table>
                    </div>
                  </div>
                  </div>
                </div>
              </form>
            </div>    
    </div>
  </body>

