<?php
session_start();
require_once(__DIR__ . '/../Models/Persona/PersonaDAO.php'); //Importar clase PersonaDAO
require_once(__DIR__ . '/../Models/Persona/Persona.php'); //Importar clase Persona
class PersonaControlador extends Controller //Clase controlador persona recibe herencia de la clase Controller para tener acceso al método render encargado de redigir a ubicaciones que se le diga.
{
  public function index() //Método inicio
  {
    if (isset($_POST['txtLogin']) || isset($_POST['txtPassword'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      $p = new Persona(); //Crear una instancia de la clase Persona
      $p->setLogin($_POST['txtLogin']); //Enviar la información traida del input login a la variable Login de la clase persona.
      $p->setPassword($_POST['txtPassword']); //Enviar la información traida del input password a la variable password de la clase persona.
      $dao = new PersonaDAO();  //Crear una instancia de la clase PersonaDAO para poder usar los métodos que llaman a la BD
      $p = $dao->ValidarUsuario($p); //Igualamos nuestro objeto p con el método ValidaUsuario de la clase PersonaDAO el cual devuelve un objeto y se le manda como argumento nuestro objeto p anterior
      if ($p->getIdUsuario() > 0) { //Si validarUsuario devuelve un numero mayor que cero el procedimiento almacenado encontró la información que se envío en la BD
        $_SESSION['persona'] = serialize($p); //Guardamos en una variable de sesión nuestro nuevo objeto p
        $_SESSION['logueo'] = 1; //Creamos una variable de sesión que verifiqué que nos logueamos con exito
        $_SESSION['logueoalert'] = 1; //Esta variable de sesión se encargara de ejecutar la alerta de bienvenida
        echo json_encode(array('error' => false, 'Validacion' => $p->getIdUsuario())); //imprimimos nuestro json que sera recolectado por la solicitud del cliente AJAX, contiene el codigo para loguearse
      } else {
        echo json_encode(array('error' => true, 'Validacion' => $p->getIDusuario()));  //imprimimos nuestro json que sera recolectado por la solicitud del cliente AJAX, contiene el codigo del error que impide loguearse
      }
    } else {
      if (isset($_SESSION['persona'])) { //Si ya existe una sesion redigir al tablero
        $this->render('Persona', 'tablero.php');
      } else { //Si no existe una sesion redigir a login
        $this->render('Persona', 'index.php');
      }
    }
  }

  public function listar()
  {
    if (isset($_SESSION['persona'])) { //Si existe una sesion hacer:
      $dao = new PersonaDAO(); //Crear una instancia personaDAO
      if (isset($_POST['txtOpcion'])) { //Cargar listado de datos generales si se esta requiriendo
        $p = new Persona(); //Instanciar clase persona
        $p->setOpcion($_POST['txtOpcion']); //Capturar dato enviado
        $array = array( //Crear array y crear una propiedad Datosgenerales que almacenara los datosgenerales
          "Datosgenerales" => $dao->ListarDatosgenerales($p) //Ejecutar método de la clase PersonaDAO que llama al procedimiento almacenado SP_ListarDatosgenerales de la BD
        );
        echo json_encode($array); //Imprimir en formato de JSON el array
      } else { //Si no se esta requiriendo mostrar datos generales hacer:
        $array = array( //Crear un array con las propiedades Usuarios y privilegios, setearlos con el método encargado de llamar a los procedimientos almacenados correspondientes.
          "Usuarios" => array($dao->ListarUsuarios()),
          "Privilegios" => array($dao->ListarPrivilegios())
        );
        echo json_encode($array); //Imprimir en formato de JSON el array
      }
    } else { //Si no existe una sesion redirigir a login
      $this->render('Persona', 'index.php');
    }
  }

  public function agregar() //Método agregar
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtPassword']) || empty($_POST['txtEmail']) || empty($_POST['cboPrivilegio'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Persona(); //Instanciar clase Persona
      $dao = new PersonaDAO(); //Instanciar clase PersonaDAO
      $p->setNombre($_POST['txtNombre'] . " " . $_POST['txtApellido']); //Captura variable nombre y apellido
      $p->setLogin($_POST['txtLogin']); //Captura login
      $p->setPassword($_POST['txtPassword']); //Captura contraseña
      $p->setEmail($_POST['txtEmail']); //Captura email
      $p->setIdPrivilegio($_POST['cboPrivilegio']); //Captura id privilegio
      if (isset($_POST['chkEstado'])) { //Captura estado del usuario
        $p->setEstado(1);
      } else {
        $p->setEstado(0);
      }
      $r = $dao->AgregarUsuario($p);
      echo json_encode($r); //Se imprime en json la respuesta de la ejecucion del método que llama al procedimiento almacenado AgregarUsuario
    }
  }

  public function modificar() //Método modificar
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtPassword']) || empty($_POST['txtEmail']) || empty($_POST['cboPrivilegio'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else {
      $p = new Persona(); //Instanciar clase Persona
      $dao = new PersonaDAO(); //Instanciar clase PersonaDAO
      $p->setIdUsuario($_POST['txtIdusuario']); //Captura el id del usuario a modificar
      $p->setOpcion($_POST['txtOpcion']); //Se captura el tipo de opción que se realizara 1. Se modifica por completo todos los datos de los usarios y 2. Solo sirve para actualizar algunos datos del perfil del usuario.
      $p->setNombre($_POST['txtNombre'] . " " . $_POST['txtApellido']); //Captura variable nombre y apellido
      $p->setLogin($_POST['txtLogin']);  //Captura login
      $p->setPassword($_POST['txtPassword']); //Captura contraseña
      $p->setEmail($_POST['txtEmail']); //Captura email
      if ($p->getOpcion() == 1) { //Si se ha escodigo la opcion 1 hacer:
        $p->setIdPrivilegio($_POST['cboPrivilegio']); //Captura Id del privilegio
        if (isset($_POST['chkEstado'])) { //Captura estado del usuario
          $p->setEstado(1);
        } else {
          $p->setEstado(0);
        }
      } else { //Si el usuario solo actualiza su perfil
        $obj = new Persona(); //Crear un objeto de la clase persona
        $obj = unserialize($_SESSION['persona']); //Asignar objeto de sesion persona
        $p->setIdPrivilegio($obj->getIdPrivilegio()); //Se captura el Privilegio actual del usuario que esta logueado
        $p->setEstado($obj->getEstado()); //Se captura el estado actual del usuario que esta logueado
        $r = $dao->ActualizarUsuario($p); 
        if($r>0)
        $_SESSION['persona'] = serialize($p); //Si se ejecuta con exito el método recargar el objeto de sesion persona
      }
      $r = $dao->ActualizarUsuario($p); //Se ejecuta el método encargado de llamar al procedimiento almacenado ActualizarUsuario
      echo json_encode($r); //Imprimir en json la respuesta del procedimiento almacenado.
    }
  }

  public function eliminar() //Método eliminar
  {
    if (empty($_POST['txtIdusuario'])) { //Valida que exista un id a eliminar si no existe hacer:
      if (isset($_SESSION['persona'])) { //Si hay una sesión abierta redirigir a la página actual.
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no hay una sesion abierta redirigir al login
        $this->render('Persona', 'index.php');
      }
    } else {
      $p = new Persona(); //Instanciar clase persona
      $dao = new PersonaDAO(); //Instanciar clase personaDAO
      $p->setIdUsuario($_POST['txtIdusuario']); //Captura Id del usuario que sera eliminado
      $r = $dao->EliminarUsuario($p); //Se ejecuta método encargado de llama al procedimiento almacenado EliminarUsuario
      echo json_encode($r); //Se imprime en json la respuesta del procedimiento almacenado.
    }
  }

  public function exit() //Método salir
  {
    session_destroy(); //Destruye todas las variables de sesión que han sido creadas
    $this->render('Persona', 'index.php'); //Redigir al login
  }
}
