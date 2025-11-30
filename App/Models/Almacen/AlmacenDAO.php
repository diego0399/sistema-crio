<?php
require_once(__DIR__ . '/../ConexionSql.php'); //Importamos la clase conexion
require_once 'InterfazAlmacenDAO.php'; //Importamos la interfaz que contiene los métodos
class AlmacenDAO implements InterfazAlmacenDAO
{ //Se crea la clase AlmacenDAO y se implementa la InterfazAlmacenDAO para tener acceso a los métodos de esa interfaz.

    public function AgregarAlmacen(Almacen $a) //Método agregar nuevo almacén
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_AgregarBodega(?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_AgregarBodega
            $st->bindValue(1, $a->getDireccion(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde a la direccion
            $st->bindValue(2, $a->getTelefono(), PDO::PARAM_STR); //Se manda el valor del teléfono al segundo parametro
            $st->bindValue(3, $a->getEstado(), PDO::PARAM_BOOL); //Se manda el valor del estado al tercer parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $a->setDireccion($ex->getMessage());
            return $a;
        }
    }

    public function ActualizarAlmacen(Almacen $a) //Método actualizar almacén
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ActualizarBodega(?,?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_ActualizarBodega
            $st->bindValue(1, $a->getIdbodega(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al id del almacen que se actualizara
            $st->bindValue(2, $a->getDireccion(), PDO::PARAM_STR);  //Se manda el valor de la direccion al segundo parametro
            $st->bindValue(3, $a->getTelefono(), PDO::PARAM_STR); //Se manda el valor del teléfono al tercer parametro
            $st->bindValue(4, $a->getEstado(), PDO::PARAM_BOOL); //Se manda el valor del estado al cuarto parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $a->setDireccion($ex->getMessage());
            return $a;
        }
    }

    public function EliminarAlmacen(Almacen $a) //Método encargado de eliminar almacenes
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_EliminarBodega(?)'); //Se llama al procedimiento almacenado de la BD SP_EliminarBodega
            $st->bindValue(1, $a->getIdbodega(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al Id del almacen que se eliminara.

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $a->setDireccion($ex->getMessage());
            return $a;
        }
    }

    public function ListarBodegas() //Método encargado de Listar Bodegas
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ListarBodegas()'); //Se llama al procedimiento almacenado de la BD SP_ListarBodegas
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        } catch (Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
        {
            return null;
        }
    }

    public function ListarProductosBodega(Almacen $a) //Método encargado de los productos de una bodega
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar();  //Abrir conexion a la BD 
            $st = $conex->prepare('CALL SP_ListarProductosBodega(?)'); //Se llama al procedimiento almacenado de la BD SP_ListarProductosBodega
            $st->bindValue(1, $a->getIdbodega(), PDO::PARAM_INT); //Se manda el id de la bodega que se desea conocer que productos contiene.
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array   
        } catch (Exception $ex) { //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
            return null;
        }
    }
}
