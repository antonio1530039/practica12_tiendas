<?php
  //instancia de la clase controlador
  $controller_inventario = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_inventario->verificarLoginController();
  //ejecutar el metodo que realiza el registro de historial
  $controller_inventario->registroHistorialController();

?>
  <head>
    <title>Movimiento de inventario</title>
  </head>
  <body>

  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Movimiento de inventario</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Movimiento de inventario</li>
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
              <form role="" method="post">
                <div class="row">
                <div class="col-6">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Ingreso o salida de productos</h3>
                      </div>
                    <div class="card-body p-0">
                      <center><img src='view/dist/img/Box.png' width="200" height="200" alt='Imagen de producto'></center>
                    <br>
                    <div class="container row">

                    <div class="col-12">
                        <div class="form-group">
                            <select class="form-control select2" name="producto" required="">
                              <?php $controller_inventario->getSelectForProductos(""); ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Unidades</span>
                              </div>
                            <input type="number" step="1"  min="1"  class="form-control" name="cantidad" required="">
                            <div class="input-group-append">
                              <span class="input-group-text"></span>
                            </div>
                          </div>

                          </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                          <input type="number" step="1" class="form-control" name="codigo_control" required="" placeholder="Numero de control">

                          </div>
                    </div>
                  </div>

                  <div class="container">
                    <center><br>
                          <input type="submit" name="btn_movimiento_entrada"  class="btn btn-success" value="Entrada">
                          <input type="submit" name="btn_movimiento_salida"  class="btn btn-danger" value="Salida">
                    </center><br>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6">
              <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Historial</h3>
                      </div>
                    <div class="card-body p-0">
                      
                    </div>
                    <br>
                    <div class="table-responsive">
                      <table id='htable' width='100%' class='table table-bordered table-striped'>
                      <thead>
                        <th>Ref</th>
                        <th>Producto</th>
                        <th>User</th>
                        <th>Fecha</th>
                        <th>Cant</th>
                        <th>Tipo</th>
                      </thead>
                      <tbody>
                        <?php 
                        //listado del historial de productos
                        $controller_inventario->getHistorialProductos(); 
                         ?>
                      </tbody>
                    </table>
                    </div>

                  </div>

                </div>
              </div>
            </div>

</div>


              </form>
          </div>
        </section>
  </body>

