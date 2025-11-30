<?php
require_once 'Persona.php'; //Importamos la clase persona que contiene las propiedades GET y SET
interface InterfazPersonaDAO
{
    public function ValidarUsuario(Persona $p);
    public function AgregarUsuario(Persona $p);
    public function ActualizarUsuario(Persona $p);
    public function EliminarUsuario(Persona $p);
    public function ListarUsuarios();
    public function ListarPrivilegios();
    public function ListarDatosgenerales(Persona $p);
}
