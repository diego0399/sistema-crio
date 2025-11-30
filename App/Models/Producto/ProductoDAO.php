<?php
require_once(__DIR__ . '/../ConexionSql.php'); //Importamos la clase conexion
require_once 'InterfazProductoDAO.php'; //Importamos la interfaz que contiene los métodos
class ProductoDAO implements InterfazProductoDAO //Se crea la clase ProductoDAO y se implementa la interfazProductoDAO para tener acceso a los métodos de esa interfaz.
{

    public function AgregarProducto(Producto $p) //Método agregar nuevos productos
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_AgregarProducto(?,?,?,?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_AgregarProducto
            $st->bindValue(1, $p->getNombre(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde al nombre
            $st->bindValue(2, $p->getPrecio(), PDO::PARAM_STR); //Se manda el valor del precio al segundo parametro
            $st->bindValue(3, $p->getIdcategoria(), PDO::PARAM_INT); //Se manda el id de la categoria al tercer parametro
            $st->bindValue(4, $p->getImg(), PDO::PARAM_STR); //Se manda el nombre de la imagen del producto al cuarto parametro
            $st->bindValue(5, $p->getDescripcion(), PDO::PARAM_STR); //Se manda la descripcion del producto al quito parametro
            $st->bindValue(6, $p->getIdusuario(), PDO::PARAM_INT); //Se manda el id del usuario que esta registrando el producto al sexto parametro.

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function AgregarCategoria(Producto $p) //Método agregar nuevas categoria
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_AgregarCategoria(?,?)'); //Se llama al procedimiento almacenado de la BD SP_AgregarCategoria
            $st->bindValue(1, $p->getNombre(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde al nombre
            $st->bindValue(2, $p->getDescripcion(), PDO::PARAM_STR); //Se manda el valor de la descripcion de la categoria al segundo parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function ActualizarProducto(Producto $p) //Método encargado de actualizar productos
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ActualizarProducto(?,?,?,?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_ActualizarProducto
            $st->bindValue(1, $p->getIdproducto(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al Id del producto que se esta modificando.
            $st->bindValue(2, $p->getNombre(), PDO::PARAM_STR); //Se manda el valor del nombre del producto al segundo parametro
            $st->bindValue(3, $p->getPrecio(), PDO::PARAM_STR); //Se manda el valor del precio al tercer parametro
            $st->bindValue(4, $p->getIdcategoria(), PDO::PARAM_INT); //Se manda el id de la categoria al cuarto parametro
            $st->bindValue(5, $p->getImg(), PDO::PARAM_STR); //Se manda el nombre de la imagen del producto al quinto parametro
            $st->bindValue(6, $p->getDescripcion(), PDO::PARAM_STR); //Se manda el valor de la descripcion del producto al sexto parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar();  //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento 
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function ActualizarCategoria(Producto $p) //Método encargado de actualizar categorias
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ActualizarCategoria(?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_ActualizarCategoria
            $st->bindValue(1, $p->getIdcategoria(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al Id de la categoria que se esta modificando.
            $st->bindValue(2, $p->getNombre(), PDO::PARAM_STR); //Se manda el valor del nombre de la categoria al segundo parametro
            $st->bindValue(3, $p->getDescripcion(), PDO::PARAM_STR); //Se manda el valor de la descripcion de la categoria al tercer parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento 
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function EliminarProducto(Producto $p) //Método encargado de eliminar productos
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_EliminarProducto(?)'); //Se llama al procedimiento almacenado de la BD SP_EliminarProducto
            $st->bindValue(1, $p->getIdproducto(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al Id del producto que se eliminara.

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento  
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function EliminarCategoria(Producto $p) //Método encargado de eliminar categorias
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_EliminarCategoria(?)'); //Se llama al procedimiento almacenado de la BD SP_EliminarCategoria
            $st->bindValue(1, $p->getIdcategoria(), PDO::PARAM_INT); //Se manda un valor al primer parametro en este caso corresponde al Id de la categoria que se eliminara.

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento  
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function AgregarExistencia(Producto $p) //Método encargado de agregar existencias
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_AgregarExistencia(?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_AgregarExistencia
            $st->bindValue(1, $p->getExistencias(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde a las existencias del producto.
            $st->bindValue(2, $p->getIdbodega(), PDO::PARAM_INT); //Se manda el Id de la bodega al segundo parametro
            $st->bindValue(3, $p->getIdproducto(), PDO::PARAM_INT); //Se manda el Id del producto al tercer parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento 
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setIdproducto($ex->getMessage());
            return $p;
        }
    }

    public function ActualizarExistencia(Producto $p) //Método encargado de actualizar existencias
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ActualizarExistencia(?,?,?)'); //Se llama al procedimiento almacenado de la BD SP_ActualizarExistencia
            $st->bindValue(1, $p->getExistencias(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde a las existencias del producto.
            $st->bindValue(2, $p->getIdbodega(), PDO::PARAM_INT); //Se manda el Id de la bodega al segundo parametro
            $st->bindValue(3, $p->getIdproducto(), PDO::PARAM_INT); //Se manda el Id del producto al tercer parametro

            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento  
        } catch (Exception $ex) { //Si existe un error al ejecutar el procedimiento almacenado retornar el mensaje de error
            $p->setIdproducto($ex->getMessage());
            return $p;
        }
    }

    public function ListarProductos() //Método encargado de Listar productos
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ListarProductos()'); //Se llama al procedimiento almacenado de la BD SP_ListarProductos
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        } catch (Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
        {
            return null;
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

    public function ListarCategorias() //Método encargado de Listar Categorias
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ListarCategorias()'); //Se llama al procedimiento almacenado de la BD SP_ListarCategorias
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        } catch (Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
        {
            return null;
        }
    }

    public function ListarExistencias(Producto $p) //Método encargado de Listar las existencias de un Producto
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ListarExistencias(?)'); //Se llama al procedimiento almacenado de la BD SP_ListarExistencias
            $st->bindValue(1, $p->getIdproducto(), PDO::PARAM_INT); //Se manda el id del producto que se desea conocer las existencias.
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        } catch (Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
        {
            return null;
        }
    }

    public function ListarProductosCategoria(Producto $p) //Método encargado de Listar Productos por categoria
    {
        try {
            $ConexionSql = new ConexionSql(); //Instanciar clase conexion
            $conex = $ConexionSql->conectar(); //Abrir conexion a la BD
            $st = $conex->prepare('CALL SP_ListarProductosCategoria(?)'); //Se llama al procedimiento almacenado de la BD SP_ListarExistencias
            $st->bindValue(1, $p->getIdcategoria(), PDO::PARAM_INT); //Se manda el id de la categoria que se desea ver que productos contiene.
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs = $st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex = $ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array  
        } catch (Exception $ex) { //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
            return null;
        }
    }
}
