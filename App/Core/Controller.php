<?php
class Controller{ //Clase opciones del controlador
    protected function render($folder,$path,$parameters = [],$layout=''){ //Metodo para redirigir a una vista
        require_once(__DIR__.'/../Views/'.$folder.'/'.$path);    
        //echo "<script>window.location.replace('App/Views/".$folder."/".$path."')</script>";      
    }

    protected function Eliminardir($path){ //Método para eliminar un directorio
        foreach(glob($path."/*") as $elemento){
            unlink($elemento);
        }
        rmdir($path);
    }
}

?>