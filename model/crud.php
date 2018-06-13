<?php
//se incluye el archivo de conexion
require_once "conexion.php";
//clase modelo llamada Crud que hereda las propiedades y metodos de la clase Conexion
class Crud extends Conexion{
	
	//metodo ingresoUsuarioModel: dado un email y una contrasena, se realiza un select en la base de datos de maestros y reotrna el resultado, esto para verificar si coincide con una cuenta de un maestro registrada
	public function ingresoUsuarioModel($user, $password){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE user = :user and password = :password and deleted=0"); //se prepara la conexion
		//definicion de parametros
		$stmt->bindParam(":user", $user);
		$stmt->bindParam(":password",$password);
		$stmt->execute(); //ejecucion mediante pdo
		return $stmt->fetch(); //se retorna lo asociado a la consulta
		$stmt->close();
	}

	//metodo vistaXTablaModel: dado un nombre de tabla realiza un select y retorna el contenido de la tabla, considerando solamente registros no borrados.
	public function vistaXTablaModel($table, $tiendas_id){
		//verificar si la tabla a mostrar es tienda debido a que no cuenta con campo tiendas_id (es la tabla padre)
	if($table == "tiendas"){
			if($tiendas_id == "desactivadas"){
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE deleted=0 and active=0"); //preparacion de la consulta SQL
			 }else{
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE deleted=0 and active=1"); //preparacion de la consulta SQL 
			}
	}else if($table == "transaccion"){
      		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE deleted=0 and tiendas_id = :tiendas_id ORDER BY id DESC"); //preparacion de la consulta SQL 
      		$stmt->bindParam(":tiendas_id",$tiendas_id);
    }
    else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE deleted=0 and tiendas_id = :tiendas_id"); //preparacion de la consulta SQL 
      		$stmt->bindParam(":tiendas_id",$tiendas_id);
		}
		$stmt->execute(); //ejecucion de la consulta
		return $stmt->fetchAll(); //se retorna en un array asociativo el resultado de la consulta
		$stmt->close();

	}

	//metodo getProductosVentasModel: dado un id de venta, trae todos los registros de esa venta de la tabla ventasProductos
	public function getProductosVentasModel($id){
		//preparar la conexion
			$stmt = Conexion::conectar()->prepare("SELECT * FROM ventas_productos WHERE deleted=0 and id_venta = :id"); //preparacion de la consulta SQL 
      		$stmt->bindParam(":id",$id);
		$stmt->execute(); //ejecucion de la consulta
		return $stmt->fetchAll(); //se retorna en un array asociativo el resultado de la consulta
		$stmt->close();

	}




	//metodo registroProductoModel: dado un arreglo asociativo de datos, se inserta en la tabla productos los datos especificados
	public function registroProductoModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO productos(codigo, nombre, descripcion, precio_unitario, stock, fecha_registro, id_categoria, tiendas_id) VALUES(:codigo, :nombre, :descripcion, :precio_unitario, :stock, :fecha, :categoria, :tiendas_id)");
		//preparacion de parametros
		$stmt->bindParam(":codigo", $data['codigo']);
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":descripcion", $data['descripcion']);
		$stmt->bindParam(":precio_unitario", $data['precio_unitario']);
		$stmt->bindParam(":stock", $data['stock']);
		$stmt->bindParam(":fecha", $data['fecha']);
		$stmt->bindParam(":categoria", $data['categoria']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroHistorialModel: dado un arreglo asociativo de datos, se inserta en la tabla productos los datos especificados
	public function registroHistorialModel($data){
		//id	id_producto	id_usuario	cantidad	tipo	fecha	deleted
		//verificar si existe stock´

		if($data["tipo"]=="Vendido" || $data["tipo"]=="Stock inicial" ){
			$stmt = Conexion::conectar()->prepare("INSERT INTO transaccion(id_producto, id_usuario, cantidad, tipo, fecha, serie, tiendas_id) VALUES(:id_producto, :id_usuario, :cantidad, :tipo, :fecha, :serie, :tiendas_id)");
			//preparacion de parametros
			$stmt->bindParam(":id_producto", $data['id_producto']);
			$stmt->bindParam(":id_usuario", $data['id_usuario']);
			$stmt->bindParam(":cantidad", $data['cantidad']);
			$stmt->bindParam(":tipo", $data['tipo']);
			$stmt->bindParam(":fecha", $data['fecha']);
			$stmt->bindParam(":serie", $data['serie']);
			$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
			if($stmt->execute())
				return "success";
			else
				return "erorr";
		}else{

			$stmt = Conexion::conectar()->prepare("SELECT stock - :cant FROM productos WHERE id = :id_producto and tiendas_id = :tiendas_id");
			$stmt->bindParam(":cant", $data['cantidad']);
			$stmt->bindParam(":id_producto", $data['id_producto']); //colocar parametros
			$stmt->bindParam(":tiendas_id", $data['tiendas_id']); //colocar parametros
			$stmt->execute();
			$result = $stmt->fetchAll();


			if($result[0][0] >= 0 || $data['tipo'] == "Entrada"){
				$stmt = Conexion::conectar()->prepare("INSERT INTO transaccion(id_producto, id_usuario, cantidad, tipo, fecha, serie, tiendas_id) VALUES(:id_producto, :id_usuario, :cantidad, :tipo, :fecha, :serie, :tiendas_id)");
				//preparacion de parametros
				$stmt->bindParam(":id_producto", $data['id_producto']);
				$stmt->bindParam(":id_usuario", $data['id_usuario']);
				$stmt->bindParam(":cantidad", $data['cantidad']);
				$stmt->bindParam(":tipo", $data['tipo']);
				$stmt->bindParam(":fecha", $data['fecha']);
				$stmt->bindParam(":serie", $data['serie']);
				$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
				
				if($stmt->execute()) {//ejecucion
					//actualizar el producto segun la cantidad ingresada
					if($data['tipo']=="Entrada"){
						$stmt = Conexion::conectar()->prepare("UPDATE productos SET stock = stock + :cantidad WHERE id = :id_producto and tiendas_id = :tiendas_id"); //preparar la consulta
					}else if($data["tipo"]=="Salida"){
						$stmt = Conexion::conectar()->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id = :id_producto and tiendas_id = :tiendas_id"); //preparar la consulta
					}
					$stmt->bindParam(":id_producto", $data['id_producto']); //colocar parametros
					$stmt->bindParam(":cantidad", $data['cantidad']); //colocar parametros
					$stmt->bindParam(":tiendas_id", $data['tiendas_id']); //colocar parametros
					if($stmt->execute()){ //ejecutar la consulta
						return "success"; //respuesta final
					}else{
						return "error";
					}
				}
				else{
					return "error";
				}
			}else{
				return "nostock";
			}

		}

		
		$stmt->close();
	}

	//metodo registroCategoriaModel: dado un arreglo asociativo de datos, se inserta en la tabla categorias los datos especificados
	public function registroCategoriaModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO categorias(nombre, tiendas_id) VALUES(:nombre, :tiendas_id)");
		//preparacion de parametros
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroUsuarioModel: dado un arreglo asociativo de datos, se inserta en la tabla usuarios los datos especificados
	public function registroUsuarioModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO usuarios(user,password,fecha_registro, tiendas_id) VALUES(:username, :password, :fecha, :tiendas_id)");
		//preparacion de parametros
		$stmt->bindParam(":username", $data['username']);
		$stmt->bindParam(":password", $data['password']);
		$stmt->bindParam(":fecha", $data['fecha_registro']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroTiendaModel: dado un arreglo asociativo de datos, se inserta en la tabla tiendas los datos especificados
	public function registroTiendaModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO tiendas(nombre,direccion,fecha_registro) VALUES(:nombre, :direccion, :fecha)");
		//preparacion de parametros
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":direccion", $data['direccion']);
		$stmt->bindParam(":fecha", $data['fecha_registro']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroVentaModel: dado un arreglo asociativo de datos, se inserta en la tabla ventas los datos especificados
	public function registroVentaModel($data){
		$stmt = Conexion::conectar()->prepare("INSERT INTO ventas(fecha,id_usuario,tiendas_id, total) VALUES(:fecha, :id_usuario, :tiendas_id, :total)");
		//preparacion de parametros
		$stmt->bindParam(":fecha", $data['fecha']);
		$stmt->bindParam(":id_usuario", $data['id_usuario']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		$stmt->bindParam(":total", $data['total']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}

	//metodo registroProductoEnVentaModel: dado un arreglo asociativo de datos, se inserta en la tabla ventas_productos los datos especificados
	public function registroProductoEnVentaModel($data){
		//actualizar stock
		$stmt = Conexion::conectar()->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id=:id_producto");
		$stmt->bindParam(":cantidad", $data['cantidad']);
		$stmt->bindParam(":id_producto", $data['id_producto']);
		$stmt->execute();
		$stmt = Conexion::conectar()->prepare("INSERT INTO ventas_productos(id_producto,id_venta,subtotal, cantidad) VALUES(:id_producto, :id_venta, :subtotal, :cantidad)");
		//preparacion de parametros
		$stmt->bindParam(":id_producto", $data['id_producto']);
		$stmt->bindParam(":id_venta", $data['id_venta']);
		$stmt->bindParam(":subtotal", $data['subtotal']);
		$stmt->bindParam(":cantidad", $data['cantidad']);
		if($stmt->execute()) //ejecucion
			return "success"; //respuesta
		else
			return "error";
		$stmt->close();
	}






	//metodo getRegModel: dado un id de un registro y el nombre de la tabla se retorna la informacion del id asociado
	public function getRegModel($id, $table, $tiendas_id){
		//en base al nombre de la tabla se define el nombre de la llave primaria de la tabla
		if($table=="productos" || $table == "usuarios" || $table == "categorias" || $table == "tiendas" || $table == "ventas_productos" || $table == "ventas"  ){
			$idName = "id";
		}
		if($table == "tiendas" || $table == "usuarios"){ //cuando se pide un registro de tienda, esta no contiene el id de tiendas_id porque es la tabla en la que se basa todo
			//se prepara la consulta sql
			if($table == "tiendas"){
					$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE $idName = :id and deleted = 0 and active=1");
					$stmt->bindParam(":id",$id); //se asocia el parametro 
			}else{
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE $idName = :id and deleted = 0");
					$stmt->bindParam(":id",$id); //se asocia el parametro 
			}
			
		}else    
    	{
			//se prepara la consulta sql
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE $idName = :id and deleted = 0 and tiendas_id = :tiendas_id");
		$stmt->bindParam(":id",$id); //se asocia el parametro 
		$stmt->bindParam(":tiendas_id",$tiendas_id); //se asocia el parametro 
		}
    	
		$stmt->execute(); //se ejecuta la consulta
		return $stmt->fetch(); //se retorna el resultado de la consulta
		$stmt->close();
	}

	//metodo getQueryFromX: dado un nombre de tabla, un nombre de campo, un operador (=, >, >=, <, <= ) y un valor, se obtienen todos los registros que cumplen este requisito de la tabla dada
	public function getQueryFromX($table, $field,$operator ,$equals, $tiendas_id){
    	//se prepara la consulta sql
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE $field $operator :equals and deleted = 0 and tiendas_id = $tiendas_id ORDER BY id DESC");
		$stmt->bindParam(":equals",$equals); //se asocia el parametro 
		$stmt->execute(); //se ejecuta la consulta
		return $stmt->fetchAll(); //se retorna el resultado de la consulta
		$stmt->close();
	}


	//metodo getLastVenta: retorna la ultima venta registrada
	public function getLastIndexInX($table, $tiendas_id){
    	//se prepara la consulta sql
		$stmt = Conexion::conectar()->prepare("SELECT MAX(id) FROM $table WHERE deleted = 0 and tiendas_id = :tiendas_id");
		$stmt->bindParam(":tiendas_id",$tiendas_id); //se asocia el parametro 
		$stmt->execute(); //se ejecuta la consulta
		return $stmt->fetch(); //se retorna el resultado de la consulta
		$stmt->close();
	}


	//metodo actualizarProductoModel: dado un array de datos y un id de un producto, se actualizan los datos de este con los datos mandados
	public function actualizarProductoModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE productos SET codigo=:codigo, nombre = :nombre, id_categoria=:categoria,  descripcion = :descripcion, precio_unitario = :precio_unitario WHERE id = $id and tiendas_id = :tiendas_id");
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":codigo", $data['codigo']);
		$stmt->bindParam(":descripcion", $data['descripcion']);
		$stmt->bindParam(":precio_unitario", $data['precio_unitario']);
		$stmt->bindParam(":categoria", $data['categoria']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();


	}

	//metodo actualizarCategoriaModel: dado un array de datos y un id de una categoria, se actualizan los datos de este con los datos mandados
	public function actualizarCategoriaModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE categorias SET nombre=:nombre WHERE id = $id and tiendas_id = :tiendas_id");
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();


	}

	//metodo actualizarUsuarioModel: dado un array de datos y un id de un usuario, se actualizan los datos de este con los datos mandados
	public function actualizarUsuarioModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE usuarios SET user=:username, password=:password WHERE id = $id and tiendas_id = :tiendas_id");
		$stmt->bindParam(":username", $data['username']);
		$stmt->bindParam(":password", $data['password']);
		$stmt->bindParam(":tiendas_id", $data['tiendas_id']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();


	}

	//metodo actualizarTiendaModel: dado un array de datos y un id de un usuario, se actualizan los datos de este con los datos mandados
	public function actualizarTiendaModel($data, $id){
		$stmt = Conexion::conectar()->prepare("UPDATE tiendas SET nombre=:nombre, direccion=:direccion WHERE id = $id");
		$stmt->bindParam(":nombre", $data['nombre']);
		$stmt->bindParam(":direccion", $data['direccion']);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();


	}



	//metodo borrarXModel: dado un id de un registro y un nombre de tabla se realiza la actualizacion del campo deleted en la base de datos de cualquier tabla existente
	public function borrarXModel($id, $table, $tiendas_id){
		//se define el nombre de la llave principal segun el nombre de la tabla especificado
		if($table=="productos" || $table=="categorias" || $table=="usuarios" || $table=="tiendas" || $table=="ventas"){
			$idName = "id";
		}
		if($table == "categorias"){//borrar los productos tambien (borrado logico)
			$stmt = Conexion::conectar()->prepare("UPDATE $table SET deleted=1 WHERE $idName = :id and tiendas_id = :tiendas_id"); //actualizar a 1 el campo deleted de la tabla
			$stmt->bindParam(":id",$id); //se asocia el parametro indicado
			$stmt->bindParam(":tiendas_id",$tiendas_id); //se asocia el parametro indicado
			$stmt->execute();

			$stmt = Conexion::conectar()->prepare("UPDATE productos SET deleted=1 WHERE id_categoria = :id and tiendas_id = :tiendas_id"); //actualizar a 1 el campo deleted de la tabla
			$stmt->bindParam(":id",$id); //se asocia el parametro indicado
			$stmt->bindParam(":tiendas_id",$tiendas_id); //se asocia el parametro indicado
			//$stmt->execute();
		}else{
			if($table == "tiendas"){ //se verifica si se borrara alguna tienda
				$stmt = Conexion::conectar()->prepare("UPDATE $table SET deleted=1 WHERE $idName = :id"); //actualizar a 1 el campo deleted de la tabla
				$stmt->bindParam(":id",$id); //se asocia el parametro indicado
			}else{
				$stmt = Conexion::conectar()->prepare("UPDATE $table SET deleted=1 WHERE $idName = :id and tiendas_id = :tiendas_id"); //actualizar a 1 el campo deleted de la tabla
				$stmt->bindParam(":id",$id); //se asocia el parametro indicado
				$stmt->bindParam(":tiendas_id",$tiendas_id); //se asocia el parametro indicado
			}
			
		}
		if($stmt->execute()){ //se ejecuta la consulta
				return "success"; //se retorna la respuesta
			}else{
				return "error";
			}
		$stmt->close();
	}


	//funncion que en base un id de una tienda la desactiva (cambia el valor de active de 1 a 0)
	public function desactivateTiendaModel($id){
		$stmt = Conexion::conectar()->prepare("UPDATE tiendas SET active=0 WHERE id = :id"); //actualizar a 1 el campo deleted de la tabla
		$stmt->bindParam(":id",$id); //se asocia el parametro indicado
		if($stmt->execute()){
			return "success";
		}else{
			return "error";
		}
		$stmt->close();


	}

	//funncion que en base un id de una tienda la desactiva (cambia el valor de active de 1 a 0)
	public function activateTiendaModel($id){
		$stmt = Conexion::conectar()->prepare("UPDATE tiendas SET active=1 WHERE id = :id"); //actualizar a 1 el campo deleted de la tabla
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