<?php

class MVC{
  //metodo que muestra la plantilla base
	public function showTemplate(){
		session_start();
		include "view/template.php";
	}

  //metodo encargado de capturar la variable action mediante el metodo get y hace la peticion al modelo para que redireccione a las vistas correspondientes
	public function enlacePaginasController(){
		if(isset($_GET['action'])){
			$enlace = $_GET['action'];
		}else{
			$enlace = 'registro_comprobante';
		}
		//peticion al modelo
		$peticion = Enlaces::enlacesPaginasModel($enlace);
    	//mostrar peticion
		include $peticion;
	}

    //metodo que verifica si usuario ha iniciado sesion, si no es asi, redireccion al login
	public function verificarLoginController(){
		//session_start();
		if(isset($_SESSION)){
			if(isset($_SESSION['login'])){
            	if(!$_SESSION['login']){
          			echo "<script>window.location='index.php?action=registro_comprobante';</script>";  
          			//return false;
            	}
      }else{
        echo "<script>window.location='index.php?action=registro_comprobante';</script>"; 
        //return false;
      }
		}else{
			echo "<script>window.location='index.php?action=registro_comprobante';</script>";
			//return false;
		}
	}


	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroComprobanteController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//para subir imagen de comprobante
			$target_dir = "model/uploads/"; //directorio donde se guardara
			//$realName = substr(basename($_FILES["fileToUpload"]["name"]), 0, strlen(basename($_FILES["fileToUpload"]["name"]))-4); //nombre del archivo
			$indOfp = -1;
			$rawName = basename($_FILES["fileToUpload"]["name"]);
			for($j = strlen($rawName)-1; $j > -1; $j--){
				if($rawName[$j] == "."){
					$indOfp = $j;
					break;
				}
			}
			$realName = substr(basename($_FILES["fileToUpload"]["name"]), 0, $indOfp); //
			
			$realExt = substr(basename($_FILES["fileToUpload"]["name"]), $indOfp, strlen(basename($_FILES["fileToUpload"]["name"]))); //extension
			//echo "<h1>".$realName . date("Y-m-d H:i:s")."</h1>";
			$target_file = $target_dir . md5($realName . date("Y-m-d H:i:s") . rand ( 0 , rand(1, 9999999) ) ) . $realExt; //directorio del archivo
			//echo "<h1>basename: " . basename($_FILES["fileToUpload"]["name"]) ."</h1>";
			
			//echo "<h1>basename: " .   "</h1>";
			$uploadOk = 1; //bandera para comprobar si no ocurrio un error
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); //obtener tipo de archivo
			// verificar si es una imagen el archivo
			$log = ""; //variable de log de erorres
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
			    $uploadOk = 1;
			} else {
			    $log.="El archivo no es una imagen. ";
			     $uploadOk = 0;
			  }
			// Check if file already exists
			if (file_exists($target_file)) {
			    $log.="El archivo ya existe en el directorio. ";
			    $uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2000000) {
			    $log.="El archivo excede el tamaño minimo (1MB). ";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    $log.="Lo sentimos, solo los archivos con extension JPG, JPEG, PNG & GIF estan permitidos. ";
			    $uploadOk = 0;
			}
			// verificar si $uploadOk esta en 0 (ocurrio un error)
			if ($uploadOk == 0) {
			    echo "<script>swal('Error al subir la imagen',' ".$log."','error');</script>";
			// si todo esta bien,  intentar subir el archivo
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			       //registrar el pago en laBD
			    	//crear array con los datos a registrar tomados de los controles
					$data = array('id_grupo'=> $_POST['grupo'],
						'id_alumna'=> $_POST['alumna'],
						'nombre_mama'=> $_POST['nombre'] . " " . $_POST['apellidos'],
						'imagen'=>$target_file,
						'folio'=> $_POST['folio'],
						'fecha_pago'=> $_POST['fecha'],
						'fecha_envio'=> date("Y-m-d H:i:s")

						);
					//peticion al modelo del reigstro del comprobante mandando como param la informacion de este
					$registro = Crud::registroComprobanteModel($data);
					if($registro == "success"){ //verificar la respuesta del modelo
		        		echo "<script>swal('Exito!','Comprobante registrado. El siguiente paso es esperar a que el administrador apruebe tu comprobante, al hacer esto, tu lugar aparecera en la sesccion Lugares del menu izquierdo.','success');</script>";
					}else{
						echo "<script>swal('Error','Ocurrió un error al registrar','error');</script>";
					}

			    } else {
			        echo "<script>swal('Error al subir la imagen',' ".$log."','error');</script>";
			    }
			}
		}
	}


 //metodo especifico para el archivo header.php o navegacion, el cual verifica si el usuario esta logueado, entonces muestra el menu
 public function showNav(){
    if(isset($_SESSION)){
    	      	echo "<!-- Navbar -->

			  <nav class='main-header navbar navbar-expand bg-white navbar-light border-bottom'>
			    <!-- Left navbar links -->
			    <ul class='navbar-nav'>
			      <li class='nav-item'>
			        <a class='nav-link' data-widget='pushmenu' href='#'><i class='fa fa-bars'></i></a>
			      </li>
			      <li class='nav-item d-none d-sm-inline-block'>
			        <a href='index.php' class='nav-link' >Home</a>
			      </li>

			      </ul>
			      </nav>
			      <!-- Main Sidebar Container -->
			  <aside class='main-sidebar sidebar-dark-primary elevation-4'>
			    <!-- Brand Logo -->
			    <a href='index.php' class='brand-link'>
			      <img src='view/dist/img/AdminLTELogo.png' alt='Logo' class='brand-image img-circle elevation-3'
			           style='opacity: .8'>
			      <span class='brand-text font-weight-light'>Danzlife</span>
			    </a>

			    <!-- Sidebar -->
			    <div class='sidebar'>
			      <!-- Sidebar user panel (optional) -->
			      <div class='user-panel mt-3 pb-3 mb-3 d-flex'>";
      if(isset($_SESSION['login'])){
         if($_SESSION['login']){ //verificar que el usuario inicio sesion

         		echo "
			        <div class='info'>
			          <a href='#' class='d-block'>"; $this->mostrarInicioController(); echo "</a>
			        </div>
			      </div>

			      <!-- Sidebar Menu -->
			      <nav class='mt-2'>
			        <ul class='nav nav-pills nav-sidebar flex-column' data-widget='treeview' role='menu' data-accordion='false'>
			          <!-- Add icons to the links using the .nav-icon class
			               with font-awesome or any other icon font library -->
			          <li class='nav-item has-treeview menu-open'>
			           <ul class='nav nav-treeview'>
			              <li class='nav-item'>
			                <a href='index.php?action=registro_comprobante' class='nav-link'>
			                  <i class='nav-icon fa fa-check-circle'></i>
			                  <p>Registro de comprobante</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=grupos' class='nav-link'>
			                  <i class='nav-icon fa fa-group'></i>
			                  <p>Gestion de Grupos</p>
			                </a>
			              </li>
			              <li class='nav-item'>
                        <a href='index.php?action=alumnas' class='nav-link'>
                          <i class='nav-icon fa fa-user'></i>
                          <p>Gestion de Alumnas</p>
                        </a>
                      </li>
			              <li class='nav-item'>
			                <a href='index.php?action=pagos' class='nav-link'>
			                  <i class='nav-icon fa fa-money'></i>
			                  <p>Pagos</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=lugares' class='nav-link'>
			                  <i class='nav-icon fa fa-sort-amount-asc'></i>
			                  <p>Lugares</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=logout' class='nav-link'>
			                  <i class='nav-icon fa fa-sign-out'></i>
			                  <p>Logout</p>
			                </a>
			              </li>
							</ul>
			              </li>
			          </li>
			      </nav>
			      <!-- /.sidebar-menu -->
			    </div>
			    <!-- /.sidebar -->
			  </aside>
			    <!-- Content Wrapper. Contains page content -->
	  			<div class='content-wrapper'>
	            ";
	            echo "<div>";
         	}
         	
      }else{
         		echo "
			      <!-- Sidebar Menu -->
			      <nav class='mt-2'>
			        <ul class='nav nav-pills nav-sidebar flex-column' data-widget='treeview' role='menu' data-accordion='false'>
			          <!-- Add icons to the links using the .nav-icon class
			               with font-awesome or any other icon font library -->
			          <li class='nav-item has-treeview menu-open'>
			           <ul class='nav nav-treeview'>
			              <li class='nav-item'>
			                <a href='index.php?action=registro_comprobante' class='nav-link'>
			                  <i class='nav-icon fa fa-check-circle'></i>
			                  <p>Registro de comprobante</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=lugares' class='nav-link'>
			                  <i class='nav-icon fa fa-sort-amount-asc'></i>
			                  <p>Lugares</p>
			                </a>
			              </li>
			            </ul>
			          </li>
			          </ul>
			      </nav>
			      <!-- /.sidebar-menu -->
			    </div>
			    <!-- /.sidebar -->
			  </aside>
			    <!-- Content Wrapper. Contains page content -->
	  			<div class='content-wrapper'>
	            ";
	            echo "<div>";
      }
	}

}

  	//funcion encargada de ingresar los valores del login e iniciar sesion
	public function ingresoUsuarioController(){
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['btn_add'])){

			$resultado = Crud::ingresoUsuarioModel($_POST['username'], $_POST['password']); //se ejecuta la funcion del modelo
			//se verifica que lo retornado por el modelo no este vacio
			if(!empty($resultado)){
					$_SESSION['login']=true; //iniciar la variable de sesion login
					$_SESSION['user_info']= $resultado; //guardar los datos del usuario en una sesion
					echo "<script>window.location='index.php?action=menu';</script>";
			}else{
				//mostrar mensaje en caso de no existir el usuario
				echo "<script>swal('Usuario o contraseña incorrectos', 'No existe un usuario registrado con esas credenciales', 'error');</script>";
			}
		}
	}
	//funcion que imprime un mensaje en el inicio con el nombre del maestro tomado de la variable sesion
	public function mostrarInicioController(){
		if(isset($_SESSION['user_info'])){
			//$tienda = Crud::getRegModel($_SESSION['tienda'], "tiendas", $_SESSION['tienda']);
			echo "<i class='nav-icon fa fa-user'></i> [ ".$_SESSION['user_info']['user']." ]";
		}
	}

	


	//funcion encargada de crear una tabla con los grupos registrados en la base de datos
	public function getGruposController(){
		$informacion = Crud::vistaXTablaModel("grupos");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de los grupos
			foreach ($informacion as $row => $item) {
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['nombre']."</td>";
          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_grupo&id=".$item['id']."></a></td>";
				//mandar por propiedad onclick el id del elemento tag a para eleminarlo
				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=grupos&id=".$item['id']."'></a></td>";  
              
        echo "</tr>";
				
			}
		}
		
	}

	//funcion encargada de crear una tabla con las alumnas registradas en la base de datos
	public function getAlumnasController($requestForJSON){
		//Alumnas : nombre, apellidos, fecha nac, grupo
		$informacion = Crud::vistaXTablaModel("alumnas");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			if(empty($requestForJSON)){ //se verifica si hay que imprimir el resultado en un json_encodern(para el comprobante)
				//si el resultado no esta vacio, imprimir los datos de las alumnas
				foreach ($informacion as $row => $item) {
					$grupo = Crud::getRegModel($item['id_grupo'], "grupos"); //obtener la informacion del grupo de cada alumna para mostrarlo
					echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$item['nombre']."</td>";
					echo "<td>".$item['apellidos']."</td>";
					echo "<td>".$item['fecha_nacimiento']."</td>";
					echo "<td>".$grupo['nombre']."</td>";
	          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_alumna&id=".$item['id']."></a></td>";
					//mandar por propiedad onclick el id del elemento tag a para eleminarlo
					 echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=alumnas&id=".$item['id']."'></a></td>";  
	              
	        		echo "</tr>";
				}
			}else{
				$_SESSION['temp_alumnas'] = $informacion;
			}
			
		}
		
	}


	//funcion encargada de crear una tabla con los pagos registrados en la base de datos
	public function getPagosController(){
		//Alumnas : nombre, apellidos, fecha nac, grupo
		$informacion = Crud::vistaXTablaModel("pagos");//ejecucion del metodo del modelo
		if(!empty($informacion)){
				//imprimir los datos en forma de tabla
				foreach ($informacion as $row => $item) {
					$alumna = Crud::getRegModel($item['id_alumna'], "alumnas");
					$grupo = Crud::getRegModel($alumna['id_grupo'], "grupos"); //obtener la informacion del grupo de cada alumna para mostrarlo
					echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$grupo['nombre']."</td>";
					echo "<td>".$alumna['nombre'] . " " . $alumna["apellidos"]."</td>";
					echo "<td>".$item['nombre_mama']."</td>";
					echo "<td>".$item['fecha_pago']."</td>";
					echo "<td>".$item['fecha_envio']."</td>";
					//mostrar la imagen en una nueva ventana
					echo '<td><a href="'.$item["imagen_comprobante"].'" target="_blank">Ver</a></td>';
					echo "<td>".$item['folio']."</td>";
	          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_pago&id=".$item['id']."></a></td>";
					//mandar por propiedad onclick el id del elemento tag a para eleminarlo
					 echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=pagos&id=".$item['id']."'></a></td>";  
	              	if($item["aprobado"]=="0"){
	              		echo "<td>"."<a class='btn btn-success fa  fa fa-check-circle' id='aprobar".$item["id"]."' onclick='d(".$item["id"].");' href='index.php?action=aprobar_pago&id=".$item['id']."'></a></td>";  
	              	}else{
	              		echo "<td>Aprobado</td>";
	              	}
	              	
	        		echo "</tr>";
				}
		}
		
	}


	//funcion encargada de crear una tabla con los pagos registrados en la base de datos
	public function getPagosSortedController(){
		//Alumnas : nombre, apellidos, fecha nac, grupo
		$informacion = Crud::getPagosSortedByDate();//ejecucion del metodo del modelo
		if(!empty($informacion)){
				$i = 1;
				//imprimir los datos en forma de tabla
				foreach ($informacion as $row => $item) {
					$alumna = Crud::getRegModel($item['id_alumna'], "alumnas");
					$grupo = Crud::getRegModel($alumna['id_grupo'], "grupos"); //obtener la informacion del grupo de cada alumna para mostrarlo
					echo "<tr>";
					echo "<td>".$i."</td>";
					echo "<td>".$grupo['nombre']."</td>";
					echo "<td>".$alumna['nombre']. " " .$alumna["apellidos"] ."</td>";
					echo "<td>".$item['nombre_mama']."</td>";
					echo "<td>".$item['fecha_pago']."</td>";
					echo "<td>".$item['fecha_envio']."</td>";
					echo "<td>".$item['folio']."</td>";
	        		echo "</tr>";
	        		$i++;
				}
		}
		
	}





 
	//funcion que crea un select con las alumnas registradas
	public function getSelectForX($table, $firstID){
		$informacion = Crud::vistaXTablaModel($table); //se obtienen todos los registros de la tabla indicada en parametro al ejecutar el metodo del modelo
		if(!empty($informacion)){ //verificar que no este vacio el resultado
			if($firstID==""){//en caso de imprimir los elementos de forma normal
				foreach ($informacion as $row => $item) {
					echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}else{ //en caso de que la peticion sea para mostrar la info de una edicion de registro (tiene que aparecer al principio el id o elemento en el select indicado)
				//se obtiene la informacion del registro que aparecera al principio del select
				$reg = Crud::getRegModel($firstID, $table);
				//se coloca primero la opcion del select de la tabla indicada
				echo "<option value='".$reg['id']."'>".$reg['nombre']."</option>";
				foreach ($informacion as $row => $item) { //se imprimen los registros restantes
					if($item['id']!=$firstID)
						echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}
			
			
		}
	}


	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroGrupoController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre']
					);
			//peticion al modelo del reigstro del grupo mandando como param la informacion de este
			$registro = Crud::registroGrupoModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        			echo "<script>swal('Exito!','Grupo registrado','success');
       				 window.location='index.php?action=grupos';</script>";
			}else{
				echo "<script>swal('Error','Ocurrió un error al registrar','error');</script>";
			}
		}
	}


	//funcion encargada de verificar si se presiono un boton de registro en la pagina registroalumna, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroAlumnaController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre'],
						'apellidos'=> $_POST['apellidos'],
						'fecha_nacimiento'=> $_POST['fecha_nacimiento'],
						'id_grupo'=> $_POST['grupo']
					);
			//peticion al modelo del reigstro de la alumna mandando como param la informacion de este
			$registro = Crud::registroAlumnaModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        			echo "<script>swal('Exito!','Alumna registrada','success');
       				 window.location='index.php?action=alumnas';</script>";
			}else{
				echo "<script>swal('Error','Ocurrió un error al registrar','error');</script>";
			}
		}
	}



	//funcion encargada de, dado un id de un grupo, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getGrupoController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'grupos'); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo "
				<div class='form-group'>
                    <p>
                    <label>Id</label>
                    <input type='text' class='form-control' name='id' value='".$peticion['id']."' placeholder='' required='' readonly='true'>
                  </p>
                  </div>
                  <div class='form-group'>
                    <p>
                    <label>Nombre</label>
                    <input type='text' class='form-control' name='nombre' value='".$peticion['nombre']."' placeholder='Ingresa el nombre del grupo' required=''>
                  </p>
                  </div>
                  ";
		}
	}


	//funcion encargada de, dado un id de una alumna, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getAlumnaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'alumnas'); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo '
				<div class="form-group">
					<label>Id</label>
                    <input type="text" class="form-control" name="id" readonly="true" required="" value="'.$peticion['id'].'">
				</div>
				<div class="form-group">
                    <div class="row">
                      <div class="col-6">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la alumna" required="" value="'.$peticion['nombre'].'">
                      </div>
                      <div class="col-6">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" placeholder="Apellidos de la alumna" required="" value="'.$peticion['apellidos'].'">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-6">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" required="" value="'.$peticion['fecha_nacimiento'].'">
                      </div>
                      <div class="col-6">
                        <label>Grupo</label>
                        <select class="form-control select2" name="grupo" required="">
                          ';
                          $this->getSelectForX("grupos", $peticion['id_grupo']); 
                          echo '
                        </select>
                      </div>
                    </div>
                    </div>
			';
		}
	}



	//funcion encargada de, dado un id de un pago, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getPagoController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'pagos'); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			$_SESSION["alumna_temp"] = $peticion["id_alumna"];
			echo '<!-- left column -->
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
                    <label>Id</label>
                    <input type="text" class="form-control" name="id" placeholder="id" required="" value="'.$peticion["id"].'" readonly="true">
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Grupo</label>
                    <select class="form-control select2" name="grupo" id="grupo" required="" onchange="cambioGrupo();">
                      ';
                      $this->getSelectForX("grupos", $peticion['id_grupo']);
                    echo '</select>
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Alumna</label>
                    <select class="form-control select2" name="alumna" required="" id="select_alumnas">';
                      //$this->getSelectForX("alumnas", $peticion['id_alumna']);
                    $alumna = Crud::getRegModel($peticion['id_alumna'], "alumnas");
                    echo "<option value=".$alumna["id"].">".$alumna["nombre"] . " " . $alumna["apellidos"]."</option>";
                    echo '</select>
                  </p>
                  </div>
                  <div class="form-group">
                    <p>
                    <label>Datos de la mama</label>
                         <input type="text" class="form-control" name="nombre_mama" placeholder="Nombre" required="" value="'.$peticion["nombre_mama"].'" >
                   
                  </p>
                  </div>
                  <div class="form-group">
                    <label>Fecha de pago</label>
                         <input type="date" class="form-control" name="fecha" required="" value="'.$peticion["fecha_pago"].'">

                  </div>
                  <div class="form-group">
                    <label>Fecha de envio</label>
                         <input type="text" id="" class="form-control" name="fecha_envio" required="" value="'.$peticion["fecha_envio"].'">
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
                    <label>Comprobante</label><br><center>
                    ';
                    echo '<img src="'.$peticion['imagen_comprobante'].'" alt="Comprobante" height="300" width="300"></center>';
                    echo '

                  </div>    
                   
                  <div class="form-group">
                    <label>Folio</label>
                        <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Numero de folio</span>
                      </div>
                      <input type="number" step="1"  class="form-control" name="folio" required="" value="'.$peticion["folio"].'">
                      <div class="input-group-append">
                        <span class="input-group-text"></span>
                      </div>
                    </div>
                  </div>

                  <button type="submit" name="btn_actualizar" id="targ"  class="btn btn-success" onclick="c();" style="float:right;">Guardar cambios</button>
            </div>   
            </div>
            <!-- -->
            </div> 
			';
		}
	}





	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarGrupoController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"nombre"=>$_POST['nombre']
			);
			//se realiza la ejecucion del metodo que actualiza un grupo en el modelo, mandando los parametros correspondientes
			$peticion = Crud::actualizarGrupoModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=grupos';</script>";
        
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo de la tabla alumnas
	public function actualizarAlumnaController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"nombre"=>$_POST['nombre'],
				"apellidos"=>$_POST['apellidos'],
				"fecha_nacimiento"=>$_POST['fecha_nacimiento'],
				"id_grupo"=>$_POST['grupo'],
				"id"=>$_POST['id']
			);
			//se realiza la ejecucion del metodo que actualiza una alumna en el modelo, mandando los parametros correspondientes
			$peticion = Crud::actualizarAlumnaModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       		echo "<script>window.location='index.php?action=alumnas';</script>";
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualiza mediante la ejecucion del metodo del modelo de la tabla pagos
	public function actualizarPagoController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array('id_grupo'=> $_POST['grupo'],
						'id_alumna'=> $_POST['alumna'],
						'nombre_mama'=> $_POST['nombre_mama'],
						'folio'=> $_POST['folio'],
						'fecha_pago'=> $_POST['fecha'],
						'fecha_envio'=> $_POST['fecha_envio'],
						'id' => $_POST['id']

						);
			//se realiza la ejecucion del metodo que actualiza una alumna en el modelo, mandando los parametros correspondientes
			$peticion = Crud::actualizarPagoModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       		echo "<script>window.location='index.php?action=pagos';</script>";
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}






	//Funcion que dado un id y nombre de tabla, ejecuta el metodo del modelo y borra el registro especificado en base a la tabla
	public function borrarController($id, $tabla){
		//se ejecuta el metodo borrar del modelo mandando como paremtros los explicados anteriormente
		$peticion = Crud::borrarXModel($id, $tabla);
		if($peticion == "success"){ //verificar respuesta
			echo "<script>window.location='index.php?action=".$tabla."';</script>";
		}else{
			echo "<script>swal('Error', 'Ocurrio un error al dar de baja el registro', 'error');</script>";
		}
	}

	//Funcion que dado un id ejecuta el metodo del modelo y aprueba el pago ingresado
	public function aprobarPagoController($id){
		//se ejecuta el metodo desactivar del modelo mandando como paremtro el id de la tienda
		$peticion = Crud::aprobarPagoModel($id);
		if($peticion == "success"){ //verificar respuesta
		echo "<script>window.location='index.php?action=pagos';</script>";
		}else{
			echo "<script>swal('Error', 'Ocurrio un error al aprobar el pago', 'error');</script>";
		}
	}



}





?>