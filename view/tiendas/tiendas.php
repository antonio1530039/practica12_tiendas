<?php
  //instancia de la clase controlador
  $controller_tiendas = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_tiendas->verificarLoginController();

?>
  <head>
    <title>Gestion de Tiendas</title>
  </head>
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Gestión de Tiendas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Gestión de Tiendas</li>
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
                    <input type="button" class="btn btn-primary" name="btn_back" value="Registrar tienda" onclick="window.location = 'index.php?action=registro_tienda'" style="float: right;">
                    <br><br>
                  </div>
                  <div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Listado de tiendas activadas <a href="#tiendas_desactivadas" class="btn btn-warning fa fa-circle-o" style="float:right;color:black"> Ver tiendas desactivadas</a></h3>
                      </div>

                    <div class="card-body p-0"><br>
                    <div class="table-responsive">
                    <table width="100%" id="example1" class="table table-bordered table-striped">
                      <thead>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Fecha de registro</th>
                        <th>Editar</th>
                        <th>Desactivar</th>
                        <th>Eliminar</th>
                        <th>Ingresar</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado de tiendas
                       // $controller_tiendas->getTiendasController($_SESSION['user_info']['id']); 
                        $controller_tiendas->getTiendasController(); 
                         ?>
                      </tbody>
                    </table>
                    </div>
                  </div>
                  </div>
                </div>
              </div>



              <div class="card card-primary">
                <div class="card-body">
                  <div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Tiendas desactivadas</h3>
                      </div>
                    <div class="card-body p-0">
                      <br>
                    <div class="table-responsive">
                    <table width="100%" id="tiendas_desactivadas" class="table table-bordered table-striped">
                      <thead>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Fecha de registro</th>
                        <th>Activar</th>
                        <th>Eliminar</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado de tiendas
                       // $controller_tiendas->getTiendasController($_SESSION['user_info']['id']); 
                        $controller_tiendas->getTiendasDesactivadasController(); 
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

