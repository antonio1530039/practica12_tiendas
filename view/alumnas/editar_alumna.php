<?php
  //instancia de la clase controlador
  $controller_alumnas = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_alumnas->verificarLoginController();
  //se ejecuta el metodo actualizarAlumnaController para actualizar la alumna seleccionado
  $controller_alumnas->actualizarAlumnaController();

?>


<head>
    <title>Modificación de alumna</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Modificación de alumna</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=alumnas"> Gestión de Alumnas</a></li>
              <li class="breadcrumb-item active">Modificación de alumna</li>
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
                        <h3 class="card-title">Realice los cambios correspondientes y de clic en guardar</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                    <?php $controller_alumnas->getAlumnaController() ?>
                  <button type="submit" name="btn_actualizar" id="targ"  class="btn btn-success" onclick="c();" style="float:right;">Guardar cambios</button>
            
               
              </div>   
            </div>
            </div> 
                
              </div>
</div>
</section>
  </body>
  <script>
var ps = "<?php echo $_SESSION['user_info']['password'] ?>";
      </script>
  
  </html>
