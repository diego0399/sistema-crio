<?php
class Persona{
    private $IdUsuario;
    private $Nombre;
    private $Login;
    private $Password;
    private $Email;
    private $Estado;
    private $IdPrivilegio;
    private $Privilegio;
    private $Fecha;
    private $Opcion;
    
    /**
     * Get the value of IdUsuario
     */ 
    public function getIdUsuario()
    {
        return $this->IdUsuario;
    }

    /**
     * Set the value of IdUsuario
     *
     * @return  self
     */ 
    public function setIdUsuario($IdUsuario)
    {
        $this->IdUsuario = $IdUsuario;

        return $this;
    }

    /**
     * Get the value of Nombre
     */ 
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * Set the value of Nombre
     *
     * @return  self
     */ 
    public function setNombre($Nombre)
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    /**
     * Get the value of Login
     */ 
    public function getLogin()
    {
        return $this->Login;
    }

    /**
     * Set the value of Login
     *
     * @return  self
     */ 
    public function setLogin($Login)
    {
        $this->Login = $Login;

        return $this;
    }
    

    /**
     * Get the value of Password
     */ 
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * Set the value of Password
     *
     * @return  self
     */ 
    public function setPassword($Password)
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * Get the value of Email
     */ 
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Set the value of Email
     *
     * @return  self
     */ 
    public function setEmail($Email)
    {
        $this->Email = $Email;

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
     * Get the value of IdPrivilegio
     */ 
    public function getIdPrivilegio()
    {
        return $this->IdPrivilegio;
    }

    /**
     * Set the value of IdPrivilegio
     *
     * @return  self
     */ 
    public function setIdPrivilegio($IdPrivilegio)
    {
        $this->IdPrivilegio = $IdPrivilegio;

        return $this;
    }

    /**
     * Get the value of Privilegio
     */ 
    public function getPrivilegio()
    {
        return $this->Privilegio;
    }

    /**
     * Set the value of Privilegio
     *
     * @return  self
     */ 
    public function setPrivilegio($Privilegio)
    {
        $this->Privilegio = $Privilegio;

        return $this;
    }

    /**
     * Get the value of Fecha
     */ 
    public function getFecha()
    {
        return $this->Fecha;
    }

    /**
     * Set the value of Fecha
     *
     * @return  self
     */ 
    public function setFecha($Fecha)
    {
        $this->Fecha = $Fecha;

        return $this;
    }

    /**
     * Get the value of Opcion
     */ 
    public function getOpcion()
    {
        return $this->Opcion;
    }

    /**
     * Set the value of Opcion
     *
     * @return  self
     */ 
    public function setOpcion($Opcion)
    {
        $this->Opcion = $Opcion;

        return $this;
    }
}
?>