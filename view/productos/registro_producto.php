<?php

  //instancia de la clase controlador
  $controller_productos = new MVC();
  //verificar si el usuario inicio sesion antes
  $controller_productos->verificarLoginController();
  //registro de producto al presionar el boton de registrar
  $controller_productos->registroProductoController();

?>

  <head>
    <title>Registro de producto</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Registro de producto</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=productos"> Gestión de Productos</a></li>
              <li class="breadcrumb-item active">Registro de producto</li>
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
          <div class="col-sm-6">
            <!-- general form elements -->
            <div class="card card-primary">
               <div class="card-header">
                        <h3 class="card-title">Por favor, Ingresa la información del producto</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <p>
                    <label>Codigo</label>
                    <input type="text" class="form-control" name="codigo" placeholder="Ingresa el codigo del producto" required="">
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre del producto" required="">
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Descripcion</label>
                    <input type="text" class="form-control" name="descripcion" placeholder="Ingresa la descripcion del producto" required="">
                  </p>
                  </div>
               
            </div>   
            </div>
            <!-- -->
            </div> 




            <!-- left column -->
          <div class="col-sm-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <p>
                    <label>Categoria</label>
                    <select class="form-control select2" name="categoria" required="">
                      <?php $controller_productos->getSelectForCategorias(""); ?>
                    </select>
                  </p>
                  </div>
                  <div class="form-group">
                    <label>Precio unitario</label>
                        <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="number" step="0.0000001"  class="form-control" name="precio" required="">
                      <div class="input-group-append">
                        <span class="input-group-text"></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Stock</label>
                        <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Unidades</span>
                      </div>
                      <input type="number" step="1" id="stockk"  class="form-control" name="stock" required="">
                      <div class="input-group-append">
                        <span class="input-group-text"></span>
                      </div>
                    </div>
                  </div>
                  <br>
                  
                   <input type="submit" name="btn_agregar" value="Registrar" class="btn btn-success" onclick="veri();" style="float: right;">
            
               
            </div>   
            </div>
            <!-- -->
            </div> 


                
            </div><!-- end row-->
</div>
</div>
  </form>
</div>
</section> 
<script type="text/javascript">
  //funcion que verifica si el stock es mayor a 0
  function veri(){
    if(document.getElementById("stockk").value < 0){
      event.preventDefault();
      swal("Stock invalido", "Ingresa un numero mayor o igual a 0 en el campo stock.", "warning");
    }
  }

</script>
  </body>

  </html>
