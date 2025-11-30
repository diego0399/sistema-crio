<?php
require_once 'Almacen.php'; //Importamos la clase Almacen que contiene las propiedades GET y SET
interface InterfazAlmacenDAO{
    public function AgregarAlmacen(Almacen $a);
    public function ActualizarAlmacen(Almacen $a);
    public function EliminarAlmacen(Almacen $a);
    public function ListarBodegas();
    public function ListarProductosBodega(Almacen $a);
}

?>