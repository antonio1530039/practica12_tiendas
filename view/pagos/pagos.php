<?php
  //instancia de la clase controlador
  $controller_pagos = new MVC();
  //se verifica que el usuario haya iniciado sesion
  $controller_pagos->verificarLoginController();

?>
  <head>
    <title>Gestion de Alumnas</title>
  </head>
  <body>
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Gestión de Pagos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Gestión de Pagos</li>
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
                    <input type="button" class="btn btn-primary" name="btn_back" value="Registrar comprobante de pago" onclick="window.location = 'index.php?action=registro_comprobante'" style="float: right;">
                    <br><br>
                  </div>
                  <div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Listado de pagos</h3>
                      </div>
                    <div class="card-body p-0">
                      <br>
                    <div class="table-responsive">
                    <table width="100%" id="example1" class="table table-bordered table-striped">
                      <!-- Id, grupo, nombre mama, fecha pago, fecha de envio, comprobante, folio, VER COMPROBANTE
 -->
                      <thead>
                        <th>Id</th>
                        <th>Grupo</th>
                        <th>Mama</th>
                        <th>Fecha de pago</th>
                        <th>Fecha de envio</th>
                        <th>Comprobante</th>
                        <th>Folio</th>
                        <th>Editar</th>
                        <th>Borrar</th>
                        <th>Aprobar</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado de grupos
                        $controller_pagos->getPagosController(); 
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

