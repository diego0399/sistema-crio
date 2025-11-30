<?php
require_once 'Producto.php'; //Importamos la clase producto que contiene las propiedades GET y SET
interface InterfazProductoDAO{
    public function AgregarProducto(Producto $p);
    public function AgregarCategoria(Producto $p);
    public function ActualizarProducto(Producto $p);
    public function ActualizarCategoria(Producto $p);
    public function EliminarProducto(Producto $p);
    public function EliminarCategoria(Producto $p);
    public function AgregarExistencia(Producto $p);
    public function ActualizarExistencia(Producto $p);
    public function ListarProductos();
    public function ListarBodegas();
    public function ListarCategorias();
    public function ListarExistencias(Producto $p);
    public function ListarProductosCategoria(Producto $p);
}

?>