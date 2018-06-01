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
			$enlace = 'index';
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
          			echo "<script>window.location='index.php?action=login';</script>";  
          			//header("Location: index.php?")
          			return false;
            	}else{
            		return true;
            	}
      }else{
        echo "<script>window.location='index.php?action=login';</script>"; 
        return false;
      }
		}else{
			echo "<script>window.location='index.php?action=login';</script>";
			return false;
		}
	}
  
  //metodo especifico para el archivo header.php o navegacion, el cual verifica si el usuario esta logueado, entonces muestra el menu
 public function showNav(){
    if(isset($_SESSION)){
      if(isset($_SESSION['login'])){
         if($_SESSION['login']){
            //verificar si el usurio esta logueado se imprime el menu
            echo "
		            <!-- Navbar -->
		  <nav class='main-header navbar navbar-expand bg-white navbar-light border-bottom'>
		    <!-- Left navbar links -->
		    <ul class='navbar-nav'>
		      <li class='nav-item'>
		        <a class='nav-link' data-widget='pushmenu'><i class='fa fa-bars'></i></a>
		      </li>
		      <li class='nav-item d-none d-sm-inline-block'>
		        <a href='index.php' class='nav-link'>Home</a>
		      </li>
		      <li class='nav-item d-none d-sm-inline-block'>
		        <a href='index.php?action=logout' class='nav-link'>Logout</a>
		      </li>
		    </ul>
		    
		  </nav>
		  <!-- Main Sidebar Container -->
		  <aside class='main-sidebar sidebar-dark-primary elevation-4'>
		    <!-- Brand Logo -->
		    <a href='index.php' class='brand-link'>
		      <img src='view/dist/img/AdminLTELogo.png' alt='Logo' class='brand-image img-circle elevation-3'
		           style='opacity: .8'>
		      <span class='brand-text font-weight-light'>Inventarios</span>
		    </a>

		    <!-- Sidebar -->
		    <div class='sidebar'>
		      <!-- Sidebar user panel (optional) -->
		      <div class='user-panel mt-3 pb-3 mb-3 d-flex'>
		        <div class='image'>
		          <img src='view/dist/img/boxed-bg.jpg' class='img-circle elevation-2' alt='User Image'>
		        </div>
		        <div class='info'>
		          <a href='#' class='d-block'>"; $control = new MVC(); $control->mostrarInicioController(); echo "</a>
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
		                <a href='index.php' class='nav-link'>
		                  <i class='nav-icon fa fa-dashboard'></i>
		                  <p>Dashboard</p>
		                </a>
		              </li>
		              <li class='nav-item'>
		                <a href='index.php?action=categorias' class='nav-link'>
		                  <i class='nav-icon fa fa-edit'></i>
		                  <p>Gestion de Categorias</p>
		                </a>
		              </li>
		              <li class='nav-item'>
		                <a href='index.php?action=productos' class='nav-link'>
		                  <i class='nav-icon fa fa-tree'></i>
		                  <p>Gestion de Productos</p>
		                </a>
		              </li>
		              <li class='nav-item'>
		                <a href='index.php?action=usuarios' class='nav-link'>
		                  <i class='fa fa-circle-o nav-icon'></i>
		                  <p>Gestion de Usuarios</p>
		                </a>
		              </li>
		            </ul>
		          </li>
		          
		      </nav>
		      <!-- /.sidebar-menu -->
		    </div>
		    <!-- /.sidebar -->
		  </aside>
		    <!-- Content Wrapper. Contains page content -->
  <div class='content-wrapper'>
            ";
        }else{
        	echo "<div>";
        }
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
				$_SESSION['user_info']= $resultado; //guardar los datos del maestro en una sesion
				echo "<script>window.location='index.php';</script>";
			}else{
				//mostrar mensaje en caso de no existir el usuario
				echo "<script>alert('Username o password incorrectos');</script>";
			}
		}
	}
	//funcion que imprime un mensaje en el inicio con el nombre del maestro tomado de la variable sesion
	public function mostrarInicioController(){
		if(isset($_SESSION['user_info'])){
			echo "<center>".$_SESSION['user_info']['user']."</center>";
		}
	}

	//funcion encargada de crear una tabla con los productos registrados en la base de datos
	public function getProductosController(){
		$informacion = Crud::vistaXTablaModel("productos");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de los productos
			foreach ($informacion as $row => $item) {
				$categoria = Crud::getRegModel($item['id_categoria'], "categorias");
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['codigo']."</td>";
				echo "<td>".$item['nombre']."</td>";
				echo "<td>".$item['descripcion']."</td>";
				echo "<td>".$item['precio_unitario']."</td>";
				echo "<td>".$categoria['nombre']."</td>";
				echo "<td>".$item['fecha_registro']."</td>";
				echo "<td>".$item['stock']."</td>";
          		echo "<td>"."<a class='btn btn-secondary' href=index.php?action=editar_producto&id=".$item['id'].">Modificar</a></td>";
				echo "<td>"."<a class='btn btn-danger' href=index.php?action=borrar&tipo=productos&id=".$item['id']." class='button radius tiny warning' onclick='confirmar();'>Borrar</a></td>";
        echo "</tr>";
				
			}
		}
		
	}
	//funcion encargada de crear una tabla con las categorias registrados en la base de datos
	public function getCategoriasController(){
		global $in;
		//imprimir la varable que trae los datos del usuario
		
		$informacion = Crud::vistaXTablaModel("categorias");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de las categorias
			foreach ($informacion as $row => $item) {
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['nombre']."</td>";
          		echo "<td>"."<a class='btn btn-secondary' href=index.php?action=editar_categoria&id=".$item['id'].">Modificar</a></td>";
				echo "<td>"."<a class='btn btn-danger' href=index.php?action=borrar&tipo=categorias&id=".$item['id']." class='button radius tiny warning' onclick='confirmar();'>Borrar</a></td>";
        echo "</tr>";
				
			}
		}
		
	}

	//funcion encargada de crear una tabla con los maestros registrados en la base de datos
	public function getMaestrosController($flag){
		$informacion = Crud::vistaXTablaModel("maestros"); //se ejecuta el metodo de la clase crud del modelo para obtener los registros de la bd 
		if(!empty($informacion)){
			//si no esta vacio el resultado se imprimen los datos de cada maestro en una tabla
			foreach ($informacion as $row => $item) {
				$carrera = Crud::getRegModel($item['carrera'],"carreras");
				echo "<tr>";
				echo "<td>".$item['numero_empleado']."</td>";
				echo "<td>".$item['nombre']."</td>";
				echo "<td>".$carrera['nombre']."</td>";
				echo "<td>".$item['email']."</td>";
        if($item["superadmin"]==1) //se verifica de que tipo es para mostrarlo de forma textual
          echo "<td>Superadmin</td>";
        else
          echo "<td>Maestro</td>";
				if(empty($flag)){//se verifica si el param flag esta vacio, de ser asi se imprimen los botones de editar y borrar
           echo "<td>"."<a href=index.php?action=editar_maestro&numero_empleado=".$item['numero_empleado']." class='button radius tiny secondary'>Ver detalles</a></td>";
				echo "<td>"."<a href=index.php?action=borrar&tipo=maestros&numero_empleado=".$item['numero_empleado']." class='button radius tiny warning' onclick='confirmar();'>Borrar</a></td>";
        }
        echo "</tr>";
       
			}
		}
	}
  
  //funcion encargada de crear una tabla (en html) con las sesiones de tutorias registradas del maestro que tiene la sesion iniciada  o tdas las sesiones de tutorias registradas (dependiendo del parametro flag)
  public function getTutoriasMaestros($flag){
		$informacion = Crud::vistaXTablaModel("sesion_tutoria"); //obtener la tabla completa de la bd mediante la conexion con el modelo
		if(!empty($informacion)){
			foreach ($informacion as $row => $item) {
        if(!empty($flag)){ //en caso de no estar vacio el parametro flag se imprimen todas las sesiones de tutorias
          $maestro = Crud::getRegModel($item['maestro'],"maestros"); //obtneer el registro del maestro para mostrar su nombre
          //mostrar la informacion del maestro
          echo "<tr>";
          echo "<td>".$item['id']."</td>";
          echo "<td>".$maestro['nombre']."</td>";
          echo "<td>".$item['fecha']."</td>";
          echo "<td>".$item['hora']."</td>";
          echo "<td>".$item['tipo_tutoria']."</td>";
            echo "<td>".$item['tutoria_informacion']."</td>";
         echo "</tr>";
        }else if($item['maestro'] == $_SESSION['maestro_info']['numero_empleado']){ //solo se imprimen las sesiones de tutorias del maestro que tiene la sesion iniciada
        $maestro = Crud::getRegModel($item['maestro'],"maestros"); //se obtiene la info del maestro
        	//se imprimen los datos de la tutoria
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$maestro['nombre']."</td>";
				echo "<td>".$item['fecha']."</td>";
        echo "<td>".$item['hora']."</td>";
        echo "<td>".$item['tipo_tutoria']."</td>";
          echo "<td>".$item['tutoria_informacion']."</td>";
				echo "<td>"."<a href=index.php?action=ver_detalles&id=".$item['id']." class='button radius tiny'>Ver detalles</a></td>";
        
          echo "<td>"."<a href=index.php?action=borrar&tipo=sesion_tutoria&id=".$item['id']." class='button radius tiny warning' onclick='confirmar();'>Borrar</a></td>";
        echo "</tr>";
        }
				
			}
		}
	}

	//funcion encargada de crear una tabla en HTML con las carreras registrados en la base de datos
	public function getCarrerasController(){
		$informacion = Crud::vistaXTablaModel("carreras"); //peticion al modelo y guardado del array assoc contenedor de las carreras registradas
		if(!empty($informacion)){
			//impresion del contenido de la tabla de la bd, y mostrarlo en la vista a manera de tabla
			foreach ($informacion as $row => $item) {
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['nombre']."</td>";
				echo "<td>"."<a href=index.php?action=editar_carrera&id=".$item['id']." class='button radius tiny secondary'>Ver detalles</a></td>";
				echo "<td>"."<a href=index.php?action=borrar&tipo=carreras&id=".$item['id']." class='button radius tiny warning' onclick='confirmar();'>Borrar</a></td>";
        echo "</tr>";
			}
		}
		
	}

	//funcion que crea un select con las categorias registradas
	public function getSelectForCategorias($firstID){
		$informacion = Crud::vistaXTablaModel("categorias"); //se obtienen todos las categorias de la bd mediante la conexion al modelo
		if(!empty($informacion)){
			if($firstID==""){
				foreach ($informacion as $row => $item) {
					echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}else{
				//se obtiene la informacion del maestro en base al parametro firstID
				$categoria = Crud::getRegModel($firstID, "categorias");
				//se coloca primero la opcion del select del maestro
				echo "<option value='".$categoria['id']."'>".$categoria['nombre']."</option>";
				foreach ($informacion as $row => $item) { //se imprimen los maestros restantes
					if($item['id']!=$firstID)
						echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}
			
			
		}
	}

	//funcion que crea un select con los maestros registrados
	public function getSelectForMaestros($firstID){
		$informacion = Crud::vistaXTablaModel("maestros"); //se obtienen todos los maestros de la bd mediante la conexion al modelo
		if(!empty($informacion)){
			if($firstID == ""){ //se verifica que si esta vacio el parametro, se imprimen en cualquier orden los maestros en el select
				foreach ($informacion as $row => $item) {
					echo "<option value='".$item['numero_empleado']."'>".$item['nombre']."</option>";
				}
			}else{
				//se obtiene la informacion del maestro en base al parametro firstID
				$maestro = Crud::getRegModel($firstID, "maestros");
				//se coloca primero la opcion del select del maestro
				echo "<option value='".$maestro['numero_empleado']."'>".$maestro['nombre']."</option>";
				foreach ($informacion as $row => $item) { //se imprimen los maestros restantes
					if($item['numero_empleado']!=$firstID)
						echo "<option value='".$item['numero_empleado']."'>".$item['nombre']."</option>";
				}
			}
			
		}
	}

	//funcion que crea un select con las carreras registrados
	public function getSelectForCarreras($index){
		$informacion = Crud::vistaXTablaModel("carreras"); //se obtiene el contenido de la tabla de carreras de la bd mediante la conexion con el modeloy  la ejecucion del metodo vistaXTablaModel pasando como parametro el nombre de la tabla
		if(!empty($informacion)){
			if($index==""){ //si el parametro indice esta vacio se imprimen en cualquier orden las opciones del select
				foreach ($informacion as $row => $item) {
					echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}else{
				//en caso de que contenga el indice, se pone primero la carrera con el indice especificado
				$carrera = Crud::getRegModel($index, "carreras");
				echo "<option value='".$carrera['id']."'>".$carrera['nombre']."</option>";
				foreach ($informacion as $row => $item) { //se muesran las carreras restantes
					if($item['id']!=$index){
						echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
					}
				}
			}
			
		}

	}

//funcion que crea un select con los tutorados (alumnos) del maestro con la sesion iniciada
  public function getSelectForMaestrosTutoria($numero_maestro){
		$informacion = Crud::vistaXTablaModel("alumnos"); //se obtiene la vista completa de los alumnos
		if(!empty($informacion)){
				foreach ($informacion as $row => $item) {
		          if($item['tutor'] == $numero_maestro){ //se filtra si el id de tutor es igual al numero de empleado del maestro con la sesion iniciada
		          	//se muestra la informacion del alumno en el select para un filtrado con el select2
		            $carrera_alumno = Crud::getRegModel($item['carrera'], "carreras");
		            echo "<option value='".$item['matricula']."'>".$item['matricula']. " | ". $item['nombre'] . " | ".$carrera_alumno['nombre'] . "</option>";
		          }
			}
		}else{
        	echo "<center><h3>No tiene asignado ningun alumno</h3></center>";
    	}
	}

	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroProductoController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre'],
						'descripcion'=> $_POST['descripcion'],
						'precio_unitario'=> $_POST['precio'],
						'stock'=> $_POST['stock'],
						'codigo'=> $_POST['codigo'],
						'fecha'=> date("Y-m-d"),
						'categoria'=> $_POST['categoria'],
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroProductoModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
				echo "<script>window.location='index.php?action=productos';</script>";
			}else{
				echo "<script>alert('Error al registrar el producto')</script>";
			}
		}
	}



	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroCategoriaController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroCategoriaModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
				echo "<script>window.location='index.php?action=categorias';</script>";
			}else{
				echo "<script>alert('Error al registrar la categoria')</script>";
			}
		}
	}


	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo (registro de maestros)
	public function registroMaestroController(){
		if(isset($_POST['btn_agregar'])){ //se verifica si se dio el clic 
			//se crea el array asociativo con los datos a registrar
			$data = array('numero_empleado'=> $_POST['numero_empleado'],
						'nombre'=> $_POST['nombre'],
						'carrera'=> $_POST['carrera'],
						'correo'=> $_POST['correo'],
						'password'=> $_POST['password'],
            'superadmin'=> $_POST['tipo']
					);
			//se realiza el registro ejecutando el metodo de registro de maestro del modelo mandando como param el array asociativo de los datos
			$registro = Crud::registroMaestroModel($data);
			if($registro == "success"){ //verificar respuesta del modelo
				echo "<script>window.location='index.php?action=maestros';</script>";
			}else{
				echo "<script>alert('Error al registrar... Verifica que el numero de empleado que ingresaste no pertenezca a otro usuario o la conexion con la base de datos')</script>";
			}
		}
	}
  //funcion que registra una sesion de tutoria ya sea individual o grupal
  public function registroTutoriaController(){

		if(isset($_POST['btn_agregar'])){
      $fecha = date('Y-m-d', strtotime($_POST['fecha'])); //tomar del servidor la fecha
      
      
      $i=0;
      //contar el numero de alumnos para saber de que tipo es la tutoria
      while(!empty($_POST["matricula".$i])){
        $i++;
      }
      //verificar que tipo de tutoria fue
      if($i > 1){
        $tipo = "Grupal";
      }else{
        $tipo = "Individual";
      }
      //registrar tutoria
      //lenado de datos
       $data = array(
						'fecha'=> $fecha,
             'hora'=> $_POST['hora'],
              'maestro'=> $_POST['maestro'],
              'info'=> $_POST['tutoria_informacion'],
              'tipo'=> $tipo
					);
      $registro = Crud::registroTutoriaModel($data);
      if($registro == "success"){
				//Tutoria registrada
	        //Se procede a obtener el id de la tutoria ingresada
	        $lastID = Crud::returnLastTutoria();
	        
	        //crear clase del modelo para posteriormente insertar
	        //Se procede a registrar cada alumno de la tutoria en la tabla tutoria_alumnos
	        //iterar para obtener los datos de las cajas de texto (obtener los alumnos)
	        for($j=0; $j < $i; $j++){
	          //guardar los datos en array
	          $alumno_data = array(
							'matricula_alumno'=> $_POST["matricula".$j],
	             'id_tutoria'=> $lastID[0]
						);
	          //registrar alumno en la bd
	          Crud::registroAlumnoTutoria($alumno_data);
	        }
        	echo "<script>window.location.assign('index.php?action=sesion_tutoria');</script>";
		}else{
				echo "<script>alert('Error al registrar la sesion de tutoria')</script>";
			}
      
		}
	}

	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo (registro de carrera)
	public function registroCarreraController(){
		if(isset($_POST['btn_agregar'])){ //verificacion del clic en el boton
			//creacion del array con los datos del control
			$data = array('nombre'=> $_POST['nombre']
					);
			//registro mediante la ejecucion del metodo del modelo 
			$registro = Crud::registroCarreraModel($data);
			if($registro == "success"){ //verificacion de la respuesta del modelo
				echo "<script>window.location='index.php?action=carreras';</script>";
			}else{
				echo "<script>alert('Error al registrar')</script>";
			}
		}
	}

	//funcion encargada de, dado un id de un producto, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getProductoController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'productos'); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo "
				<div class='form-group'>
                    <p>
                    <label>Id</label>
                    <input type='text' class='form-control' name='id' value='".$peticion['id']."' placeholder='Ingresa el codigo del producto' required='' readonly='true'>
                  </p>
                  </div>
				<div class='form-group'>
                    <p>
                    <label>Codigo</label>
                    <input type='text' class='form-control' name='codigo' value='".$peticion['codigo']."' placeholder='Ingresa el codigo del producto' required=''>
                  </p>
                  </div>
                  <div class='form-group'>
                    <p>
                    <label>Nombre</label>
                    <input type='text' class='form-control' name='nombre' value='".$peticion['nombre']."' placeholder='Ingresa el nombre del producto' required=''>
                  </p>
                  </div>
                  <div class='form-group'>
                    <p>
                    <label>Descripcion</label>
                    <input type='text' class='form-control' name='descripcion' value='".$peticion['descripcion']."'  placeholder='Ingresa la descripcion del producto' required=''>
                  </p>
                  </div>
                  <div class='form-group'>
                    <p>
                    <label>Categoria</label>
                    <select class='form-control select2' name='categoria'>";
                      $this->getSelectForCategorias($peticion['id_categoria']);
                    echo "</select>
                  </p>
                  </div>
                  <div class='form-group'>
                    <label>Precio unitario</label>
                        <div class='input-group'>
                      <div class='input-group-prepend'>
                        <span class='input-group-text'>$</span>
                      </div>
                      <input type='number' value='".$peticion['precio_unitario']."'  step='0.0000001'  class='form-control' name='precio' required=''>
                      <div class='input-group-append'>
                        <span class='input-group-text'></span>
                      </div>
                    </div>
                  </div>
                  <div class='form-group'>
                    <label>Stock</label>
                        <div class='input-group'>
                      <div class='input-group-prepend'>
                        <span class='input-group-text'>Unidades</span>
                      </div>
                      <input type='number' step='1' value='".$peticion['stock']."' class='form-control' name='stock' required=''>
                      <div class='input-group-append'>
                        <span class='input-group-text'></span>
                      </div>
                    </div>
                  </div>
                  ";
		}
	}


	//funcion encargada de, dado un id de una categoria, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getCategoriaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'categorias'); //peticion al modelo del registro especificado por el id
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
                    <input type='text' class='form-control' name='nombre' value='".$peticion['nombre']."' placeholder='Ingresa el nombre de la categoria' required=''>
                  </p>
                  </div>
                  ";
		}
	}




	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarProductoController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"id"=>$_POST['id'],
				"codigo"=>$_POST['codigo'],
				"nombre"=>$_POST['nombre'],
				"descripcion"=>$_POST['descripcion'],
				"precio_unitario"=>$_POST['precio'],
				"stock"=>$_POST['stock'],
				"categoria"=>$_POST['categoria'],
			);

			//se realiza la ejecucion del metodo que actualiza un alumno en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarProductoModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=productos';</script>";
        
			}else{
				echo "<script>alert('Error al actualizar')</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarCategoriaController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"nombre"=>$_POST['nombre']
			);

			//se realiza la ejecucion del metodo que actualiza un alumno en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarCategoriaModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=categorias';</script>";
        
			}else{
				echo "<script>alert('Error al actualizar')</script>";
			}
		}
	}


	//funcion encargada de, dado un numero de empleado de un maestro, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getMaestroController(){
		$id = (isset($_GET['numero_empleado'])) ? $_GET['numero_empleado'] : ""; //guardado del numero de empleado del metodo post
		$peticion = Crud::getRegModel($id, 'maestros'); //se obtienen los datos del registro del maestro
		if(!empty($peticion)){
			//mostrado de controles con los datos del maestro para su posterior edicion
			echo "<p>
              <label>Numero de Empleado</label>
              <input type='text' name='numero_empleado' placeholder='Numero de Empleado' required='' value='".$peticion['numero_empleado']."' readonly='true'>
            </p>";
            echo "<p>
              <label>Nombre</label>
              <input type='text' name='nombre' placeholder='Nombre' required='' value='".$peticion['nombre']."'>
            </p>";
            echo "<p>
              <label>Carrera</label>
              <select name='carrera' required='' class='select2'>
                  ";
            $this->getSelectForCarreras($peticion['carrera']);
            echo "
              </select>
            </p>";
            echo "<p>
              <label>Email</label>
              <input type='text' name='correo' placeholder='Correo' required='' value='".$peticion['email']."'>
            </p>";
            echo "<p>
              <label>Password</label>
              <input type='text' name='password' placeholder='Password' required='' value='".$peticion['password']."'>
            </p>";
            echo "<select name='tipo'>";
            if($peticion["superadmin"] == 1){
              echo "<option value='1'>Superadmin</option>
              <option value='0'>Maestro</option>";
            }else{
              echo "<option value='0'>Maestro</option>
              <option value='1'>Superadmin</option>";
            }
            echo "</select>";
             
           
		}else{
			echo "<script>window.location='index.php?action=maestros';</script>";
		}
	}

	//funcion encargada de, dado un id de una carrera, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getCarreraController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //obtencion del id de la carrera del metodo get
		$peticion = Crud::getRegModel($id, 'carreras'); //se obtiene la informacion del registro de la carrera
		if(!empty($peticion)){
			//se muestran los controles con los datos de la carrera especificada
			echo "<p>
              <label>Id</label>
              <input type='text' name='id' placeholder='Id' required='' value='".$peticion['id']."' readonly='true'>
            </p>";
            echo "<p>
              <label>Nombre</label>
              <input type='text' name='nombre' placeholder='Nombre' required='' value='".$peticion['nombre']."'>
            </p>";
		}else{
			echo "<script>window.location='index.php?action=carreras';</script>";
		}
	}
  
  //funcion encargada de, dado un id de una sesion de tutoria, se obtienen los datos de la base de datos y se imprimen los detalles de esta en una tabla
  public function getTutoriaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //se obtiene el id de la tutoria
		$peticion = Crud::vistaXTablaModel('tutoria_alumnos'); //se obtiene una vista de la tabla tutoria_alumnos para filtrarla por el id de tutoria especificado
    $tutoria = Crud::getRegModel($id, 'sesion_tutoria'); //se obtiene la informacion del registro
    //impresion de la informacion a manera de tabla
    echo "
    <table width='100%'>
      <thead>
        <tr>
          <td>Id de tutoria</td>
          
          <td>Tipo</td>

          <td>Hora</td>
          
          <td>Tema(as)</td>
        </tr>
      </thead>
      
      <tbody>
      <td>".$tutoria['id']."</td>
      <td>".$tutoria['tipo_tutoria']."</td>
      <td>".$tutoria['hora']." hrs</td>
      <td>".$tutoria['tutoria_informacion']."</td>
      
      </tbody>
    
    </table>
    
    ";
		if(!empty($peticion)){
      echo "
    <table width='100%'>
      <thead>
        <tr>
          <td>Nombre del alumno</td>
          
          <td>Carrera</td>
          
        </tr>
      </thead>";
      //impresion de la informacion de los alumnos adjuntados al id de la tutoria espeicifcado
      foreach($peticion as $row => $item){
        if($item["tutoria"]==$tutoria["id"]){
          $alumno = Crud::getRegModel($item["matricula"], "alumnos");
          $carrera = Crud::getRegModel($alumno["carrera"], "carreras");
          echo "<tr>";
          echo "<td>".$alumno['nombre']."</td>";
          echo "<td>".$carrera['nombre']."</td>";
          echo "</tr>";
        }
      }
      
    echo "</table>";
		}else{
			echo "<script>window.location='index.php?action=sesion_tutoria';</script>";
		}
	}


	//funcion que muestra el dashboard del sistema
	function showDashboard(){
		//mostrar productos sin stock
		$this->setQueryController("productos","Productos sin stock", "stock", "=",0);

		$this->setQueryController("transaccion","Transacciones realizadas hoy", "fecha", "=", date('Y-m-d'));
		

	}

	//funcion setQueryController, dado un nombre de tabla, un titulo, un campo, un operador y un valor, se ejecuta un metodo de la clase modelo (Crud) el cual, pasando como parametro los valores mencionados, obtiene los registos que cumplan con estas coincidencias en la tabla seleccionada y crea una tabla con esta informacion
	function setQueryController($table, $title, $field, $operator, $equals){
		$peticion = Crud::getQueryFromX($table, $field, $operator,$equals); //peticion al modelo
		//se imprime la tabla con informacion
		echo "<div class='form-group'>
					<div class='card-header'>
                        <h3 class='card-title'>$title (".count($peticion).")</h3>
                      </div>
                    <div class='card'>";
		echo "<table width='100%' class='table table-bordered table-striped'>";
			echo "<thead>";
				echo "<tr>";
					if($table=="transaccion"){
						echo "<th>Productos</th>";
						echo "<th>Cantidad</th>";
						echo "<th>Tipo de transaccion</th>";
						echo "<th>Fecha</th>";
						echo "<th>Realizada por</th>";
					}else{
						echo "<th>$title: ".count($peticion)."</th>";
						echo "<th>$field</th>";
					}
					
				echo "</tr>";
			echo "</thead>";
		echo "<tbody>";
		if(!empty($peticion)){ //se verifica que la variable peticion no este vacia
			echo "<tbody>";
			foreach ($peticion as $row => $item) {
				echo "<tr>";
				if($table == "transaccion"){
					$producto = Crud::getRegModel($item['id_producto'],"productos");
					echo "<td>".$producto['nombre']."</td>";
					echo "<td>".$item["cantidad"]."</td>";
					echo "<td>".$item["tipo"]."</td>";
					echo "<td>".$item["fecha"]."</td>";
					$usuario = Crud::getRegModel($item['id_usuario'],"usuarios");
					echo "<td>".$usuario["user"]."</td>";
				}else{
					echo "<td>".$item['nombre']."</td>";
					echo "<td>".$item[$field]."</td>";
				}
				
				echo "</tr>";
      		}
      		
		}
		echo "</tbody>";
      		echo "</table>";
      		echo "</div>";
      		echo "</div>";
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo (actualizacion de maestro)
	public function actualizarMaestroController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion del clic en actualizar
			//tomar los datos de los controles y guardarlo en un array
			$data = array(
				"nombre"=>$_POST['nombre'],
				"carrera"=>$_POST['carrera'],
				"correo"=>$_POST['correo'],
				"password"=>$_POST['password'],
        "superadmin"=>$_POST['tipo']
			);
			//realizar la actualizacion ejecutando uel metodo de actualizar maestro en el modelo, mandando como parametros los datos y el numero de empleado
			$peticion = Crud::actualizarMaestroModel($data, $_POST['numero_empleado']);
			if($peticion == "success"){ //verificar respuesta del modelo
				echo "<script>window.location='index.php?action=maestros';</script>";
			}else{
				echo "<script>alert('Error al actualizar')</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo (actualizacion de carrera)
	public function actualizarCarreraController(){
		if(isset($_POST['btn_actualizar'])){ //verificar si se dio clic en el boton actualizar
			//crear array con los datos de la carrera a actualizar
			$data = array(
				"nombre"=>$_POST['nombre']
			);
			//realizar la actualizacion ejecutando el metodo carrera del modelo mandando como parametros el array de datos y el id de la carrera actualizar
			$peticion = Crud::actualizarCarreraModel($data, $_POST['id']);
			if($peticion == "success"){ //revisar la respuesta del modelo
				echo "<script>window.location='index.php?action=carreras';</script>";
			}else{
				echo "<script>alert('Error al actualizar')</script>";
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
			echo "<script>alert('Error al borrar')</script>";
		}
	}

	//Funcion encargada de verificar si se dio clic en el boton de filtrar y en base al valor del select, se realiza la vista de la tabla seleccionada
  public function verReporteController(){
    if(isset($_POST["btn_filtrar"])){ //verificar clic enboton filtrar
          if($_POST["query"]=="alumnos"){  //si se selecciona alumnos en el select
          	//crear la tabla de la vista de los alumnos
             echo "<center><h3>Reporte de alumnos</h3></center><table id='dt' class='display' style='width:100%'>
            <thead>
              <td>Matricula</td>
              <td>Nombre</td>
              <td>Carrera</td>
              <td>Tutor</td>
            </thead>
            <tbody>";
            	//se llama el metodo de esta funcion que trae los alumnos y se manda un parametro cualquiera para que no imprima los botones
                   $this->getAlumnosController("qwerty");
            echo "</tbody>
          </table>";
          }else if($_POST["query"]=="maestros"){ //si se selecciona maestros
            echo "<center><h3>Reporte de maestros</h3></center><table id='dt' class='display' style='width:100%'>
            <thead>
              <td>Numero Empleado</td>
              <td>Nombre</td>
              <td>Carrera</td>
              <td>Correo</td>
              <td>Tipo de usuario</td>
            </thead>
            <tbody>";

            	//se llama el metodo de esta funcion que trae los maestros y se manda un parametro cualquiera para que no imprima los botones
                     $this->getMaestrosController("qwerty");
            echo "</tbody>
          </table>";
            
          }else if($_POST["query"] == "sesion_tutoria"){//si se selecciona sesion_tutoria
            echo " <center><h3>Reporte de Sesiones de Tutoria</h3></center>
            <table id='dt' class='display' style='width:100%'>
            <thead>
              <td>Id</td>
              <td>Tutor</td>
              <td>Fecha</td>
              <td>Hora</td>
              <td>Tipo de tutoria</td>
              <td>Tema</td>
            </thead>
            <tbody>";

            	//se llama el metodo de esta funcion que trae las tutorias registradas y se manda un parametro cualquiera para que no imprima los botones
                            $this->getTutoriasMaestros("qwerty");
            echo "</tbody>
          </table>";

          }
        }
  }



}





?>