<?php
	//se incluyen los archivos de modelo y controlador
	require_once("model/crud.php");
	require_once("controller/controller.php");

	//se crea la instancia del controlador
	$controller = new MVC();
	
	//guardar el id obtenido por el metodo get
	$id = (isset($_GET['id'])) ? $_GET['id'] : "";

	//se ejecuta el metodo borrar de la clase del controlador
	$controller->activateTiendaController($id);

?>