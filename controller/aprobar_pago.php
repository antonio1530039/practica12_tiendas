<?php
	//se crea la instancia del controlador
	$controller = new MVC();
	
	//guardar el id obtenido por el metodo get
	$id = (isset($_GET['id'])) ? $_GET['id'] : "";

	//se ejecuta el metodo borrar de la clase del controlador
	$controller->aprobarPagoController($id);

?>