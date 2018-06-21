<?php

	//se destruye la sesion actual
	session_destroy();
	//se redirecciona al index
	echo "<script>window.location='index.php';</script>";

?>