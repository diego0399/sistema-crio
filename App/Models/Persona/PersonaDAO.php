<?php
require_once(__DIR__ .'/../ConexionSql.php'); //Importamos la clase conexion
require_once 'InterfazPersonaDAO.php'; //Importamos la interfaz que contiene los métodos
class PersonaDAO implements InterfazPersonaDAO{ //Se crea la clase PersonaDAO y se implementa la interfazPersonaDAO para tener acceso a los métodos de esa interfaz
    
    public function ValidarUsuario(Persona $p){ //Método que validará inicio de sesión recibe como argumento un objeto de clase persona
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
            $conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_ValidarUsuario(?,?,?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, $p->getLogin(), PDO::PARAM_STR); //Se manda un valor al primer parametro en este caso corresponde a Login
            $st->bindValue(2, $p->getPassword(), PDO::PARAM_STR); //Se manda un valor al segundo parametro  en este caso es la contraseña
            $st->bindValue(3, "Cri0EdP", PDO::PARAM_STR); //Se manda la clave del patrón de encriptacion de la clave
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs=$st->fetchAll(PDO::FETCH_OBJ); //Se guarda el resultado del procedimiento almacenado en un array
            
            foreach($rs as $rs){ //Se recorre el array rs y creamos un array el cual extraera la información puede ser nombrado de la misma manera o de diferente forma
                $r=$rs->Idusuario; //Guardamos el primer Idusuario devuelto para validar si el inicio de sesión fue exitoso recordando que en la bd esta validado que si regresa -1 el login no existe en la db y -2 la contraseña esta incorrecta
                if($r>0){ //Si el IDUsuario no es -1 o -2 el inicio de sesión se logro correctamente y se procede a guardar la informacion del usuario en un objeto
                    $p->setIdUsuario($rs->Idusuario);
                    $p->setNombre($rs->Nombre);
                    $p->setLogin($rs->Login);
                    $p->setPassword($rs->Contra);
                    $p->setEmail($rs->Email);
                    $p->setEstado($rs->Estado);
                    $p->setIdPrivilegio($rs->Idprivilegio);
                    $p->setFecha($rs->Fecha);
                }
                else{ //Si ocurre esto es porque el inicio de sesión no se pudo lograr
                    $p->setIdUsuario($rs->Idusuario); 
                }
            }
            $conex=$ConexionSql->cerrar(); //Se cierra la conexión de la BD
            return $p; //Retornamos el objeto 
        }
        catch(Exception $ex){ //Si esto sucede hay un conflicto con la conexión de la BD
            $p->setNombre($ex->getMessage());
            return $p;
        }
    }

    public function AgregarUsuario(Persona $p){ //Método encargado de agregar nuevos usuarios
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
            $conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_AgregarUsuario(?,?,?,?,?,?,?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, $p->getNombre(), PDO::PARAM_STR); //Se captura el nombre del usuario.
            $st->bindValue(2, $p->getLogin(), PDO::PARAM_STR); //Se captura el login del usuario.
            $st->bindValue(3, $p->getPassword(), PDO::PARAM_STR); //Se captura la contraseña del usuario.
            $st->bindValue(4, $p->getEmail(), PDO::PARAM_STR); //Se captura el email del usuario.
            $st->bindValue(5, $p->getEstado(), PDO::PARAM_BOOL); //Se captura el estado del usuario.
            $st->bindValue(6, $p->getIdPrivilegio(), PDO::PARAM_INT); //Se captura el id del privilegio del usuario.
            $st->bindValue(7, "Cri0EdP", PDO::PARAM_STR); //Se manda la clave del patrón de encriptacion de la clave
        
            $st->execute();  //Se ejecuta el procedimiento almacenado
            $rs=$st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex=$ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento  
        }
        catch (Exception $ex){ //Si existe un error al ejecutar el procedimiento almacenado retornar -3
            return -3;
        }
    }

    public function ActualizarUsuario(Persona $p){ //Método encargado de actualizar usuarios
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
            $conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_ActualizarUsuario(?,?,?,?,?,?,?,?,?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, $p->getIdUsuario(), PDO::PARAM_INT); //Captura el id del usuario que se actualizara
            $st->bindValue(2, $p->getOpcion(), PDO::PARAM_INT);  //Captura el tipo de opcion que se realizara si es actualizar mi perfil o actualizar usuarios.
            $st->bindValue(3, $p->getNombre(), PDO::PARAM_STR); //Captura nombre del usuario
            $st->bindValue(4, $p->getLogin(), PDO::PARAM_STR); //Captura login del usuario
            $st->bindValue(5, $p->getPassword(), PDO::PARAM_STR); //Captura contraseña del usuario
            $st->bindValue(6, "Cri0EdP", PDO::PARAM_STR); //Se manda la clave del patrón de encriptacion de la clave
            $st->bindValue(7, $p->getEmail(), PDO::PARAM_STR); //Se captura el email del usuario.
            $st->bindValue(8, $p->getEstado(), PDO::PARAM_BOOL); //Se captura el estado del usuario.
            $st->bindValue(9, $p->getIdPrivilegio(), PDO::PARAM_INT); //Se captura el id del privilegio del usuario.
            
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs=$st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex=$ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento  
        }
        catch (Exception $ex){ //Si existe un error al ejecutar el procedimietno almacenado retornar -3
            return -3;
        }
    }

    public function EliminarUsuario(Persona $p){ //Método encargado de eliminar usuario usuarios
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
            $conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_EliminarUsuario(?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, $p->getIdUsuario(), PDO::PARAM_INT); //Se captura id del usuario a eliminar
 
            $st->execute(); //Se ejecuta el procedimiento almacenado
            $rs=$st->fetchColumn(); //Se captura la respuesta que arroja el procedimiento almacenado
            $conex=$ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Se retorna la respuesta de ejecutar el procedimiento 
        }
        catch (Exception $ex){ //Si existe un error al ejecutar el procedimietno almacenado retornar -2
            return -2;
        }
    }

    public function ListarUsuarios(){ //Listara los usuarios creados en la BD
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
			$conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_ListarUsuarios(?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, "Cri0EdP", PDO::PARAM_STR); //Se manda la clave del patrón de encriptacion de la clave
            $st->execute(); //Ejecuta el procedimiento almacenado ListarUsuarios
            $rs=$st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex=$ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        }
        catch(Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara
        {
            return null;
        }
    }
    public function ListarPrivilegios(){ //Listara los privilegios creados en la BD
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
			$conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_ListarPrivilegios()'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->execute(); //Ejecuta el procedimiento almacenado ListarPrivilegios
            $rs=$st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex=$ConexionSql->cerrar();
            return $rs; //Retornar array    
        }
        catch(Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara
        {
            return null;
        }
    }

    public function ListarDatosgenerales(Persona $p) //Listara datos generales del sistema
    {
        try{
            $ConexionSql=new ConexionSql(); //Se instancia la clase conexion SQL
			$conex=$ConexionSql->conectar(); //Se abre la conexión a la BD
            $st=$conex->prepare('CALL SP_Datosgenerales(?)'); //Se llama al procedimiento almacenado que se ejecutara de la BD
            $st->bindValue(1, $p->getOpcion(), PDO::PARAM_INT); //Se manda el tipo de informacion que sea desea obtener
            $st->execute(); //Ejecuta el procedimiento almacenado ListarDatosgenerales
            $rs=$st->fetchAll(PDO::FETCH_OBJ); //Se guarda la información en un array
            $conex=$ConexionSql->cerrar(); //Se cierra la conexion
            return $rs; //Retornar array    
        }
        catch(Exception $ex) //Si existe algun problema al ejecutar el procedimiento almacenado se retornara null
        {
            return null;
        }
    }
}
