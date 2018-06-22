<?php
//Clase de enlaces de pagina
class Enlaces{
	//metodo publico que dado un nombre de enlace, retorna el modulo que sera incluido o mostrado 
	public function enlacesPaginasModel($enlace){
		
		if($enlace == "grupos" || $enlace == "alumnas" || $enlace == "pagos"){
			$module = "view/$enlace/$enlace.php";
		}else if($enlace == "registro_grupo" || $enlace == "editar_grupo"){
			$module = "view/grupos/$enlace.php";
		}else if($enlace == "registro_alumna" || $enlace == "editar_alumna"){
			$module = "view/alumnas/$enlace.php";
		}else if($enlace == "editar_pago"){
			$module = "view/pagos/$enlace.php";
		}
		else if($enlace == "registro_comprobante" || $enlace == "login" || $enlace == "lugares"){
			$module = "view/$enlace.php";
		}else if($enlace == "logout" || $enlace == "aprobar_pago"){
			$module = "controller/$enlace.php";
		}else if($enlace == "borrar"){
			$module = "controller/$enlace.php";
		}
		else{
			$module = "view/registro_comprobante.php";
		}
		return $module;
	}
}



?>