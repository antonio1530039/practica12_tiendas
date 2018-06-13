<?php
	//se incluyen los archivos de modelo y controlador
	require_once("model/crud.php");
	require_once("controller/controller.php");
	//cambiar la variable de sesion de tienda actual a la mandada por el parametro get
	
	//tomar el id de ingreso
	$idIngreso = isset($_GET['id']) ? $_GET['id'] : "";


	//se crea la instancia del controlador
	$controller = new MVC();

	$controller->ingresarTiendaController($idIngreso);


?>