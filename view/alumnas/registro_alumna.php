<?php
  //instancia de la clase controlador
  $controller_alumnas = new MVC();
  //verificar si el usuario inicio sesion antes
  $controller_alumnas->verificarLoginController();
  //registro de alumna al presionar el boton de registrar
  $controller_alumnas->registroAlumnaController();
?>

  <head>
    <title>Registro de alumna</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Registro de alumna</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=alumnas"> Gestión de Alumnas</a></li>
              <li class="breadcrumb-item active">Registro de alumna</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <form role="form" method="post">
        <div class="row">
          <!-- left column -->
          <div class="col-sm-12">
            <!-- general form elements -->
            <div class="card card-primary">
               <div class="card-header">
                        <h3 class="card-title">Por favor, Ingresa la información de la alumna</h3>
                </div>
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-6">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la alumna" required="">
                      </div>
                      <div class="col-6">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" placeholder="Apellidos de la alumna" required="">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-6">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" required="">
                      </div>
                      <div class="col-6">
                        <label>Grupo</label>
                        <select class="form-control select2" name="grupo" required="">
                          <?php $controller_alumnas->getSelectForX("grupos", ""); ?>
                        </select>
                      </div>
                    </div>
                    </div>
                   <input type="submit" name="btn_agregar" value="Registrar" class="btn btn-success" style="float: right;">
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

  </html>
