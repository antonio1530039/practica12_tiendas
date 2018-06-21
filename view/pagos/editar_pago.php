<?php
  //instancia de la clase controlador
  $controller_pagos = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_pagos->verificarLoginController();

   $controller_pagos->getAlumnasController("requestJSON"); //ejecutar metodo del controlador para obtener las alumnas mediante una funcion json_encoder (para el select de grupos y alumnas)
  //se ejecuta el metodo actualizarAlumnaController para actualizar la alumna seleccionado
  $controller_pagos->actualizarPagoController();

?>


<head>
    <title>Modificación de pago</title>
  </head>
  <body class="hold-transition login-page">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Modificación de pago</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="index.php?action=pagos"> Gestión de Pagos</a></li>
              <li class="breadcrumb-item active">Modificación de pago</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <form role="form" method="post">
        <div class="row">
              <?php $controller_pagos->getPagoController(); ?>
              </div>
            </form>

</div>
</section>
  </body>
 <script type="text/javascript">
  var alumnas = <?php echo json_encode($_SESSION['temp_alumnas']); ?>; //obtener las alumnas por medio de php
  var nombre_alumna = <?php echo $_SESSION['alumna_temp']; ?>; //obtener las alumnas por medio de php
      //agregar las almunas del grupo seleccionado al selectAlumnas
      alumnas.forEach( function(value, index, array) {
          if(nombre_alumna != value['id']){
              if(value["id_grupo"] == document.getElementById("grupo").value ){
              //crear elemento option
              var opt = document.createElement("option");
              opt.value = value['id'];
              opt.innerHTML = value['nombre'] + " " + value["apellidos"];
              document.getElementById("select_alumnas").appendChild(opt);
              //alert("En el índice " + index + " hay este valor: " + value["nombre"]);
            }
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
      var nameOfFirst = "";
      var nameLast = "";
      var i = 0;
      alumnas.forEach( function(value, index, array) {
          if(value["id_grupo"] == grupoSeleccionado ){
            if(i == 0){
              nameOfFirst = value['id'];
            }
            //crear elemento option
            var opt = document.createElement("option");
            opt.value = value['id'];
            opt.innerHTML = value['nombre'] + " " + value["apellidos"];
            selectAlumnas.appendChild(opt);
            nameLast = value['id'];
            i++;
          }
          
      });
      $('#select_alumnas').val('');
  }
  //funcion para el datetime picker
   $("#datetimepicker").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
</script>
  
  </html>
