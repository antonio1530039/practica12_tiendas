<?php

  //instancia de la clase controlador
  $controller_categorias = new MVC();
  //verificar si el usuario inicio sesion antes
  $controller_categorias->verificarLoginController();
  //registro de producto al presionar el boton de registrar
  $controller_categorias->registroCategoriaController();

?>

  <head>
    <title>Registro de categoria</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Registro de categoria</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=categorias"> Gestión de Categorias</a></li>
              <li class="breadcrumb-item active">Registro de categoria</li>
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
                        <h3 class="card-title">Por favor, Ingresa la información de la categoria</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <p>
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre de la cateogria" required="">
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
