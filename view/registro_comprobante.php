<?php

  //instancia de la clase controlador
  $controller_comprobante = new MVC();

  $controller_comprobante->getAlumnasController("requestJSON"); //ejecutar metodo del controlador para obtener las alumnas mediante una funcion json_encoder
  $controller_comprobante->registroComprobanteController();
?>

  <head>
    <title>Registro de comprobante</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php"> Registro de comprobante</a></li>
            </ol>
          </div><!-- /.col -->

        </div><!-- /.row -->
        <center><h1 class="m-0 text-dark">Registro de comprobante </h1><br><h4>Festival Verano 2018</h4></center>
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <form role="form" method="post" enctype="multipart/form-data">
        <div class="row">
          <!-- left column -->
          <div class="col-sm-6">
            <!-- general form elements -->
            <div class="card card-primary">
               <div class="card-header">
                        <h3 class="card-title">Por favor, Ingresa la información del comprobante</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body login-card-body">
                  <div class="form-group">
                    <p>
                    <label>Grupo</label>
                    <select class="form-control select2" name="grupo" id="grupo" required="" onchange="cambioGrupo();">
                      <?php  $controller_comprobante->getSelectForX("grupos", ""); ?>
                    </select>
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Alumna</label>
                    <select class="form-control select2" name="alumna" required="" id="select_alumnas">
                    </select>
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Datos de la mama</label>
                    <div class="row">
                      <div class="col-6">
                         <input type="text" class="form-control" name="nombre" placeholder="Nombres" required="">
                      </div>

                      <div class="col-6">
                         <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required="">
                      </div>
                    </div>
                   
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
                    <label>Foto del comprobante</label><br>
                      <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required="">
                  </p>
                  </div>
                  <div class="form-group">
                    <label>Folio</label>
                        <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Numero de folio</span>
                      </div>
                      <input type="number" step="1"  class="form-control" name="folio" required="">
                      <div class="input-group-append">
                        <span class="input-group-text"></span>
                      </div>
                    </div>
                  </div>
                  

                  <div class="form-group">
                    <label>Fecha de pago</label>
                         <input type="date" class="form-control" name="fecha" required="">

                  </div>



                  <br>
                  
                   <input type="submit" name="btn_agregar" value="Registrar" class="btn btn-success" onclick="veri();" style="float: right;">
            
               
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
<script type="text/javascript">
  var alumnas = <?php echo json_encode($_SESSION['temp_alumnas']); ?>; //obtener las alumnas por medio de php
      //agregar las almunas del grupo seleccionado al selectAlumnas
      alumnas.forEach( function(value, index, array) {
          if(value["id_grupo"] == document.getElementById("grupo").value ){
            //crear elemento option
            var opt = document.createElement("option");
            opt.value = value['id'];
            opt.innerHTML = value['nombre'] + " " + value["apellidos"];
            document.getElementById("select_alumnas").appendChild(opt);
          }
          
      });

  //funcion que agrega al select de alumnas - las alumnas del grupo seleccionado en el select de grupos
  var selectAlumnas = document.getElementById("select_alumnas");
  function cambioGrupo(){
      //eliminar todos los elementos del select de alumnas
      var fc = selectAlumnas.firstChild;
      selectAlumnas.innerHTML = "";
      $('#select_alumnas').val(null).trigger('change');

      //obtener gurpo seleccionado
      var grupoSeleccionado = document.getElementById("grupo").value;

      //agregar las almunas del grupo seleccionado al selectAlumnas
      alumnas.forEach( function(value, index, array) {
          if(value["id_grupo"] == grupoSeleccionado ){
            //crear elemento option
            var opt = document.createElement("option");
            opt.value = value['id'];
            opt.innerHTML = value['nombre'] + " " + value["apellidos"];
            selectAlumnas.appendChild(opt);
            nameLast = value['id'];
          }
          
      });
      $('#select_alumnas').val('');
  }
</script>
  </body>

  </html>
