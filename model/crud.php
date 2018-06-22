<?php
//se incluye el archivo de conexion
require_once "conexion.php";
//clase modelo llamada Crud que hereda las propiedades y metodos de la clase Conexion
class Crud extends Conexion{
	
	//metodo ingresoUsuarioModel: dado un email y una contrasena, se realiza un select en la base de datos de maestros y reotrna el resultado, esto para verificar si coincide con una cuenta de un maestro registrada
	public function ingresoUsuarioModel($user, $password){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE user = :user and password = :password"); //se prepara la conexion
		//definicion de parametros
		$stmt->bindParam(":user", $user);
		$stmt->bindParam(":password",$password);
		$stmt->execute(); //ejecucion mediante pdo
		return $stmt->fetch(); //se retorna lo asociado a la consulta
		$stmt->close();
	}

	//metodo vistaXTablaModel: dado un nombre de tabla realiza un select y retorna el contenido de la tabla
	public function vistaXTablaModel($table){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table"); //preparacion de la consulta SQL 
		$stmt->execute(); //ejecucion de la consulta
		return $stmt->fetchAll(); //se retorna en un array asociativo el resultado de la consulta
		$stmt->close();

	}


	//metodo getPagosSortedByDate: dado un nombre de tabla realiza un select y retorna el contenido de la tabla
	public function getPagosSortedByDate(){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM pagos WHERE aprobado=1 ORDER BY fecha_envio ASC"); //preparacion de la consulta SQL 
		$stmt->execute(); //ejecucion de la consulta
		return $stmt->fetchAll(); //se retorna en un array asociativo el resultado de la consulta
		$stmt->close();

	}



	//metodo registroComprobanteModel: dado un arreglo asociativo de datos, se inserta en la tabla pagos los datos especificados
	public function registroComprobanteModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO pagos(id_grupo, id_alumna, nombre_mama,
			fecha_pago, fecha_envio, imagen_comprobante, folio) VALUES(:id_grupo, :id_alumna, :nombre_mama, :fecha_pago, :fecha_envio, :imagen, :folio)");
		//preparacion de parametros
		$stmt->bindParam(":id_grupo", $data['id_grupo']);
		$stmt->bindParam(":id_alumna", $data['id_alumna']);
		$stmt->bindParam(":nombre_mama", $data['nombre_mama']);
		$stmt->bindParam(":fecha_pago", $data['fecha_pago']);
		$stmt->bindParam(":fecha_envio", $data['fecha_envio']);
		$stmt->bindParam(":imagen", $data['imagen']);
		$stmt->bindParam(":folio", $data['folio']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroGrupoModel: dado un arreglo asociativo de datos, se inserta en la tabla grupos los datos especificados
	public function registroGrupoModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO grupos(nombre) VALUES(:nombre)");
		//preparacion de parametros
		$stmt->bindParam(":nombre", $data['nombre']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroAlumnaModel: dado un arreglo asociativo de datos de una alumna, se inserta en la tabla alumnas los datos especificados
	public function registroAlumnaModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO alumnas(nombre, apellidos, fecha_nacimiento, id_grupo) VALUES(:nombre, :apellidos, :fecha_nacimiento, :id_grupo)");
		//preparacion de parametros
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":apellidos", $data['apellidos']);
		$stmt->bindParam(":fecha_nacimiento", $data['fecha_nacimiento']);
		$stmt->bindParam(":id_grupo", $data['id_grupo']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}


	//metodo getRegModel: dado un id de un registro y el nombre de la tabla se retorna la informacion del id asociado
	public function getRegModel($id, $table){
		//se prepara la consulta sql
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE id = :id");
		$stmt->bindParam(":id",$id); //se asocia el parametro 
		$stmt->execute(); //se ejecuta la consulta
		return $stmt->fetch(); //se retorna el resultado de la consulta
		$stmt->close();
	}

	

	//metodo actualizarGrupoModel: dado un array de datos y un id de un grupo, se actualizan los datos de este con los datos mandados
	public function actualizarGrupoModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE grupos SET nombre=:nombre WHERE id = $id");
		$stmt->bindParam(":nombre", $data['nombre']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();
	}

	//metodo actualizarAlumnaModel: dado un array de datos y un id de una alumna, se actualizan los datos de esta con los datos mandados
	public function actualizarAlumnaModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE alumnas SET nombre=:nombre, apellidos = :apellidos, fecha_nacimiento = :fecha_nacimiento, id_grupo = :id_grupo WHERE id = :id");
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":apellidos", $data['apellidos']);
		$stmt->bindParam(":fecha_nacimiento", $data['fecha_nacimiento']);
		$stmt->bindParam(":id_grupo", $data['id_grupo']);
		$stmt->bindParam(":id", $data['id']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();
	}

	//metodo actualizarPagoModel: dado un array de datos y un id de un pago, se actualizan los datos de esta con los datos mandados
	public function actualizarPagoModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE pagos SET id_grupo=:id_grupo, id_alumna = :id_alumna, nombre_mama = :nombre_mama, fecha_pago = :fecha_pago, fecha_envio = :fecha_envio, folio = :folio WHERE id = :id");

		$stmt->bindParam(":id_grupo", $data['id_grupo']);
		$stmt->bindParam(":id_alumna", $data['id_alumna']);
		$stmt->bindParam(":nombre_mama", $data['nombre_mama']);
		$stmt->bindParam(":folio", $data['folio']);
		$stmt->bindParam(":fecha_pago", $data['fecha_pago']);
		$stmt->bindParam(":fecha_envio", $data['fecha_envio']);
		$stmt->bindParam(":id", $data['id']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();
	}

	

	//metodo borrarXModel: dado un id de un registro y un nombre de tabla se realiza el borrado del registro en la tabla indicada
	public function borrarXModel($id, $table){
		$stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE id = :id"); //preparar consulta sql
		$stmt->bindParam(":id",$id); //se asocia el parametro indicado (id)
		if($stmt->execute()){ //se ejecuta la consulta
				return "success"; //se retorna la respuesta
		}else{
				return "error";
		}
		$stmt->close();
	}


	//funncion que en base un id de una tienda la desactiva (cambia el valor de active de 1 a 0)
	public function aprobarPagoModel($id){
		$stmt = Conexion::conectar()->prepare("UPDATE pagos SET aprobado=1 WHERE id = :id"); //actualizar a 1 el campo deleted de la tabla
		$stmt->bindParam(":id",$id); //se asocia el parametro indicado
		if($stmt->execute()){
			return "success";
		}else{
			return "error";
		}
		$stmt->close();


	}

}



?>