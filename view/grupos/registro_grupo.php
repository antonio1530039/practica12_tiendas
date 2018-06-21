<?php

  //instancia de la clase controlador
  $controller_grupos = new MVC();
  //verificar si el usuario inicio sesion antes
  $controller_grupos->verificarLoginController();
  //registro de producto al presionar el boton de registrar
  $controller_grupos->registroGrupoController();
?>

  <head>
    <title>Registro de grupo</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Registro de grupo</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=grupos"> Gestión de Grupos</a></li>
              <li class="breadcrumb-item active">Registro de grupo</li>
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
                        <h3 class="card-title">Por favor, Ingresa la información del grupo</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <p>
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre del grupo" required="">
                  </p>
                  </div>
                  
                   <input type="submit" name="btn_agregar" value="Registrar" class="btn btn-success" style="float: right;">
            
               
            </div>   
            </div>
            <!-- -->
            </div> 
                
              </div>
</div>
</div>
  </form>
</div>
</section>
  </body>

  </html>
