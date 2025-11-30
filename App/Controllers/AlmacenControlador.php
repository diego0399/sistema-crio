<?php
session_start();
require_once(__DIR__ . '/../Models/Almacen/Almacen.php'); //Importar clase Almacen
require_once(__DIR__ . '/../Models/Almacen/AlmacenDAO.php'); //Importar clase AlmacenDAO
class AlmacenControlador extends Controller //Crear clase AlmacenControlador y recibir herencia de la clase Controller para disponer método render
{
    public function listar() //Método listar
    {
        if (isset($_SESSION['persona'])) {  //Si existe una sesion hacer:
            $dao = new AlmacenDAO(); //Instancia clase AlmacenDAO
            if (isset($_POST['txtIdbodega'])) { //Cargar lista de Productos de una bodega en especifico si se esta solicitando
                $a = new Almacen(); //Instanciar clase almacen
                $a->setIdbodega($_POST['txtIdbodega']); //Captura Id de la bodega que sea desea ver los productos que contiene
                $array = array( // Crear array y una propiedad Bodegaproductos que tendra los productos de la bodega que se esta pidiendo
                    "Bodegaproductos" => array($dao->ListarProductosBodega($a)) //Ejecutar método de la clase AlmacenDAO que llama al procedimiento almacenado SP_ListarBodegaProductos de la BD
                );
                echo json_encode($array); //Imprimir en formato de JSON el array
            } else { //Si no se esta requiriendo mostrar los productos de una bodega hacer:
                $array = array( //Crear array y crear una propiedad Bodegas que tendra los datos de todas las bodegas que hay en el sistema
                    "Bodegas" => array($dao->ListarBodegas()) //Ejecutar método de la clase AlmacenDAO que llama al procedimiento almacenado SP_ListarBodegas de la BD
                );
                echo json_encode($array); //Imprimir en formato de JSON el array
            }
        } else { //Si no existe una sesion redigir a login
            $this->render('Persona', 'index.php');
        }
    }

    public function agregar() //Metodo agregar 
    {
        if (empty($_POST['txtDireccion']) || empty($_POST['txtTelefono'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
            if (isset($_SESSION['persona'])) { //Si existe una sesion se redigira a la ultima página abierta
                $url = explode('/', $_SESSION['url']);
                $this->render($url[4], $url[5]);
            } else { //Si no se encuentra una sesion abierta se retorna al login
                $this->render('Persona', 'index.php');
            }
        } else { //Si el formulario se recibe con todos los datos completos hacer:
            $a = new Almacen(); //Instanciar clase Almacen
            $dao = new AlmacenDAO(); //Instanciar AlmacenDAO
            $a->setDireccion($_POST['txtDireccion']); //Capturar dato de la direccion
            $a->setTelefono($_POST['txtTelefono']); //Capturar dato del telefono
            if (isset($_POST['chkEstado'])) { //Captura estado del usuario
                $a->setEstado(1); //TRUE
            } else {
                $a->setEstado(0); //FALSE
            }
            $r = $dao->AgregarAlmacen($a); //Ejecutar método agregar de la clase AlmacenDAO
            $res = explode('/', $r); //extraer respuesta devuelta del procedimiento almacenado
            if ($res[1] > 0) { //Si el procedmiento almacenado se ejecuto sin problemas hacer:
                echo json_encode(array('Respuesta' => $res[1], 'Codigo' => $res[0])); //Imprimir en formato de JSON el valor de la respuesta y el Código del almacen
            } else { //Si no se ejecuto correctamente
                echo json_encode(array('Respuesta' => $res[1], 'Codigo' => $res[0])); //Imprimir en formato de JSON el valor de la respuesta y el Código del almacen
            }
        }
    }

    public function modificar() //Método modificar almacen
    {
        if (empty($_POST['txtDireccion']) || empty($_POST['txtTelefono'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
            if (isset($_SESSION['persona'])) { //Si existe una sesion se redigira a la ultima página abierta
                $url = explode('/', $_SESSION['url']);
                $this->render($url[4], $url[5]);
            } else { //Si no se encuentra una sesion abierta se retorna al login
                $this->render('Persona', 'index.php');
            }
        } else { //Si el formulario se recibe con todos los datos completos hacer:
            $a = new Almacen(); //Instanciar clase Almacen
            $dao = new AlmacenDAO(); //Instanciar AlmacenDAO
            $a->setIdbodega($_POST['txtIdbodega']); //Capturar Id de la bodega que se actualizara
            $a->setDireccion($_POST['txtDireccion']); //Capturar nombre de la direccion
            $a->setTelefono($_POST['txtTelefono']); //Capturar dato del telefono
            if (isset($_POST['chkEstado'])) { //Captura estado del usuario
                $a->setEstado(1); //TRUE
            } else {
                $a->setEstado(0); //FALSE
            }
            $r = $dao->ActualizarAlmacen($a); //Ejecutar método actualizar de la clase AlmacenDAO
            $res = explode('/', $r); //extraer respuesta devuelta del procedimiento almacenado
            if ($res[1] > 0) { //Si el procedmiento almacenado se ejecuto sin problemas hacer:
                echo json_encode(array('Respuesta' => $res[1], 'Codigo' => $res[0])); //Imprimir en formato de JSON el valor de la respuesta y el Código del almacen
            } else {
                echo json_encode(array('Respuesta' => $res[1], 'Codigo' => $res[0])); //Imprimir en formato de JSON el valor de la respuesta y el Código del almacen
            }
        }
    }

    public function eliminar() //Método eliminar almacen
    {
        if (empty($_POST['txtIdbodega'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
            if (isset($_SESSION['persona'])) {
                $url = explode('/', $_SESSION['url']);
                $this->render($url[4], $url[5]);
            } else { //Si no se encuentra una sesion abierta se retorna al login
                $this->render('Persona', 'index.php');
            }
        } else { //Si el formulario se recibe con todos los datos completos hacer:
            $a = new Almacen(); //Instanciar clase almacen
            $dao = new AlmacenDAO(); //Instanciar clase AlmacenDAO
            $a->setIdbodega($_POST['txtIdbodega']); //Captura Id del almacen que sera eliminado
            $r = $dao->EliminarAlmacen($a); //Ejecutar método actualizar de la clase AlmacenDAO
            echo json_encode($r); //Imprimir en formato JSON la respuesta devuelta del método
        }
    }
}
