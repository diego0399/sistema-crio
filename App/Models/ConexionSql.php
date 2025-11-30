<?php
class ConexionSql{ //Clase connexion;
	private $conexion; //Variable de conexion
	private $configuracion = [ //array que contiene los parametros de conexion
		"driver" => "mysql",
		"host" => "localhost",
		"database" => "crio",
		"port" => "3306",
		"username" => "root",
		"password" => "",
		"charset" => "utf8mb4"

	];

	public function conectar(){ //Método que abre la conexion a la BD
		try{
			$CONTROLADOR = $this->configuracion["driver"]; //Tipo de BD 
			$SERVIDOR=$this->configuracion["host"]; //Nombre del host
			$BASE_DATOS=$this->configuracion["database"]; //Nombre de la BD
			$PUERTO =$this->configuracion["port"]; //Puerto del controlador
			$USUARIO =$this->configuracion["username"]; //Usuario de la bd
			$CLAVE =$this->configuracion["password"]; //Contraseña del usuario de la bd
			$CODIFICACION = $this->configuracion["charset"]; //Tipo de codificacion de la BD

			$url= "{$CONTROLADOR}:host={$SERVIDOR}:{$PUERTO};"."dbname={$BASE_DATOS};charset{$CODIFICACION}"; //Cadena de conexion
		    return $this->conexion = new PDO($url,$USUARIO,$CLAVE); //Abre la conexion y se retorna
		}catch(Exception $exc){ //Si existe un problema se retornara un mensaje de error
			echo "Error en la conexión ".$exc->getMessage();
		}
	}

	public function cerrar(){ //Método que cerrara la conexion
		return $this->conexion=null; //Vaciar el contenido de la variable conexion
	}
}
?>
