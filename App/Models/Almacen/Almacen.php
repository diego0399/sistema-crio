<?php
class Almacen{
    private $Idbodega;
    private $Direccion;
    private $Telefono;
    private $Estado;

    /**
     * Get the value of Direccion
     */ 
    public function getDireccion()
    {
        return $this->Direccion;
    }

    /**
     * Set the value of Direccion
     *
     * @return  self
     */ 
    public function setDireccion($Direccion)
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    /**
     * Get the value of Telefono
     */ 
    public function getTelefono()
    {
        return $this->Telefono;
    }

    /**
     * Set the value of Telefono
     *
     * @return  self
     */ 
    public function setTelefono($Telefono)
    {
        $this->Telefono = $Telefono;

        return $this;
    }

    /**
     * Get the value of Estado
     */ 
    public function getEstado()
    {
        return $this->Estado;
    }

    /**
     * Set the value of Estado
     *
     * @return  self
     */ 
    public function setEstado($Estado)
    {
        $this->Estado = $Estado;

        return $this;
    }

    /**
     * Get the value of Idbodega
     */ 
    public function getIdbodega()
    {
        return $this->Idbodega;
    }

    /**
     * Set the value of Idbodega
     *
     * @return  self
     */ 
    public function setIdbodega($Idbodega)
    {
        $this->Idbodega = $Idbodega;

        return $this;
    }
}
?>