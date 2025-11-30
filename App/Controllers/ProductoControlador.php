<?php
session_start();
require_once(__DIR__ . '/../Models/Producto/ProductoDAO.php'); //Importar clase ProductoDAO
require_once(__DIR__ . '/../Models/Producto/Producto.php'); //Importar clase Producto
class ProductoControlador extends Controller //Clase controlador producto recibe herencia de la clase Controller para tener acceso al método render encargado de redigir a ubicaciones que se le diga y metodo Eliminardir encargado de borrar directorios.
{
  public function listar() //Método listar
  {
    if (isset($_SESSION['persona'])) { //Si existe una sesion hacer:
      $dao = new ProductoDAO(); //Instanciar clase ProductoDAO
      if (isset($_POST['txtIdproducto'])) { //Si se esta solicitando las bodegas y existencias que tiene el producto hacer:
        $p = new Producto(); //Instanciar clase producto
        $p->setIdproducto($_POST['txtIdproducto']); //Capturar Id del producto que se desea ver las bodegas y existencias que tiene
        $array = array( //Arrar que tiene la propiedad existencias que contiene las bodegas y existencia de un producto en especifico
          "Existencias" => array($dao->ListarExistencias($p)) //Ejecutar método de la clase ProductoDAO que llama al procedimiento almacenado SP_ListarExistencias de la BD
        );
        echo json_encode($array);  //Imprimir en formato de JSON el array
      } else if (isset($_POST['txtIdcategoria'])) { //Si se esta solicitando los productos que contiene una categoria hacer:
        $p = new Producto(); //Instanciar clase Producto 
        $p->setIdcategoria($_POST['txtIdcategoria']); //Capturar Id de la categoria que se quiere ver los productos que contiene
        $array = array( //Array que tiene la propiedad Categoiaproductos que tiene los productos de una categoria en especifico
          "Categoriaproductos" => array($dao->ListarProductosCategoria($p)) //Ejecutar método de la clase ProductoDAO que llama al procedimiento almacenado SP_ListarProductosCategoria de la BD
        );
        echo json_encode($array); //Imprimir en formato de JSON el array
      } else { //Si no se solicita las bodegas y existencias de un producto ni los productos de una categoria hacer:
        $array = array( //Array que contiene las propiedades Productos (almacena los productos del sistema), Bodegas (almacena las bodegas del sistema) y Categorias (guarda las categorias que hay en el sistema)
          "Productos" => array($dao->ListarProductos()),
          "Bodegas" => array($dao->ListarBodegas()),
          "Categorias" => array($dao->ListarCategorias())
        );
        $_SESSION['Listaproductos']=serialize($dao->ListarProductos()); //Variable sesion que se encargara de guardar los productos que hay en el sistema, esta variable servira para mostrar la informacion en el reporte listaproductos
        echo json_encode($array); //Imprimir array en formato de JSON
      }
    } else { //Si no existe un sesion redigir a la pagina de acceso
      $this->render('Persona', 'index.php');
    }
  }

  public function agregar() //Método agregar
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtPrecio']) || empty($_POST['cboCategorias']) || empty($_FILES["imagen"]["name"]) || empty($_POST['txtDescripcion'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Producto(); //Instancia de la clase Producto
      $dao = new ProductoDAO(); //Instancia de la clase ProductoDAO
      $p->setNombre($_POST['txtNombre']); //Captura nombre del producto
      $p->setIdcategoria($_POST['cboCategorias']); //Captura id de la categoria
      $p->setImg($_FILES["imagen"]["name"]); //Captura nombre de la imagen
      $p->setPrecio($_POST['txtPrecio']); //Captura el precio
      $p->setDescripcion($_POST['txtDescripcion']); //Captura la descripcion
      $p->setIdusuario($_POST['txtIdusuario']); //Captura el Id del usuario que inserto el registro
      $r = $dao->AgregarProducto($p); //Ejecuta al método encargado de llamar al procedimiento almacenado AgregarProducto
      if ($r > 0) { //Si el procedimiento almacenado se ejecuto con exito hacer:
        for ($i = 0; $i < count(($_POST['txtBodegaId'])); ++$i) { //Recorrer todas las bodegas existentes en la BD
          $idbodega = $_POST['txtBodegaId'][$i]; //Captura el id de cada una de las bodegas
          if (!empty($_POST['txtExistencias'][$i])) { //Valida que las exitencias no sean null si es null se le asignara valor de 0
            $existencias = $_POST['txtExistencias'][$i];
          } else {
            $existencias = 0;
          }
          $p = new Producto(); //Se creara objetos de la clase producto por cada fila que se recorra del for
          $p->setIdbodega($idbodega); //Captura el id de la bodega
          $p->setExistencias($existencias); //Captura cantidad de existencias
          $p->setIdproducto($r); //Captura el id del producto
          $codigoprod = $dao->AgregarExistencia($p); //Agrega las existencias
        }
        $path = './assets/img/Productos/' . $codigoprod; //Se creara una nueva carpeta en la ruta assets/img/Productos/ con el nombre del codigo del producto insertado.
        if (!is_dir($path)) { //Valida que la carpeta no exista
          $crear = mkdir($path, '0777', true); //Se crea la carpeta       
          if ($crear) {
            $path = './assets/img/Productos/' . $codigoprod . '/' . $_FILES["imagen"]["name"]; //Se indica donde se guardara la imagen que ha subido el usuario.
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $path); //Se realiza la accion de mover la imagen a la ruta.
          }
        }
        echo json_encode(array('Codigo' => $codigoprod, 'Validacion' => $r));  //Se imprime en json la respuesta de la ejecucion del método que llama al procedimiento almacenado AgregarProducto
      } else {
        echo json_encode(array('Validacion' => $r)); //Se imprime en json la respuesta de la ejecucion del método que llama al procedimiento almacenado AgregarProducto
      }
    }
  }

  public function agregarcategoria() //Metodo agregar categoria
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Producto(); //Instanciar clase Producto
      $dao = new ProductoDAO(); //Instanciar clase ProductoDAO
      $p->setNombre($_POST['txtNombre']); //Capturar nombre de la categoria
      $p->setDescripcion($_POST['txtDescripcion']); //Capturar descripcion de la categoria
      $r = $dao->agregarcategoria($p); //Ejecuta al método encargado de llamar al procedimiento almacenado Agregarcategoria
      echo json_encode($r); //Imprimir en JSON la respuesta de la ejecucion del metodo
    }
  }

  public function modificar() //Modificar producto
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtPrecio']) || empty($_POST['cboCategorias']) || empty($_POST['txtDescripcion'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else {
      $p = new Producto(); //Instancia de la clase Producto
      $dao = new ProductoDAO(); //Instancia de la clase ProductoDAO
      $p->setIdproducto($_POST['txtIdproducto']); //Capturar Id del producto que se esta modificando
      $p->setNombre($_POST['txtNombre']); //Captura nombre del producto
      $p->setIdcategoria($_POST['cboCategorias']); //Captura id de la categoria
      if (empty($_FILES["imagen"]["name"])) { //Valida que no se mande una nueva imagen del producto
        $p->setImg('default'); //No se recibio una nueva imagen el dato sera default
      } else { //Si se manda una nueva imagen
        $p->setImg($_FILES["imagen"]["name"]); //Captura nombre de la imagen
        $path = './assets/img/Productos/' . $_POST['txtCodProducto'] . '/' . $_FILES["imagen"]["name"]; //Agregarla al directorio del producto
        if (!is_file($path)) { //Valida que no se dupliquen las imagenes, se grabara la imagen en el directorio solo si esta imagen no se encuentra grabada en el directorio del producto.
          move_uploaded_file($_FILES["imagen"]["tmp_name"], $path); //Graba la imagen enviada del producto en el directorio del producto
        }
      }
      $p->setPrecio($_POST['txtPrecio']); //Captura el precio
      $p->setDescripcion($_POST['txtDescripcion']); //Captura la descripcion
      $r = $dao->ActualizarProducto($p); //Ejecuta al método encargado de llamar al procedimiento almacenado Actualizarproducto
      if ($r > 0) { //Si el procedimiento almacenado se ejecuto con exito hacer:
        for ($i = 0; $i < count(($_POST['txtBodegaId'])); ++$i) { //Recorrer todas las bodegas existentes en la BD
          $idbodega = $_POST['txtBodegaId'][$i]; //Captura el id de cada una de las bodegas
          if (!empty($_POST['txtExistencias'][$i])) { //Valida que las exitencias no sean null si es null se le asignara valor de 0
            $existencias = $_POST['txtExistencias'][$i];
          } else {
            $existencias = 0;
          }
          $p = new Producto(); //Se creara objetos de la clase producto por cada fila que se recorra del for
          $p->setIdbodega($idbodega); //Captura el id de la bodega
          $p->setExistencias($existencias); //Captura cantidad de existencias
          $p->setIdproducto($r); //Captura el id del producto
          $codigoprod = $dao->ActualizarExistencia($p); //Agrega las existencias
        }
        rename("./assets/img/Productos/" . $_POST['txtCodProducto'], "./assets/img/Productos/" . $codigoprod); //Se renombra el nombre del directorio del producto por si se llega actualizar la categoria del producto
        echo json_encode(array('Codigo' => $codigoprod, 'Validacion' => $r)); //Imprimir en JSON la respuesta de los métodos ejecutados
      } else { //Si ocurre un error en ejecutar los métodos hacer:
        echo json_encode(array('Validacion' => $r)); //Imprimir en JSON respuesta del error
      }
    }
  }

  public function modificarcategoria() //Metodo actualizar categoria
  {
    if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Producto(); //Instanciar clase Producto
      $dao = new ProductoDAO(); //Instanciar clase ProductoDAO
      $p->setIdcategoria($_POST['txtIdcategoria']); //Captura Id de la categoria que se esta modificando
      $p->setNombre($_POST['txtNombre']); //Captura nombre de la categoria
      $p->setDescripcion($_POST['txtDescripcion']); //Captura la descripcion de la categoria
      $r = $dao->ActualizarCategoria($p);  //Ejecuta al método encargado de llamar al procedimiento almacenado Actualizarcategoria
      echo json_encode($r); //Imprimir en formato de JSON la respuesta del metodo ActualizarCategoria
    }
  }

  public function eliminar() //Método eliminar producto
  {
    if (empty($_POST['txtIdproducto'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Producto(); //Instanciar clase persona
      $dao = new ProductoDAO(); //Instanciar clase personaDAO
      $p->setIdproducto($_POST['txtIdproducto']); //Captura Id del usuario que sera eliminado
      $r = $dao->EliminarProducto($p); //Ejecuta al método encargado de llamar al procedimiento almacenado Eliminarproducto
      if ($r > 0) { //Si se elimina correctamente el producto
        $path = './assets/img/Productos/' . $_POST['txtCodProducto']; //Capturar directorio del producto que se eliminara
        $this->Eliminardir($path); //Elimina el directorio seleccionado
      }
      echo json_encode($r); //Imprimir en formato de JSON la respuesta del metodo Eliminarproducto
    }
  }

  public function eliminarcategoria() //Método eliminar categoria
  {
    if (empty($_POST['txtIdcategoria'])) { //Si la información enviada por el formulario esta vacía se retornara a la página de acceso
      if (isset($_SESSION['persona'])) {
        $url = explode('/', $_SESSION['url']);
        $this->render($url[4], $url[5]);
      } else { //Si no se encuentra una sesion abierta se retorna al login
        $this->render('Persona', 'index.php');
      }
    } else { //Si el formulario se recibe con todos los datos completos hacer:
      $p = new Producto(); //Instanciar clase producto
      $dao = new ProductoDAO(); //Instanciar clase ProductoDAO
      $p->setIdcategoria($_POST['txtIdcategoria']); //Capturar Id de la categoria que sera eliminada
      $r = $dao->EliminarCategoria($p); //Ejecuta al método encargado de llamar al procedimiento almacenado EliminarCategoria
      echo json_encode($r); //Imprimir en formato de JSON la respuesta del metodo EliminarCategoria
    }
  }
}
