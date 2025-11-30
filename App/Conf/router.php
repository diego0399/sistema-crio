<?php
class Router //Clase router
{
    private $controller; //Variable controlador
    private $method; //Metodo del controlador


    public function __construct() //Constructor que inicializa el metodo matchRoute y setea la variable session encargada de almacener el directorio del proyecto
    {
        $this->matchRoute();
        $_SESSION['URL_PATH'] = "http://".$_SERVER['HTTP_HOST']."/SistemaCrio";
    }

    public function matchRoute() //Metodo match
    {
        $url = explode('/', URL); //Separa variable url
        //var_dump($url);
        $this->controller = !empty($url[1]) ? $url[1] : 'Persona'; //Primera parte de la variable url contiene el nombre del controlador, si esta vacia por defecto sera controlador persona
        $this->method = !empty($url[2]) ? $url[2] : 'index'; //Segunda parte de la variable url contiene el nombre de la funcion, si esta vacia por defecto sera index

        $this->controller = $this->controller . "Controlador"; //Setear la variable controlador
        require_once(__DIR__ . '/../Controllers/' . $this->controller . '.php'); //Cargar controlador 
    }

    public function run() //Metodo run
    {
        $controller = new $this->controller(); //Instanciar controlador
        $method = $this->method; //Setear la variable method
        $controller->$method(); //llama una funcion del controlador
    }
}
