<?php 
class Producto{
    private $Idproducto;
    private $Nombre;
    private $Precio;
    private $Idcategoria;
    private $Img;
    private $Descripcion;
    private $Existencias;
    private $Idbodega;
    private $Idusuario;

    /**
     * Get the value of Idproducto
     */ 
    public function getIdproducto()
    {
        return $this->Idproducto;
    }

    /**
     * Set the value of Idproducto
     *
     * @return  self
     */ 
    public function setIdproducto($Idproducto)
    {
        $this->Idproducto = $Idproducto;

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
     * Get the value of Precio
     */ 
    public function getPrecio()
    {
        return $this->Precio;
    }

    /**
     * Set the value of Precio
     *
     * @return  self
     */ 
    public function setPrecio($Precio)
    {
        $this->Precio = $Precio;

        return $this;
    }

    /**
     * Get the value of Idcategoria
     */ 
    public function getIdcategoria()
    {
        return $this->Idcategoria;
    }

    /**
     * Set the value of Idcategoria
     *
     * @return  self
     */ 
    public function setIdcategoria($Idcategoria)
    {
        $this->Idcategoria = $Idcategoria;

        return $this;
    }

    /**
     * Get the value of Img
     */ 
    public function getImg()
    {
        return $this->Img;
    }

    /**
     * Set the value of Img
     *
     * @return  self
     */ 
    public function setImg($Img)
    {
        $this->Img = $Img;

        return $this;
    }

    /**
     * Get the value of Existencias
     */ 
    public function getExistencias()
    {
        return $this->Existencias;
    }

    /**
     * Set the value of Existencias
     *
     * @return  self
     */ 
    public function setExistencias($Existencias)
    {
        $this->Existencias = $Existencias;

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

    /**
     * Get the value of Descripcion
     */ 
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * Set the value of Descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    /**
     * Get the value of Idusuario
     */ 
    public function getIdusuario()
    {
        return $this->Idusuario;
    }

    /**
     * Set the value of Idusuario
     *
     * @return  self
     */ 
    public function setIdusuario($Idusuario)
    {
        $this->Idusuario = $Idusuario;

        return $this;
    }
}
?>