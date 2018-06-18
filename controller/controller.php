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

   
    if(isset($_SESSION)){
      if(isset($_SESSION["user_info"])){
        if(isset($_SESSION["tienda"])){
           //Condicionar si el usuario en sesion es tipo ROOT y la tienda en la que se encuentra es la ROOT (id 1) entonces solo redireccionar a GESTION DE TIENDAS
          if($_SESSION["user_info"]["tipo_usuario"] == "1" && $_SESSION["tienda"] == "1"){
            
             
             if($enlace != "ingresar_tienda" && $enlace != "borrar" &&  $enlace != "activar_tienda" &&  $enlace != "desactivar_tienda" && $enlace != "editar_tienda" && $enlace != "logout" && $enlace != "registro_tienda") { //sin embargo puede redireccionar a ingreso de tienda para poder ingresar a una
                $enlace = "tiendas";
             }
            
            
            
          }
          //Sabiendo que el usuario ROOT puede tener acceso a editar_tienda y a borrar ; se debe prevenir que no intente editar o borrar
            //por url la tienda root
            $idX = (isset($_GET["id"])) ? $_GET["id"] : ""; //en caso de existir la variable id del metodo GET guardarla
            $tipoOTabla = (isset($_GET["tipo"])) ? $_GET["tipo"] : ""; //en caso de existir la variable tipoOTabla del metodo GET guardarla
          //verificar que al borrar algo no sea la tienda ROOT o base, o que intenten modificarla
          if($enlace == "borrar" || $enlace == "editar_tienda"){
            
            
            if($enlace== "borrar"){
              if($tipoOTabla == "tiendas" && $idX == "1"){ //si intentan borrar la tienda 1 se cancele y redireccione al index
                $enlace = "tiendas"; //redireccionar al index
              }
              if($_SESSION["user_info"]["tipo_usuario"] != "1" && $tipoOTabla == "tiendas"){ //si el usuario no es root e intenta borrar una tienda, redireccionar al index
                $enlace = "index";
              }
            }else{
              if($idX == "1"){//si se quiere editar tienda
                $enlace = "tiendas"; //redireccionar al index;
             }
         
            }
       
          }
          if( $enlace == "desactivar_tienda" && $idX == "1"){
          	$enlace = "tiendas";
          }


          //condicionar que si el usuario no es root e intenta acceder a gestion de tiendas; redireccionar al index
          if($_SESSION["user_info"]["tipo_usuario"] != "1" && ($enlace == "tiendas" || $enlace == "editar_tienda" || $enlace == "registro_tienda" || $enlace == "ingresar_tienda" || $enlace == "desactivar_tienda" || $enlace == "activar_tienda")){
            $enlace = "index";//redireccionar al index
          }

          //evitar que el usuario vea la pagina de login si ya inicio sesion
          if($enlace == "login"){
          	$enlace = "index";
          }
        }
        
      }
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
          			//return false;
            	}
      }else{
        echo "<script>window.location='index.php?action=login';</script>"; 
        //return false;
      }
		}else{
			echo "<script>window.location='index.php?action=login';</script>";
			//return false;
		}
	}
  
  
  
  
  
  //metodo especifico para el archivo header.php o navegacion, el cual verifica si el usuario esta logueado, entonces muestra el menu
 public function showNav(){
    if(isset($_SESSION)){
      if(isset($_SESSION['login'])){
         if($_SESSION['login']){ //verificar que el usuario inicio sesion
         	echo "			   <!-- Navbar -->
			  <nav class='main-header navbar navbar-expand bg-white navbar-light border-bottom'>
			    <!-- Left navbar links -->
			    <ul class='navbar-nav'>
			      <li class='nav-item'>
			        <a class='nav-link' data-widget='pushmenu' href='#'><i class='fa fa-bars'></i></a>
			      </li>
			      <li class='nav-item d-none d-sm-inline-block'>
			        <a href='index.php' class='nav-link' >Home</a>
			      </li>

			      </ul>";
			     //mostrar notificaciones
			      $this->showNotifications();
      echo "
			    
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
";

         	//verificar que tipo de usuario es:si es usuario root (el que agrega y accede a la tienda que quiera) o usuario de una tienda normal
         	if($_SESSION['user_info']['tipo_usuario'] == 1){//para usuario root
         		//verificar si el usurio esta logueado se imprime el menu
	            echo "
			        <div class='info'>
			          <a href='' class='d-block'>"; $this->mostrarInicioController(); echo "<br><i class='nav-icon fa fa-wrench'></i> [ ROOT ]</a>
			        </div>
			      </div>

			      <!-- Sidebar Menu -->
			      <nav class='mt-2'>
			        <ul class='nav nav-pills nav-sidebar flex-column' data-widget='treeview' role='menu' data-accordion='false'>
			          <!-- Add icons to the links using the .nav-icon class
			               with font-awesome or any other icon font library -->
";
            
            //Si la tienda en sesion es la 1: quiere decir que es la tienda ROOT (base) es decir, solo se mostrara gestion de tiendas
     
              if($_SESSION["tienda"] == "1"){ //tienda root, solo mostrar GESTION DE TIENDAS
              echo "
                      <li class='nav-item'>
                        <a href='index.php?action=tiendas' class='nav-link'>
                          <i class='nav-icon fa fa-home'></i>
                          <p>Gestion de Tiendas</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='' class='nav-link' onclick='confirmLogout();'>
                          <i class='nav-icon fa fa-sign-out'></i>
                          <p>Cerrar Sesión</p>
                        </a>
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
              }else{//sino, es otra tienda mostrar todo normal
                echo "
                      <li class='nav-item'>
                        <a href='index.php' class='nav-link'>
                          <i class='nav-icon fa fa-dashboard'></i>
                          <p>Dashboard</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=tiendas' class='nav-link'>
                          <i class='nav-icon fa fa-home'></i>
                          <p>Gestion de Tiendas</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=movimiento_inventario' class='nav-link'>
                          <i class='nav-icon fa fa-exchange'></i>
                          <p>Movimiento de Inventario</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=ventas' class='nav-link'>
                          <i class='nav-icon fa fa-money'></i>
                          <p>Gestion de Ventas</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=categorias' class='nav-link'>
                          <i class='nav-icon fa fa-tags'></i>
                          <p>Gestion de Categorias</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=productos' class='nav-link'>
                          <i class='nav-icon fa fa-cube'></i>
                          <p>Gestion de Productos</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=usuarios' class='nav-link'>
                          <i class='nav-icon fa fa-users'></i>
                          <p>Gestion de Usuarios</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='index.php?action=ingresar_tienda&id=1' class='nav-link'>
                          <i class='nav-icon fa fa-sign-out'></i>
                          <p>Salir de tienda</p>
                        </a>
                      </li>
                      <li class='nav-item'>
                        <a href='' class='nav-link' onclick='confirmLogout();'>
                          <i class='nav-icon fa fa-sign-out'></i>
                          <p>Cerrar Sesión</p>
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
            }
            
           
         	}else{ //usuario normal (no agrega o modifica tiendas)

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
			                <a href='index.php' class='nav-link'>
			                  <i class='nav-icon fa fa-dashboard'></i>
			                  <p>Dashboard</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=movimiento_inventario' class='nav-link'>
			                  <i class='nav-icon fa fa-exchange'></i>
			                  <p>Movimiento de Inventario</p>
			                </a>
			              </li>
			              <li class='nav-item'>
                        <a href='index.php?action=ventas' class='nav-link'>
                          <i class='nav-icon fa fa-money'></i>
                          <p>Gestion de Ventas</p>
                        </a>
                      </li>
			              <li class='nav-item'>
			                <a href='index.php?action=categorias' class='nav-link'>
			                  <i class='nav-icon fa fa-tags'></i>
			                  <p>Gestion de Categorias</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=productos' class='nav-link'>
			                  <i class='nav-icon fa fa-cube'></i>
			                  <p>Gestion de Productos</p>
			                </a>
			              </li>
			              <li class='nav-item'>
			                <a href='index.php?action=usuarios' class='nav-link'>
			                  <i class='nav-icon fa fa-users'></i>
			                  <p>Gestion de Usuarios</p>
			                </a>
			              </li>

			              <li class='nav-item'>
			                <a href='' class='nav-link' onclick='confirmLogout();'>
			                  <i class='nav-icon fa fa-sign-out'></i>
			                  <p>Cerrar Sesión</p>
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

         	}
         	echo "<div>";
      }
	}
  }
}

	//funcion que muestra las notificaciones de productos con stock <= 5
	public function showNotifications(){

		//peticion de productos con stock bajo (menor a 5)
		$numberOfNotifications = $this->setQueryControllerGetNumber("productos", "stock", "<=", 5);


			        $data = $this->setQueryControllerGetData("productos", "stock", "<=", 5); //obtener informacion de productos que cumplen con esa condicion

			        if(!empty($data)){
			        	echo '

			      	 <!-- Right navbar links -->
		    			<ul class="navbar-nav ml-auto">
					        <!-- Notifications Dropdown Menu -->
				      <li class="nav-item dropdown">
				        <a class="nav-link" data-toggle="dropdown" href="#">
				          <i class="fa fa-bell-o">--</i>
				          <span class="badge badge-danger navbar-badge">'.$numberOfNotifications.'</span>
				        </a>
				        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				          <span class="dropdown-item dropdown-header"> ('.$numberOfNotifications.') Productos con stock bajo</span>
				        ';
				        //por cada producto con stock bajo, imprimirlo en la tabla de notificaciones
			        	foreach ($data as $row => $item) {
						echo '
							<div class="dropdown-divider"></div>
			          <a href="index.php?action=movimiento_inventario" class="dropdown-item">
			            <i class="fa nav-icon fa fa-cube"></i> '.$item["nombre"].' | Stock: '.$item["stock"].'
			            <span class="float-right text-muted text-sm"></span>
			          </a>
						';
						}

			        }else{
			        	echo '

			      	 <!-- Right navbar links -->
		    			<ul class="navbar-nav ml-auto">
					        <!-- Notifications Dropdown Menu -->
				      <li class="nav-item dropdown">
				        <a class="nav-link" data-toggle="dropdown" href="#">
				          <i class="fa fa-bell-o"></i>
				        </a>
				        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				          <span class="dropdown-item dropdown-header">No tienes notificaciones</span>
				        ';

			        }


					echo '


			      </li>
			      </ul>';
	}

  	//funcion encargada de ingresar los valores del login e iniciar sesion
	public function ingresoUsuarioController(){
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['btn_add'])){

			$resultado = Crud::ingresoUsuarioModel($_POST['username'], $_POST['password']); //se ejecuta la funcion del modelo
			//se verifica que lo retornado por el modelo no este vacio
			if(!empty($resultado)){
				//hasta aqui, el usuario esta registrado, pero tenemos que validar que la tienda no haya sido borrada, debido al borrado logico que se utiliza (solo se actualiza el campo deleted en 1 en caso de ser borrado)
				//verificar que la tienda en su campo deleted sea igual a 0 (tienda existente)
				$tienda = Crud::getRegModel($resultado['tiendas_id'], "tiendas", $resultado['tiendas_id']); //traer informacion de la tienda
				if($tienda['deleted'] == "0"){ //existe la tienda, entonces iniciar sesion
					$_SESSION['login']=true; //iniciar la variable de sesion login
					$_SESSION['user_info']= $resultado; //guardar los datos del usuario en una sesion
					$_SESSION['tienda'] = $resultado['tiendas_id'];
          
					echo "<script>window.location='index.php';</script>";
				}else{//No existe la tienda
					echo "<script>swal('Tienda desactivada', 'La tienda a la que intentas ingresar fue borrada o desactivada', 'error');</script>";
				}
        
			}else{
				//mostrar mensaje en caso de no existir el usuario
				echo "<script>swal('Usuario o contraseña incorrectos', 'No existe un usuario registrado con esas credenciales', 'error');</script>";
			}
		}
	}
	//funcion que imprime un mensaje en el inicio con el nombre del maestro tomado de la variable sesion
	public function mostrarInicioController(){
		if(isset($_SESSION['user_info'])){
			$tienda = Crud::getRegModel($_SESSION['tienda'], "tiendas", $_SESSION['tienda']);
			echo "<i class='nav-icon fa fa-user'></i> [ ".$_SESSION['user_info']['user']." ] <br> <i class='nav-icon fa fa-home'></i> [ ".$tienda['nombre']." ] ";
		}
	}

	//funcion encargada de crear una tabla con los productos registrados en la base de datos
	public function getProductosController(){
		$informacion = Crud::vistaXTablaModel("productos", $_SESSION['tienda']);//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de los productos
			foreach ($informacion as $row => $item) {
				$categoria = Crud::getRegModel($item['id_categoria'], "categorias", $_SESSION['tienda']);
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['codigo']."</td>";
				echo "<td>".$item['nombre']."</td>";
				echo "<td>".$item['descripcion']."</td>";
				echo "<td>".$item['precio_unitario']."</td>";
				echo "<td>".$item['stock']."</td>";
				echo "<td>".$categoria['nombre']."</td>";
				echo "<td>".$item['fecha_registro']."</td>";
          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_producto&id=".$item['id']."></a></td>";
				//mandar por propiedad onclick el id del elemento tag a para eleminarlo
				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=productos&id=".$item['id']."'></a></td>";  
            
        echo "</tr>";
				
			}
		}
		
	}

	//funcion encargada de crear una tabla con los productos registrados en la base de datos
	public function getHistorialProductos(){
		$informacion = Crud::vistaXTablaModel("transaccion", $_SESSION['tienda']);//ejecucion del metodo del modelo
		//	id	id_producto	id_usuario	cantidad	tipo	fecha	deleted
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de los productos
			foreach ($informacion as $row => $item) {
				$producto = Crud::getRegModel($item['id_producto'], "productos", $_SESSION['tienda']);
				$usuario = Crud::getRegModel($item['id_usuario'], "usuarios", $_SESSION['tienda']);
				echo "<tr>";
				echo "<td>".$item['serie']."</td>";
				echo "<td>".$producto['nombre']."</td>";
				echo "<td>".$usuario['user']."</td>";
				echo "<td>".$item['fecha']."</td>";
				echo "<td>".$item['cantidad']."</td>";
				echo "<td>".$item['tipo']."</td>";


			}
		}
		
	}

	//funcion encargada de verificar si el id de la tienda a ingresar existe, de ser asi cambia la variable de sesion de tienda para mostrar los datos de esta
	public function ingresarTiendaController($id){
		$tienda = Crud::getRegModel($id, "tiendas", "tiendas");
		if(!empty($tienda)){
			$_SESSION['tienda'] = $id;
			echo "<script>window.location='index.php';</script>";
		}else{
			echo "<script>swal('Error', 'La tienda a la que intentas ingresar fue borrada o esta desactivada', 'error');</script>";
			echo "<script>window.location='index.php';</script>";
		}
	}


	//funcion encargada de crear una tabla con las categorias registrados en la base de datos
	public function getCategoriasController(){
		$informacion = Crud::vistaXTablaModel("categorias", $_SESSION['tienda']);//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de las categorias
			foreach ($informacion as $row => $item) {
				echo "<tr>";
				echo "<td>".$item['id']."</td>";
				echo "<td>".$item['nombre']."</td>";
          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_categoria&id=".$item['id']."></a></td>";
				//mandar por propiedad onclick el id del elemento tag a para eleminarlo
				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=categorias&id=".$item['id']."'></a></td>";  
              
        echo "</tr>";
				
			}
		}
		
	}

	//funcion encargada de crear una tabla con las tiendas registrados en la base de datos
	public function getTiendasController(){
		$informacion = Crud::vistaXTablaModel("tiendas", "");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de las categorias
			foreach ($informacion as $row => $item) {
				if($item['id'] != 1){//no mostrar la tienda root (la tienda  a la que pertenece el super admin)
					echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$item['nombre']."</td>";
					echo "<td>".$item['direccion']."</td>";
					echo "<td>".$item['fecha_registro']."</td>";
	          		echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_tienda&id=".$item['id']."></a></td>";
          //mandar por propiedad onclick el id del elemento tag a para eleminarlo
	          		echo "<td>"."<a class='btn btn-warning fa  fa-circle-o' id='desactivar".$item["id"]."' onclick='d(".$item["id"].");' href='index.php?action=desactivar_tienda&id=".$item['id']."'></a></td>";  

				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=tiendas&id=".$item['id']."'></a></td>";  
	       echo "<td><a class='btn btn-success fa fa-sign-in' href=index.php?action=ingresar_tienda&id=".$item['id']."></a></td>";
	           //href='index.php?action=borrar&tipo=tiendas&id=".$item['id']."'
	        echo "</tr>";
				}else{ //si es la tienda root (Tienda base NO) NO MOSTRAR EDICION NI BORRADO
          echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$item['nombre']."</td>";
					echo "<td>".$item['direccion']."</td>";
					echo "<td>".$item['fecha_registro']."</td>";
	        echo "<td></td>";
					echo "<td></td>";  
					echo "<td></td>";  
	       echo "<td>"."<a class='btn btn-success fa fa-sign-in' href=index.php?action=ingresar_tienda&id=".$item['id']."></a></td>";
	           
	        echo "</tr>";
        }
				
				
			}
		}
		
	}

	//funcion encargada de crear una tabla con las ventas registrados en la base de datos
	public function getVentasController(){
		$informacion = Crud::vistaXTablaModel("ventas", $_SESSION['tienda']);//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de las categorias
			foreach ($informacion as $row => $item) {
					$usuario = Crud::getRegModel($item['id_usuario'], "usuarios", $_SESSION['tienda']);
					$tienda = Crud::getRegModel($item['tiendas_id'], "tiendas", $_SESSION['tienda']);
					echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$item['fecha']."</td>";
					echo "<td>".$usuario['user']."</td>";
					echo "<td>".$tienda['nombre']."</td>";
					echo "<td style='color:green'><strong>$ ".$item['total']."</strong></td>";
	          		echo "<td>"."<a class='btn btn-secondary fa fa-file-text-o' href=index.php?action=detalle_venta&id=".$item['id']."></a></td>";
	          		 echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=ventas&id=".$item['id']."'></a></td>";  
	        echo "</tr>";
        }
				
				
			}
		}
		




	//funcion encargada de crear una tabla con las tiendas desactivadas en la base de datos
	public function getTiendasDesactivadasController(){
		$informacion = Crud::vistaXTablaModel("tiendas", "desactivadas");//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de las categorias
			foreach ($informacion as $row => $item) {
				if($item['id'] != 1){//no mostrar la tienda root (la tienda  a la que pertenece el super admin)
					echo "<tr>";
					echo "<td>".$item['id']."</td>";
					echo "<td>".$item['nombre']."</td>";
					echo "<td>".$item['direccion']."</td>";
					echo "<td>".$item['fecha_registro']."</td>";
          //mandar por propiedad onclick el id del elemento tag a para eleminarlo
	          		echo "<td>"."<a class='btn btn-warning fa  fa-circle' id='activar".$item["id"]."' onclick='a(".$item["id"].");' href='index.php?action=activar_tienda&id=".$item['id']."'></a></td>";  

				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=tiendas&id=".$item['id']."'></a></td>";  
	        echo "</tr>";
				}
			}
		}
		
	}







	//funcion encargada de crear una tabla con los usuarios registrados en la base de datos
	public function getUsuariosController($idUser){
		$informacion = Crud::vistaXTablaModel("usuarios", $_SESSION['tienda']);//ejecucion del metodo del modelo
		if(!empty($informacion)){
			//si el resultado no esta vacio, imprimir los datos de los usuarios
      if(empty($idUser)){
          foreach ($informacion as $row => $item) {
          echo "<tr>";
          echo "<td>".$item['id']."</td>";
          echo "<td>".$item['user']."</td>";
          //para no mostrar la contraseña en texto plano, utilizamos un metodo de encriptacion md5
          echo "<td>".md5($item['password'])."</td>";
          echo "<td>".$item['fecha_registro']."</td>";
                echo "<td>"."<a class='btn btn-secondary fa fa-edit' href=index.php?action=editar_usuario&id=".$item['id']."></a></td>";
          echo "<td>"."<a class='btn btn-danger fa fa-trash' href=index.php?action=borrar&tipo=usuarios&id=".$item['id']." class='button radius tiny warning' onclick='confirmar();'></a></td>";

            echo "</tr>";

        }
      }else{
        foreach ($informacion as $row => $item) {
          if($item['id']!=$idUser){
            echo "<tr>";
          echo "<td>".$item['id']."</td>";
          echo "<td>".$item['user']."</td>";
          //para no mostrar la contraseña en texto plano, utilizamos un metodo de encriptacion md5
          echo "<td>".md5($item['password'])."</td>";
          echo "<td>".$item['fecha_registro']."</td>";
               echo "<td>"."<a class='btn btn-secondary fa fa-edit' href='index.php?action=editar_usuario&id=".$item['id']."'></a></td>";
             //mandar por propiedad onclick el id del elemento tag a para eleminarlo
				  echo "<td>"."<a class='btn btn-danger fa fa-trash' id='borrar_btn".$item["id"]."' onclick='b(".$item["id"].");' href='index.php?action=borrar&tipo=usuarios&id=".$item['id']."'></a></td>";  
           
            echo "</tr>";
          }
          

			}
      }
			
		}
		
	}


 
	//funcion que crea un select con las categorias registradas
	public function getSelectForCategorias($firstID){
		$informacion = Crud::vistaXTablaModel("categorias", $_SESSION['tienda']); //se obtienen todos las categorias de la bd mediante la conexion al modelo
		if(!empty($informacion)){
			if($firstID==""){
				foreach ($informacion as $row => $item) {
					echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}else{
				//se obtiene la informacion del maestro en base al parametro firstID
				$categoria = Crud::getRegModel($firstID, "categorias", $_SESSION['tienda']);
				//se coloca primero la opcion del select del maestro
				echo "<option value='".$categoria['id']."'>".$categoria['nombre']."</option>";
				foreach ($informacion as $row => $item) { //se imprimen los maestros restantes
					if($item['id']!=$firstID)
						echo "<option value='".$item['id']."'>".$item['nombre']."</option>";
				}
			}
			
			
		}
	}

	//funcion que crea un select con los productos registrados
	public function getSelectForProductos($flag){
		$informacion = Crud::vistaXTablaModel("productos", $_SESSION['tienda']); //se obtienen todos las categorias de la bd mediante la conexion al modelo
		$c = 0;
		if(!empty($informacion)){ 
				foreach ($informacion as $row => $item) { //se imprimen los valores
					if(!empty($flag)){
						echo "<option value='".$c."'>".$item['codigo']. " | ".$item['nombre'] ." | " . $item['stock'] . "</option>";
						$c++;
					}else{
						echo "<option value='".$item['id']."'>".$item['codigo']. " | ".$item['nombre'] ." | Stock: " . $item['stock'] . "</option>";
				
					}
				}

				$_SESSION['temp_productos'] = $informacion;
				//print_r($_SESSION['temp_productos']);
				//echo json_encode($_SESSION['temp_productos']);
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
						'tiendas_id'=> $_SESSION['tienda']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroProductoModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
				//obtener indice del producto registrado
				$id_prod = Crud::getLastIndexInX("productos",$_SESSION['tienda']);


				//registrar el stock del producto agregado
				//crear array con los datos a registrar tomados de los controles
			$data_historia = array('id_producto'=>$id_prod[0],
						'cantidad'=> $_POST['stock'],
						'id_usuario'=> $_SESSION['user_info']['id'],
						'fecha'=> date("Y-m-d"),
						'tipo'=> "Stock inicial",
						'serie'=> "Alta",
						'tiendas_id'=> $_SESSION['tienda']
					);
			//registrar en historial stock con el que inicio
			$last = Crud::registroHistorialModel($data_historia);

			if($last == "success" )
			{
				echo "<script>
        swal('Exito!', 'Producto registrado', 'success');
        window.location='index.php?action=productos';
        </script>";
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al registrar, por favor contacta al administrador', 'error');</script>";
			}
			}else{
				echo "<script>swal('Error', 'Por favor, ingresa otro codigo de producto', 'error');</script>";
			}
		}
	}


	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroHistorialController(){
		if(isset($_POST['btn_movimiento_entrada']) || isset($_POST['btn_movimiento_salida'])   ){//verificar clic en el boton
			
			//verificar de que tipo fue el movimiento
			if(isset($_POST['btn_movimiento_entrada'])){
				$tipo = "Entrada";
			}else if(isset($_POST['btn_movimiento_salida'])){
				$tipo = "Salida";
			}
			//crear array con los datos a registrar tomados de los controles
			$data = array('id_producto'=> $_POST['producto'],
						'cantidad'=> $_POST['cantidad'],
						'id_usuario'=> $_SESSION['user_info']['id'],
						'fecha'=> date("Y-m-d"),
						'tipo'=> $tipo,
						'serie'=> $_POST['codigo_control'],
						'tiendas_id'=> $_SESSION['tienda']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroHistorialModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
				echo "<script>
        swal('Exito!', 'Movimiento registrado', 'success');
        window.location='index.php?action=movimiento_inventario';
        </script>";
			}else if($registro == "nostock"){
				echo "<script>swal('Stock insuficiente!', 'La cantidad ingresada excede el stock disponible del producto', 'warning');</script>";
			}else{
				echo "<script>swal('Error', 'Error al registrar el movimiento', 'error');</script>";
			}
		}
	}



	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroCategoriaController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre'],
				'tiendas_id'=> $_SESSION['tienda']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroCategoriaModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        echo "<script>swal('Exito!','Categoría registrada','success');
        window.location='index.php?action=categorias';</script>";
			}else{
				echo "<script>swal('Error','Ocurrió un error al registrar','error');</script>";
			}
		}
	}

	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroUsuarioController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('username'=> $_POST['username'],
				'password'=> $_POST['password'],
				'fecha_registro'=> date("Y-m-d"),
				'tiendas_id'=> $_SESSION['tienda']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroUsuarioModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        echo "<script>swal('Exito!','Usuario registrado','success');
        window.location='index.php?action=usuarios';</script>";
			}else{
				echo "<script>swal('Error','Por favor, ingresa otro username','error');</script>";
			}
		}
	}



	//funcion encargada de verificar si se presiono un boton de registro, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroTiendaController(){
		if(isset($_POST['btn_agregar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('nombre'=> $_POST['nombre'],
				'direccion'=> $_POST['direccion'],
				'fecha_registro'=> date("Y-m-d")
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroTiendaModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        echo "<script>swal('Exito!','Tienda registrada','success');
        window.location='index.php?action=tiendas';</script>";
			}else{
			echo "<script>swal('Error', 'Por favor, ingresa otro nombre de tienda', 'error');</script>";
			}
		}
	}



	//funcion encargada de verificar si se presiono un boton de registro de venta, de ser asi, se toman los datos de los controles y se ejecuta la funcion que registra en el modelo
	public function registroVentaController(){
		if(isset($_POST['btn_registrar'])){//verificar clic en el boton
			//crear array con los datos a registrar tomados de los controles
			$data = array('fecha'=> date("Y-m-d"),
				'id_usuario' => $_SESSION['user_info']['id'],
				'tiendas_id' => $_SESSION['tienda'],
				'total'=> $_POST['total']
					);
			//peticion al modelo del reigstro del producto mandando como param la informacion de este
			$registro = Crud::registroVentaModel($data);
			if($registro == "success"){ //verificar la respuesta del modelo
        
				//obtener id de la venta
				$venta = Crud::getLastIndexInX("ventas",$_SESSION['tienda']);
				//print_r($venta);

				//iterar por cada producto y asi insertar en la tabla ventasproductos
				$iter = 0;
				while(!empty($_POST['nombre'.$iter])){
					$data2 = array('id_producto'=> $_POST['id'.$iter],
				'id_venta'=> $venta[0],
				'subtotal'=> $_POST['subtotal'.$iter],
				'cantidad'=> $_POST['cantidad'.$iter]
					);
					Crud::registroProductoEnVentaModel($data2); //registrar en el modelo

					//crear array con los datos a registrar tomados de los controles
			$data_historia = array('id_producto'=> $_POST['id'.$iter],
						'cantidad'=> $_POST['cantidad'.$iter],
						'id_usuario'=> $_SESSION['user_info']['id'],
						'fecha'=> date("Y-m-d"),
						'tipo'=> "Vendido",
						'serie'=> "Venta",
						'tiendas_id'=> $_SESSION['tienda']
					);
			//registrar en historial
			Crud::registroHistorialModel($data_historia);


		        $iter++;
				}

		        echo "<script>swal('Exito!','Venta registrada con Id: ".$venta[0]."','success');
		        </script>";
			}else{
				echo "<script>swal('Error','Ocurrió un error al registrar','error');</script>";
			}
		}
	}




 

	//funcion encargada de, dado un id de un producto, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getProductoController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'productos', $_SESSION['tienda']); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo "
			<div class='col-sm-6'>
            <!-- general form elements -->
			 <div class='card card-primary'>
               <div class='card-header'>
                        <h3 class='card-title'>Realice los cambios correspondientes y de clic en guardar</h3>
                </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class='card-body login-card-body'>

				<div class='form-group'>
                    <p>
                    <label>Id</label>
                    <input type='text' class='form-control' name='id' value='".$peticion['id']."' placeholder='Ingresa el codigo del producto' required='' readonly='true'>
                  </p>
                  </div>
				<div class='form-group'>
                    <p>
                    <label>Codigo</label>
                    <input type='text' class='form-control' name='codigo' value='".$peticion['codigo']."' placeholder='Ingresa el codigo del producto' required='' readonly='true'>
                  </p>
                  </div>
                  <div class='form-group'>
                    <p>
                    <label>Nombre</label>
                    <input type='text' class='form-control' name='nombre' value='".$peticion['nombre']."' placeholder='Ingresa el nombre del producto' required=''>
                  </p>
                  </div>
                  </div>
                  </div>
                  </div>
			<div class='col-sm-6'>
                  <div class='card card-primary'>
              <!-- /.card-header -->
              <!-- form start -->
                <div class='card-body login-card-body'>
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
                  </div><br>
                <button type='submit' name='btn_actualizar' id='targ'  class='btn btn-success' onclick='c();' style='float:right;'>Guardar cambios</button>
                  </div>
                  ";
		}
	}


	//funcion encargada de, dado un id de una categoria, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getCategoriaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'categorias', $_SESSION['tienda']); //peticion al modelo del registro especificado por el id
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

	//funcion encargada de, dado un id de un usuario, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getUsuarioController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, 'usuarios', $_SESSION['tienda']); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo "
					<div class='form-group'>
                    <label>Id</label>
                    <input type='text' class='form-control' name='id' value='".$peticion["id"]."' placeholder='Ingresa el username del usuario' required='' readonly='true'>
                  </div>
				<div class='form-group'>
                    <label>Username</label>
                    <input type='text' class='form-control' name='username' value='".$peticion["user"]."' placeholder='Ingresa el username del usuario' required=''>
                  </div>
                  <div class='form-group'>
                    <label>Password</label>
                    <input type='text' class='form-control' name='password' value='".$peticion["password"]."' placeholder='Ingresa la contraseña del usuario' required=''>
                  </div>
                  ";
		}
	}

	//funcion encargada de, dado un id de una venta, se obtienen los datos de la base de datos y se imprimen los productos asociados a esta
	public function getVentaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id

		$peticionVenta = Crud::getRegModel($id, "ventas", $_SESSION['tienda']);
		if(!empty($peticionVenta)){
			$peticion = Crud::getProductosVentasModel($id); //peticion al modelo del registro especificado por el id
		$venta = Crud::getRegModel($id, "ventas", $_SESSION['tienda']);
		if(!empty($peticion)){
			$usuario = Crud::getRegModel($peticionVenta['id_usuario'] , "usuarios", $_SESSION['tienda']);
			echo '
					<div class="form-group">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Informacion de venta <strong>'.$id .'</strong> : <strong> Total: $'. $venta['total']. '</strong> - Usuario: '.$usuario['user'] .'</h3>
                      </div>
                    <div class="card-body p-0">
                      <br>
                    <div class="table-responsive">
                    <table width="100%" id="detalleVenta" class="table table-bordered table-striped">
                      <thead>
                        <th>Id de venta</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                         <th>Subtotal</th>
                      </thead>
                      <tbody>';
                      foreach ($peticion as $row => $item) {
			          echo "<tr>";
			          echo "<td>".$item['id_venta']."</td>";
			          $producto = Crud::getRegModel($item['id_producto'], "productos", $_SESSION['tienda']);
			          echo "<td>".$producto['nombre']."</td>";
			          echo "<td>".$item['cantidad']."</td>";
			          echo "<td style='color:green'><strong>$ ".$item['subtotal']."</strong></td>";
			            echo "</tr>";

			        }
                     echo '
                        
                      </tbody>
                    </table>
                    </div>
                  </div>
                  </div>
                </div>

			';
		}else{
			echo "<script>window.location='index.php?action=ventas';</script>";
		}
	}else{
		echo "<script>window.location='index.php?action=ventas';</script>";
	}
		
	}

	//funcion encargada de, dado un id de un usuario, se obtienen los datos de la base de datos y se imprimen los controles con los datos en los valores para editarlos posteriormente
	public function getTiendaController(){
		$id = (isset($_GET['id'])) ? $_GET['id'] : ""; //verificacion del id
		$peticion = Crud::getRegModel($id, "tiendas", $_SESSION['tienda']); //peticion al modelo del registro especificado por el id
		if(!empty($peticion)){
			echo "
					<div class='form-group'>
                    <label>Id</label>
                    <input type='text' class='form-control' name='id' value='".$peticion["id"]."' placeholder='Ingresa el id' required='' readonly='true'>
                  </div>
				<div class='form-group'>
                    <label>Nombre</label>
                    <input type='text' class='form-control' name='nombre' value='".$peticion["nombre"]."' placeholder='Ingresa el nombre de la tienda' required=''>
                  </div>
                  <div class='form-group'>
                    <label>Direccion</label>
                    <input type='text' class='form-control' name='direccion' value='".$peticion["direccion"]."' placeholder='Ingresa la direccion de la tienda' required=''>
                  </div>
                  ";
		}else{
			echo "<script>window.location='index.php?action=tiendas';</script>";
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
				"categoria"=>$_POST['categoria'],
				"tiendas_id"=>$_SESSION['tienda']
			);

			//se realiza la ejecucion del metodo que actualiza un alumno en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarProductoModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=productos';</script>";
        
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarUsuarioController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"username"=>$_POST['username'],
				"password"=>$_POST['password'],
				"tiendas_id"=>$_SESSION['tienda']
			);

			//se realiza la ejecucion del metodo que actualiza un alumno en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarUsuarioModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=usuarios';</script>";
        
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}

	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarTiendaController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"nombre"=>$_POST['nombre'],
				"direccion"=>$_POST['direccion']
			);

			//se realiza la ejecucion del metodo que actualiza la tienda en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarTiendaModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=tiendas';</script>";
        
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}





	//funcion que verifica si se dio clic en el boton de actualizacion y realiza la actualizacon mediante la ejecucion del metodo del modelo
	public function actualizarCategoriaController(){
		if(isset($_POST['btn_actualizar'])){ //verificacion de clic en el boton
			//se toman los valores de los controles y se guardan en un array
			$data = array(
				"nombre"=>$_POST['nombre'],
				"tiendas_id"=>$_SESSION['tienda']
			);

			//se realiza la ejecucion del metodo que actualiza un alumno en el modelo, mandando los parametros correspondientes, datos y matricula
			$peticion = Crud::actualizarCategoriaModel($data, $_POST['id']);
			if($peticion == "success"){ //verificacion de la respuesta por el modelo
       echo "<script>window.location='index.php?action=categorias';</script>";
        
			}else{
				echo "<script>swal('Error', 'Ocurrio un error al guardar los cambios', 'error');</script>";
			}
		}
	}


	//funcion que muestra el dashboard del sistema
	function showDashboard(){
		//mostrar productos sin stock
		$this->setQueryController("productos","Productos sin stock", "stock", "=",0, $_SESSION['tienda']);

		$this->setQueryController("transaccion","Transacciones realizadas hoy", "fecha", "=", date('Y-m-d'), $_SESSION['tienda']);
		
	}


	//funcion setQueryControllerGetNumber, dado un nombre de tabla, un campo, un operador y un valor, se ejecuta un metodo de la clase modelo (Crud) el cual, pasando como parametro los valores mencionados, obtiene los registos que cumplan con estas coincidencias en la tabla seleccionada y retorna el numero de registros de la coincidencia
	function setQueryControllerGetNumber($table, $field, $operator, $equals){
		$peticion = Crud::getQueryFromX($table, $field, $operator,$equals, $_SESSION['tienda']); //peticion al modelo
		//se imprime la tabla con informacion
		if(!empty($peticion)){ //se verifica que la variable peticion no este vacia
			return count($peticion);
		}else{
			return 0;
		}
	}

	//funcion setQueryControllerGetData, dado un nombre de tabla, un campo, un operador y un valor, se ejecuta un metodo de la clase modelo (Crud) el cual, pasando como parametro los valores mencionados, obtiene los registos que cumplan con estas coincidencias en la tabla seleccionada y retorna los datos de esta
	function setQueryControllerGetData($table, $field, $operator, $equals){
		$peticion = Crud::getQueryFromX($table, $field, $operator,$equals, $_SESSION['tienda']); //peticion al modelo
		if(!empty($peticion)){ //se verifica que la variable peticion no este vacia
			return $peticion;
		}else{
			return "";
		}
	}



	//funcion setQueryController, dado un nombre de tabla, un titulo, un campo, un operador y un valor, se ejecuta un metodo de la clase modelo (Crud) el cual, pasando como parametro los valores mencionados, obtiene los registos que cumplan con estas coincidencias en la tabla seleccionada y crea una tabla con esta informacion
	function setQueryController($table, $title, $field, $operator, $equals, $tiendas_id){
		$peticion = Crud::getQueryFromX($table, $field, $operator,$equals, $tiendas_id); //peticion al modelo
		//se imprime la tabla con informacion
		echo "<div class='form-group'>
					<div class='card-header'>
                        <h3 class='card-title'>$title (".count($peticion).")</h3>
                      </div>
                    <div class='card'>";
                    if($table=="productos"){
                    	echo "<br><div class='table-responsive'><table id='htable2' width='100%' class='table table-bordered table-striped'>";
                    }else{
                    	echo "<br><div class='table-responsive'><table id='htable' width='100%' class='table table-bordered table-striped'>";
                    }
		
			echo "<thead>";
				echo "<tr>";
					if($table=="transaccion"){
						echo "<th>Producto</th>";
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
		//echo "<tbody>";
		if(!empty($peticion)){ //se verifica que la variable peticion no este vacia
			echo "<tbody>";
			foreach ($peticion as $row => $item) {
				echo "<tr>";
				if($table == "transaccion"){
					$producto = Crud::getRegModel($item['id_producto'],"productos",$_SESSION['tienda']);
					echo "<td>".$producto['nombre']."</td>";
					echo "<td>".$item["cantidad"]."</td>";
					echo "<td>".$item["tipo"]."</td>";
					echo "<td>".$item["fecha"]."</td>";
					$usuario = Crud::getRegModel($item['id_usuario'],"usuarios", $_SESSION['tienda']);
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
      		echo "</div>";
	}


	//Funcion que dado un id y nombre de tabla, ejecuta el metodo del modelo y borra el registro especificado en base a la tabla
	public function borrarController($id, $tabla){
		//se ejecuta el metodo borrar del modelo mandando como paremtros los explicados anteriormente
		$peticion = Crud::borrarXModel($id, $tabla, $_SESSION['tienda']);
		if($peticion == "success"){ //verificar respuesta
      if($tabla == "tiendas" && $id == $_SESSION["tienda"]){
        $_SESSION["tienda"] = 1;
      }
			echo "<script>
     
      window.location='index.php?action=".$tabla."';
      </script>";
		}else{
			echo "<script>swal('Error', 'Ocurrio un error al dar de baja el registro', 'error');</script>";
		}
	}

	//Funcion que dado un idejecuta el metodo del modelo y desactiva la tienda especificada
	public function desactivateTiendaController($id){
		//se ejecuta el metodo desactivar del modelo mandando como paremtro el id de la tienda
		$peticion = Crud::desactivateTiendaModel($id);
		if($peticion == "success"){ //verificar respuesta
	      if($id == $_SESSION["tienda"]){
	        $_SESSION["tienda"] = 1;
	      }
	echo "<script>
      window.location='index.php?action=tiendas';
      </script>";
		}else{
			echo "<script>swal('Error', 'Ocurrio un error al desactivar la tienda', 'error');</script>";
		}
	}

	//Funcion que dado un id ejecuta el metodo del modelo y activa la tienda especificada
	public function activateTiendaController($id){
		//se ejecuta el metodo desactivar del modelo mandando como paremtro el id de la tienda
		$peticion = Crud::activateTiendaModel($id);
		if($peticion == "success"){ //verificar respuesta
	      if($id == $_SESSION["tienda"]){
	        $_SESSION["tienda"] = 1;
	      }
	echo "<script>
      window.location='index.php?action=tiendas';
      </script>";
		}else{
			echo "<script>swal('Error', 'Ocurrio un error al activar la tienda', 'error');</script>";
		}
	}



}





?>