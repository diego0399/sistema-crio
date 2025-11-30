<?php
    $folderPath = dirname($_SERVER['SCRIPT_NAME']); //Variable que captura el nombre del directorio el proyecto
    $urlPath = $_SERVER['REQUEST_URI']; //Variable que captura el nombre del archivo ejecutandose
    $url = substr($urlPath,strlen($folderPath)); 
    define('URL',$url);

