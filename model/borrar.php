<?php
	//se crea la instancia del controlador
	$controller = new MVC();

	$id = (isset($_GET['id'])) ? $_GET['id'] : "";
	$tabla = (isset($_GET['tipo'])) ? $_GET['tipo'] : "";

	//se ejecuta el metodo borrar de la clase del controlador
	$controller->borrarController($id,$tabla);

?>