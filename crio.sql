-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-11-2022 a las 02:09:42
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crio`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `SP_ActualizarBodega`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ActualizarBodega` (IN `p_Idbodega` INT, IN `p_Direccion` VARCHAR(50), IN `p_Telefono` VARCHAR(10), IN `p_Estado` BIT)  BEGIN
    DECLARE v_respuesta varchar(30); #Variable de salida
    IF EXISTS(SELECT Idbodega FROM bodega WHERE Idbodega=p_Idbodega) THEN #Validar que la bodega exista
		UPDATE bodega SET Direccion=p_Direccion, Telefono=p_Telefono, Estado=p_Estado 
		WHERE Idbodega=p_Idbodega; #Hacer update
		SET v_respuesta=CONCAT((SELECT Nombre FROM bodega WHERE Idbodega=p_Idbodega),'/',p_Idbodega); #Asignar Codigo y Id de la bodega a la variable de salida
    ELSE #Si no existe la bodega
		SET v_respuesta=CONCAT('INVALID/',-1); #Indicar que es invalido y asignar valor -1
    END IF;
	SELECT v_respuesta; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_ActualizarCategoria`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ActualizarCategoria` (IN `p_Idcategoria` INT, IN `p_Nombre` VARCHAR(50), IN `p_Descripcion` TEXT)  BEGIN
    DECLARE v_Respuesta INT; #Declarar variable de salida
    IF EXISTS(SELECT Idcategoria FROM categorias WHERE Nombre=p_Nombre AND Idcategoria <> p_Idcategoria) THEN #Validar que no se ingrese un nombre repetido de una categoria
		SET v_Respuesta=-1; #Asignarle -1 a la variable de salida si el nombre de la categoria ya se encuentra registrado
    ELSE #Si no se encuentra registrado
		UPDATE categorias SET Nombre=p_Nombre,Descripcion=p_Descripcion
        WHERE Idcategoria=p_Idcategoria; #Realizar update
        SET v_Respuesta = p_Idcategoria; #Asignar Id de la categoria que se modificando a la variable de salida
    END IF;
    SELECT v_Respuesta as Idcategoria; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_ActualizarExistencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ActualizarExistencia` (IN `p_Existencias` FLOAT, IN `p_Idbodega` INT, IN `p_Idproducto` INT)  BEGIN
	DECLARE v_Codigo varchar(10); #Declara variable de salida
    UPDATE bodegainventario SET Existencias=p_Existencias
    WHERE Idbodega=p_Idbodega AND Idproducto=p_Idproducto; #Actualizar existencias
    SET v_Codigo=(SELECT Cod_producto FROM productos WHERE Idproducto=p_Idproducto); #Asignar Codigo del producto que se le modifico las existencias
    SELECT v_Codigo; #Retornar codigo
END$$

DROP PROCEDURE IF EXISTS `SP_ActualizarProducto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ActualizarProducto` (IN `p_Idproducto` INT, IN `p_Nombre` VARCHAR(50), IN `p_Precio` DECIMAL(10,3), IN `p_Idcategoria` INT, IN `p_Img` VARCHAR(150), IN `p_Descripcion` TEXT)  BEGIN
DECLARE v_Idproducto INT; #Declarar variable Id del producto
DECLARE v_Cod varchar(30); #Declarar variable codigo del producto
DECLARE v_Categoria varchar(50); #Declarar categoria del Producto
	IF EXISTS(SELECT * FROM productos WHERE lower(Nombre)=lower(p_Nombre) AND Idproducto <> p_Idproducto) THEN #Valida que no se ingrese un nombre que ya este en la BD
		SET v_Idproducto=-1; #Asignar -1 si el nombre ya se encuentra en uso
	ELSE #Si el nombre no esta en uso
        SET v_Cod=(SELECT Cod_Producto FROM productos WHERE Idproducto=p_Idproducto); #Seleccionar el codigo del Producto
        SET v_Cod=substring(v_Cod,5,8); #Extraer solo datos numericos del codigo
        SET v_Categoria=(SELECT UPPER(Nombre) FROM categorias WHERE Idcategoria=p_Idcategoria); #Seleccionar el nombre de la categoria del producto
        SET v_Categoria=concat('P',substring(v_Categoria,1,3)); #Extraer las primeras 3 letras de la categoria

	    IF p_Img='default' THEN #Si no se ingresa una nueva imagen
			UPDATE productos SET Cod_producto=concat(v_Categoria,v_Cod),Nombre=p_Nombre,Precio=p_Precio,Idcategoria=p_Idcategoria,Descripcion=p_Descripcion,Fecha_ultimamodificacion=CURRENT_TIMESTAMP
			WHERE Idproducto = p_Idproducto; #Realizar update
            SET v_Idproducto=p_Idproducto; #Asignar Id del producto afectado
        ELSE #Si se ingresa una nueva imagen
			UPDATE productos SET Cod_producto=concat(v_Categoria,v_Cod),Nombre=p_Nombre,Precio=p_Precio,Idcategoria=p_Idcategoria,Img=p_Img,Descripcion=p_Descripcion,Fecha_ultimamodificacion=CURRENT_TIMESTAMP
			WHERE Idproducto = p_Idproducto; #Realizar update
			SET v_Idproducto=p_Idproducto; #Asignar Id del producto afectado
		END IF;
	END IF;
SELECT v_Idproducto AS Idproducto; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_ActualizarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ActualizarUsuario` (IN `p_Idusuario` INT, IN `p_Opcion` INT, IN `p_Nombre` VARCHAR(50), IN `p_Login` VARCHAR(25), IN `p_Password` VARCHAR(50), IN `p_Patron` VARCHAR(25), IN `p_Email` VARCHAR(100), IN `p_Estado` BIT, IN `p_Idprivilegio` INT)  BEGIN

IF p_Opcion <=> 1 THEN #Opcion 1 sirve para modificar todos los datos de un registro de la tabla usuario
   IF EXISTS (SELECT Idusuario FROM usuarios WHERE lower(Login)=lower(p_Login) AND Idusuario<>p_Idusuario) THEN /*Valida que el login recibido no se encuentre registrado por los demás usuarios*/
		SELECT -1 AS Idusuario;
   ELSEIF EXISTS (SELECT Idusuario FROM usuarios WHERE lower(Email)=lower(p_Email) AND Idusuario<>p_Idusuario) THEN /*Valida que el correo recibido no se encuentre registrado por los demás usuarios*/
		SELECT -2 AS Idusuario;
	ELSE /*Si el login y el correo recibido no se encuentra utilizado por los demás usuarios se hará el update*/
		UPDATE usuarios SET Nombre=p_Nombre,Login=p_Login,Password=aes_encrypt(p_Password,p_Patron),Email=p_Email,Estado=p_Estado,Idprivilegio=p_Idprivilegio
		WHERE Idusuario=p_Idusuario;
		SELECT p_Idusuario as Idusuario;
	END IF;
ELSEIF p_Opcion <=> 2 THEN #Opcion 2 sirve para actualizar solo algunos datos de la tabla usuario
    IF EXISTS (SELECT Idusuario FROM usuarios WHERE lower(Login)=lower(p_Login) AND Idusuario<>p_Idusuario) THEN /*Valida que el login recibido no se encuentre registrado por los demás usuarios*/
		SELECT -1 as Idusuario;
	ELSEIF EXISTS (SELECT Idusuario FROM usuarios WHERE lower(Email)=lower(p_Email) AND Idusuario<>p_Idusuario) THEN /*Valida que el correo recibido no se encuentre registrado por los demás usuarios*/
		SELECT -2 AS Idusuario;
	ELSE /*Si el login y el correo recibido no se encuentra utilizado por los demás usuarios se hará el update*/
		UPDATE usuarios SET Nombre=p_Nombre,Login=p_Login,Password=aes_encrypt(p_Password,p_Patron),Email=p_Email
		WHERE Idusuario=p_Idusuario;
		SELECT p_Idusuario as Idusuario;
	END IF; 
END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_AgregarBodega`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_AgregarBodega` (IN `p_Direccion` VARCHAR(50), IN `p_Telefono` VARCHAR(10), IN `p_Estado` BIT)  BEGIN
    DECLARE v_Idbodega INT; #Declarar variable Id de bodega
    DECLARE v_Idproducto INT; #Declarar variable Id del producto
    DECLARE v_respuesta VARCHAR(25); #Declarar variable de salida
    DECLARE n INT; #Declarar variable cantidad
    DECLARE i INT; #Declarar variable iteradora
	INSERT INTO bodega(Direccion,Telefono,Estado)
    VALUES(p_Direccion,p_Telefono,p_Estado); #Insertar bodega
    IF(SELECT last_insert_id()>0) THEN #Si se inserto correctamente el registro 
		SET v_Idbodega=last_insert_id(); #Asignar a la variable el ultimo id insertado
		SET n=(SELECT max(Idproducto) FROM productos); #Seleccionar la cantidad registros de la tabla producto
		SET i=1; #Comenzar en uno la variable iteradora
		WHILE i<=n DO #Bucle que recorrera desde la primera fila hasta la ultima fila
		  SET v_Idproducto=(SELECT Idproducto FROM productos WHERE Idproducto=i); #Seleccionar Id de caada producto
		  IF v_Idproducto is not null THEN #Si el Id del producto existe
			INSERT INTO bodegainventario(Existencias,Idbodega,Idproducto)
			VALUES (0,v_Idbodega,v_Idproducto); #Realizar insert del contenido de la bodega
		  END IF;
		  SET i = i + 1; #Incrementar la variable iteradora en uno
	END WHILE; #Fin del bucle
    SET v_respuesta=CONCAT((SELECT Nombre FROM bodega WHERE Idbodega=v_Idbodega),'/',v_Idbodega); #Asignar Nombre de la bodega y Id a la variable de salida
    ELSE #Si ocurre un error en insertar la bodega
        SET v_respuesta=CONCAT('INVALID/',-1); #Asignar invalid y -1
    END IF;
    SELECT v_respuesta; #Retornar respuesta
END$$

DROP PROCEDURE IF EXISTS `SP_AgregarCategoria`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_AgregarCategoria` (IN `p_Nombre` VARCHAR(50), IN `p_Descripcion` TEXT)  BEGIN
    DECLARE v_Respuesta INT; #Declarar variable de salida
    IF EXISTS(SELECT Idcategoria FROM categorias WHERE Nombre=p_Nombre) THEN #Valida que el nombre ingresado no este en la BD
		SET v_Respuesta=-1; #Asignar -1 si el nombre ya esta siendo usado
    ELSE #Si no esta el nombre ocupado
		INSERT INTO categorias (Nombre,Descripcion)
        VALUES (p_Nombre,p_Descripcion); #Realizar insert
        SET v_Respuesta = last_insert_id(); #Asignar a la variable de salida el ultimo id insertado
    END IF;
    SELECT v_Respuesta as Idcategoria; #Retornar respuesta
END$$

DROP PROCEDURE IF EXISTS `SP_AgregarExistencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_AgregarExistencia` (IN `p_Existencias` FLOAT, IN `p_Idbodega` INT, IN `p_Idproducto` INT)  BEGIN
	DECLARE v_Codigo varchar(10); #Declarar variable Codigo
    INSERT INTO bodegainventario(Existencias,Idbodega,Idproducto)
    VALUES(p_Existencias,p_Idbodega,p_Idproducto); #Insertar stock de una bodega
    
    IF (SELECT last_insert_id()>0)THEN #Si se inserto correctamente el stock
       SET v_Codigo=(SELECT Cod_producto FROM productos WHERE Idproducto=p_Idproducto); #Seleccionar codigo del producto afectado
    ELSE #Si hubo problema en insertar el stock de una bodega
       SET v_Codigo=-1; #Asignar -1
    END IF;
    SELECT v_Codigo; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_AgregarProducto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_AgregarProducto` (IN `p_Nombre` VARCHAR(50), IN `p_Precio` DECIMAL(10,3), IN `p_Idcategoria` INT, IN `p_Img` VARCHAR(150), IN `p_Descripcion` TEXT, IN `p_Idusuario` INT)  BEGIN
DECLARE v_Idproducto INT; #Declarar variable de salida
	IF EXISTS(SELECT * FROM productos WHERE lower(Nombre)=lower(p_Nombre)) THEN #Validar que no se ingresen nombres ya registrados en la BD
		SET v_Idproducto=-1; #Asignar -1 si el nombre ya esta en uso
	ELSE #Si el nombre no esta en la BD
        INSERT INTO productos (Nombre,Precio,Idcategoria,Img,Descripcion,Idusuario)
        VALUES (p_Nombre,p_Precio,p_Idcategoria,p_Img,p_Descripcion,p_Idusuario); #Realizar insert
        SET v_Idproducto=(SELECT last_insert_id()); #Asignar ultimo id insertado
	END IF;
SELECT v_Idproducto AS Idproducto; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_AgregarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_AgregarUsuario` (IN `p_Nombre` VARCHAR(50), IN `p_Login` VARCHAR(30), IN `p_Password` VARCHAR(50), IN `p_Email` VARCHAR(100), IN `p_Estado` BIT, IN `p_Idprivilegio` INT, IN `p_Patron` VARCHAR(25))  BEGIN
DECLARE v_Idusuario INT; #Declarar variable de salida
IF EXISTS(SELECT Idusuario FROM usuarios WHERE lower(Login)=lower(p_Login)) THEN /*DEVUELVE -1 SI EL LOGIN INGRESADO YA SE ENCUENTRA EN LA DB*/
   SET v_Idusuario=-1;
ELSEIF EXISTS (SELECT Idusuario FROM usuarios WHERE lower(Email)=lower(p_Email)) THEN /*DEVUELVE -2 SI EL CORREO INGRESADO YA SE ENCUENTRA EN LA DB*/
   SET v_Idusuario=-2;
ELSE /*EN CASO CONTRARIO DE QUE NO SE ENCUENTREN EL LOGIN Y EL EMAIL INGRESADO EN LA DB SE PROCESEDE A HACER LA INSERCCION*/
INSERT INTO usuarios 
	(Nombre,Login,Password,Email,Estado,Idprivilegio)
	VALUES
	(p_Nombre,p_Login,aes_encrypt(p_Password,p_Patron),p_Email,p_Estado,p_IdPrivilegio);
	SET v_Idusuario=(SELECT last_insert_id()); #Asignar ultimo id insertado
END IF;
SELECT v_Idusuario AS Idusuario; #Retornar variable de salida
END$$

DROP PROCEDURE IF EXISTS `SP_Datosgenerales`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Datosgenerales` (IN `p_Opcion` INT)  BEGIN
    DECLARE v_Productos INT; #Declarar variable productos
    DECLARE v_Usuarios INT; #Declarar variable usuarios
    DECLARE v_Ultimosusuarios INT; #Declarar variable ultimos usuarios insertado
    DECLARE v_Bodegas INT; #Declarar variable bodegas
    DECLARE v_Bodegasporcentaje DECIMAL (10,2); #Declarar variable porcentaje de bodegas
    DECLARE v_Usuariosporcentaje DECIMAL (10,2); #Declarar variable porcentaje de usuarios
    DECLARE v_Ultimosusuariosporcentaje DECIMAL(10,2); #Declarar variable porcentaje de ultimos usuarios registraos
	/*Cantidad de productos*/
	SET v_Productos=(SELECT SUM(fn_stock(a.Idproducto)) as Productos FROM productos as a);
	/*Cantidad de usuarios activos*/
    SET v_Usuarios=(SELECT COUNT(*) FROM usuarios WHERE Estado=TRUE);
    /*Cantidad ultimos usuarios registrados en los ultimos 7 dias*/
    SET v_Ultimosusuarios=(SELECT COUNT(*) FROM usuarios WHERE Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW());
    /*Bodegas activas*/
	SET v_Bodegas=(SELECT COUNT(*) as Bodegas FROM bodega WHERE Estado=TRUE);
	/*Porcentaje de bodegas activas Formula (CANTIDAD / TOTAL) x 100 */
	SET v_Bodegasporcentaje=(SELECT (SELECT COUNT(*) as Bodegas FROM bodega WHERE Estado=TRUE)/(SELECT COUNT(*) as Bodegas FROM bodega)*100);
    /*Porcentaje de usuarios activos (CANTIDAD / TOTAL) x 100 */
    SET v_Usuariosporcentaje=(SELECT(SELECT COUNT(*) FROM usuarios WHERE Estado=TRUE)/(SELECT COUNT(*) FROM usuarios)*100);
    /*Porcentaje de ultimos usuarios registrados en los 7 dias (CANTIDAD / TOTAL) x 100 */
    SET v_Ultimosusuariosporcentaje=(SELECT ((SELECT COUNT(*) FROM usuarios WHERE Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()) / (SELECT COUNT(*) FROM usuarios))*100);
    CASE p_Opcion #Tipo de consulta
		WHEN 1 THEN  #Consulta productos por categoria
			SELECT * FROM vw_productoscategorias; /*Productos por categoria*/
		WHEN 2 THEN #Consulta datos generales del sistema
			SELECT v_Bodegas as Bodegas,CONCAT(v_Bodegasporcentaje,'%') as Porcentajebodegas,v_Usuarios as Usuarios,CONCAT(v_Usuariosporcentaje,'%') as Porcentajeusuarios,v_Ultimosusuarios as Ultimosusuarios,CONCAT(v_Ultimosusuariosporcentaje,'%')as Ultimosusuariosporcentaje;
		ELSE 
			BEGIN END; 
    END CASE;
END$$

DROP PROCEDURE IF EXISTS `SP_EliminarBodega`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_EliminarBodega` (IN `p_Idbodega` INT)  BEGIN
    IF EXISTS (SELECT Idbodega FROM bodega WHERE Idbodega=p_Idbodega)THEN #Validar que exista el registro
		DELETE FROM bodega WHERE Idbodega=p_Idbodega; #Realizar delete del registro
		SELECT p_Idbodega AS Idbodega; #Retornar el Id que ha sido eliminado
    ELSE #Sino existe el Id recibido
		Select -1 as Idbodega; #Retornar -1
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_EliminarCategoria`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_EliminarCategoria` (IN `p_Idcategoria` INT)  BEGIN
    IF EXISTS (SELECT Idcategoria FROM categorias WHERE Idcategoria=p_Idcategoria)THEN #Validar que exista el registro
		DELETE FROM categorias WHERE Idcategoria=p_Idcategoria; #Realizar delete del registro
		SELECT p_Idcategoria AS Idcategoria; #Retornar el Id que ha sido eliminado
    ELSE #Sino existe el Id recibido
		Select -1 as Idcategoria; #Retornar -1
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_EliminarProducto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_EliminarProducto` (IN `p_Idproducto` INT)  BEGIN
    IF EXISTS (SELECT Idproducto From productos Where Idproducto=p_Idproducto)THEN #Validar que exista el registro
		DELETE FROM productos WHERE Idproducto=p_Idproducto; #Realizar delete del registro
		SELECT p_Idproducto AS Idproducto; #Retornar el Id que ha sido eliminado
    ELSE #Sino existe el Id recibido
		Select -1 as Idproducto; #Retornar -1
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_EliminarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_EliminarUsuario` (IN `p_Idusuario` INT)  BEGIN
    IF EXISTS (SELECT Idusuario From usuarios Where Idusuario=p_IDUsuario)THEN #Validar que exista el registro
		DELETE FROM usuarios WHERE Idusuario=p_IDUsuario; #Realizar delete del registro
		SELECT p_Idusuario AS Idusuario; #Retornar el Id que ha sido eliminado
    ELSE #Sino existe el Id recibido
		Select -1 as Idusuario; #Retornar -1
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_ListarBodegas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarBodegas` ()  BEGIN
	SELECT Idbodega, Nombre, Direccion, Telefono, fn_stockbodega(Idbodega) as Productos, fn_Estado(Estado)as Estado FROM bodega; #Seleccionar bodegas y existencias
END$$

DROP PROCEDURE IF EXISTS `SP_ListarCategorias`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarCategorias` ()  BEGIN
	SELECT Idcategoria,Nombre,Descripcion,fn_productoscategoria(Idcategoria) as Productos FROM  categorias; #Seleccionar categorias y cantidad de productos
END$$

DROP PROCEDURE IF EXISTS `SP_ListarExistencias`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarExistencias` (IN `p_Idproducto` INT)  BEGIN
	SELECT a.Idbodega,a.Nombre,b.Existencias, fn_Estado(a.Estado) as Estado FROM  bodega as a
	INNER JOIN bodegainventario as b
	ON a.Idbodega=b.Idbodega
	INNER JOIN productos as c
	ON b.Idproducto=c.Idproducto
	Where c.Idproducto=p_Idproducto; #Seleccionar existencias de un producto
END$$

DROP PROCEDURE IF EXISTS `SP_ListarPrivilegios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarPrivilegios` ()  BEGIN
	SELECT * FROM  privilegios; #Seleccionar privilegios
END$$

DROP PROCEDURE IF EXISTS `SP_ListarProductos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarProductos` ()  BEGIN
	SELECT a.Idproducto,a.Cod_producto,a.Nombre,FORMAT(a.Precio,2) as Precio,b.Idcategoria,b.Nombre as Categoria,fn_stock(a.Idproducto) AS Existencias,a.Img,a.Descripcion,c.Login as Usuario,fn_Fecha(a.Fecha_ingreso) AS Fecha_ingreso,fn_Fecha(a.Fecha_ultimamodificacion) AS Fecha_actualizacion FROM productos as a
    INNER JOIN categorias AS b
    ON a.Idcategoria=b.Idcategoria
    INNER JOIN usuarios as c
    ON a.Idusuario=c.Idusuario; #Seleccionar productos y sus datos
END$$

DROP PROCEDURE IF EXISTS `SP_ListarProductosBodega`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarProductosBodega` (IN `p_Idbodega` INT)  BEGIN
    IF NOT EXISTS(SELECT a.Idbodega FROM bodega as a INNER JOIN bodegainventario as b ON a.Idbodega=b.Idbodega WHERE a.Idbodega=p_Idbodega AND b.Existencias>0) THEN #Si no hay productos en una bodega
		SELECT -1 as Idbodega;
    ELSE
		SELECT a.Idproducto,a.Cod_producto as Codigo,a.Nombre,a.Img as Imagen,c.Nombre as Bodega,b.Existencias,d.Login,fn_formatofecha_mmyy(a.Fecha_ingreso) as Fechaingreso,fn_formatofecha_mmyy(a.Fecha_ultimamodificacion) as Fechaultimamodificacion  FROM productos as a
		INNER JOIN bodegainventario as b
		ON a.Idproducto=b.Idproducto
		INNER JOIN bodega as c
		ON b.Idbodega=c.Idbodega
		INNER JOIN usuarios as d
		ON a.Idusuario=d.Idusuario
		WHERE c.Idbodega=p_Idbodega AND b.Existencias>0
		ORDER BY a.Idproducto; #Seleccionar productos de una bodega
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_ListarProductosCategoria`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarProductosCategoria` (IN `p_Idcategoria` INT)  BEGIN
    IF NOT EXISTS(SELECT a.Idproducto FROM productos as a INNER JOIN categorias as b ON a.Idcategoria=b.Idcategoria WHERE a.Idcategoria=p_Idcategoria AND fn_stock(a.Idproducto)>0) THEN #Sino hay productos en una categoria
		SELECT -1 as Idcategoria;
    ELSE
		SELECT b.Idproducto,b.Cod_producto as Codigo,b.Nombre,b.Img as Imagen,fn_stock(b.Idproducto) as Existencias,c.Login,fn_formatofecha_mmyy(b.Fecha_ingreso) as Fechaingreso,fn_formatofecha_mmyy(b.Fecha_ultimamodificacion) as Fechaultimamodificacion FROM categorias as a
		INNER JOIN productos as b
		ON a.Idcategoria=b.Idcategoria
		INNER JOIN usuarios as c
		ON b.Idusuario=c.Idusuario
		WHERE a.Idcategoria=p_Idcategoria AND fn_stock(b.Idproducto) > 0
		ORDER BY b.Idproducto; #Seleccionar productos de una categoria
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_ListarUsuarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ListarUsuarios` (IN `p_Patron` VARCHAR(25))  BEGIN
	SELECT Idusuario,Nombre,Login,(aes_decrypt(Password,p_Patron)) as contra,Email,fn_Estado(Estado) as Estado,fn_PrivilegioUsuario(Idprivilegio) as Privilegio, fn_Fecha(Fecha) as Fecha FROM Usuarios; #Seleccionar los usuarios
END$$

DROP PROCEDURE IF EXISTS `SP_ValidarUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ValidarUsuario` (IN `p_Login` VARCHAR(30), IN `p_Password` VARCHAR(100), IN `p_Patron` VARCHAR(25))  BEGIN
	IF NOT EXISTS (SELECT Idusuario FROM usuarios WHERE Login=p_Login) THEN /*Valida que exista el login ingresado si no se encuentra el login ingresado se retornara -1*/
		SELECT -1 as Idusuario;
	ELSEIF NOT EXISTS(SELECT Idusuario FROM usuarios WHERE (aes_decrypt(Password,p_Patron)) = p_Password) THEN /*Valida que exista la contraseña ingresada si no se encuentra en la db se retornara -2*/
		SELECT -2 as Idusuario;
	ELSE
        IF (SELECT Idusuario FROM usuarios WHERE Login=p_Login AND Estado=1) THEN /*Valida que el usuario se encuentre activo si el usuario esta inactivo se retornara -3*/
		   SELECT Idusuario,Nombre,Login,(aes_decrypt(Password,p_Patron)) as Contra,Email,Estado,Idprivilegio,Fecha  From usuarios WHERE Login=p_Login AND (aes_decrypt(Password,p_Patron)) = p_Password;
		ELSE
		   SELECT -3 as Idusuario;
        END IF;
	END IF;
END$$

--
-- Funciones
--
DROP FUNCTION IF EXISTS `fn_Estado`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_Estado` (`p_Estado` BIT) RETURNS VARCHAR(50) CHARSET latin1 BEGIN
DECLARE v_estado varchar (50); #Declara variable de salida
	IF p_Estado=1 THEN #Si el parametro recibido es TRUE
	    SET v_estado='Activo';
	ELSE #Si es false
	    SET v_estado='Inactivo';
	END IF;
RETURN v_estado; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_EstadoUsuario`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_EstadoUsuario` (`p_Estado` BIT) RETURNS VARCHAR(50) CHARSET latin1 BEGIN
DECLARE v_estado varchar (50); #Declarar variable de salida
	IF p_Estado=1 THEN #Si el parametro recibido es TRUE
	    SET v_estado='Activo';
	ELSE #Si es false
	    SET v_estado='Inactivo';
	END IF;
RETURN v_estado; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_Fecha`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_Fecha` (`p_Fecha` DATETIME) RETURNS VARCHAR(30) CHARSET latin1 BEGIN
	DECLARE v_fecha varchar (30); #Declarar variable de salida
    SET v_fecha=DATE_FORMAT(p_Fecha,'%d-%m-%Y %H:%I:%S'); #Cambiar formato de fecha a d-m-y
RETURN v_fecha; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_formatofecha_mmyy`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_formatofecha_mmyy` (`p_Fecha` DATETIME) RETURNS VARCHAR(20) CHARSET latin1 BEGIN
	DECLARE v_Fecha VARCHAR(20); #Declarar variable de salida
    SET v_Fecha= CONCAT(MONTH(p_Fecha),'-',substring(YEAR(p_Fecha),3,4)); #Cambiar formato de fecha a mm-yyyy
RETURN v_Fecha; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_PrivilegioUsuario`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_PrivilegioUsuario` (`p_Privilegio` INT) RETURNS VARCHAR(50) CHARSET latin1 BEGIN
DECLARE v_Privilegio VARCHAR(50); #Declarar variable de salida
	IF p_Privilegio=1 THEN #Si el Id del privilegio es 1
		SET v_Privilegio='Administrador'; 
	ELSE #Si el Id del privilegio es 2
	    SET v_Privilegio='Usuario';
	END IF;
RETURN v_Privilegio; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_productoscategoria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_productoscategoria` (`p_Idcategoria` INT) RETURNS FLOAT BEGIN
	DECLARE v_productos INT; #Declarar variable de salida
    SET v_productos = (SELECT COUNT(p_Idcategoria) from categorias as b INNER JOIN productos as a ON a.Idcategoria=b.Idcategoria WHERE a.Idcategoria=p_Idcategoria); #Devuelve cantidad de productos en una categoria
RETURN v_productos; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_stock`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_stock` (`p_Idproducto` INT) RETURNS FLOAT BEGIN
	DECLARE v_stock float; #Declarar variable de salida
    SET v_stock = (SELECT SUM(Existencias) FROM bodegainventario where Idproducto=p_Idproducto); #Devuelve existencias de un producto
RETURN v_stock; #Retornar variable de salida
END$$

DROP FUNCTION IF EXISTS `fn_stockbodega`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_stockbodega` (`p_Idbodega` INT) RETURNS FLOAT BEGIN
	DECLARE v_stock float; #Declarar variable de salida
    SET v_stock = (SELECT SUM(Existencias) FROM bodegainventario where Idbodega=p_Idbodega); #Devuelve la cantidad de productos en una bodega
RETURN v_stock; #Retornar variable de salida
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega`
--

DROP TABLE IF EXISTS `bodega`;
CREATE TABLE IF NOT EXISTS `bodega` (
  `Idbodega` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) DEFAULT NULL,
  `Direccion` varchar(50) DEFAULT NULL,
  `Telefono` varchar(10) DEFAULT NULL,
  `Estado` bit(1) DEFAULT NULL,
  PRIMARY KEY (`Idbodega`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `bodega`
--

INSERT INTO `bodega` (`Idbodega`, `Nombre`, `Direccion`, `Telefono`, `Estado`) VALUES
(1, 'B-CRIO-001', 'San Salvador', '22649878', b'1'),
(2, 'B-CRIO-002', 'Chalatenango', '22749878', b'0'),
(3, 'B-CRIO-003', 'La Libertad', '22745845', b'1'),
(4, 'B-CRIO-004', 'Morazan', '22594557', b'1'),
(12, 'B-CRIO-005', 'La Libertad', '22987458', b'1');

--
-- Disparadores `bodega`
--
DROP TRIGGER IF EXISTS `trg_Generacodigobodega_Agregar`;
DELIMITER $$
CREATE TRIGGER `trg_Generacodigobodega_Agregar` BEFORE INSERT ON `bodega` FOR EACH ROW BEGIN
	DECLARE siguiente_codigo INT; /*Declarar variable que contendra el ultimo id ingresado y se*/
    DECLARE producto varchar(15);
    SET siguiente_codigo = (SELECT ifnull(max(convert(substring(Nombre,8),signed integer)),0) FROM bodega)+1;
    SET new.Nombre = concat('B-CRIO-',LPAD(siguiente_codigo,3,'0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodegainventario`
--

DROP TABLE IF EXISTS `bodegainventario`;
CREATE TABLE IF NOT EXISTS `bodegainventario` (
  `idbodegainventario` int(11) NOT NULL AUTO_INCREMENT,
  `Existencias` float DEFAULT NULL,
  `Idbodega` int(11) DEFAULT NULL,
  `Idproducto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idbodegainventario`),
  KEY `FK_Bodegainve_Productos_idx` (`Idproducto`),
  KEY `FK_Bodegainve_Bodega_idx` (`Idbodega`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `bodegainventario`
--

INSERT INTO `bodegainventario` (`idbodegainventario`, `Existencias`, `Idbodega`, `Idproducto`) VALUES
(1, 3, 1, 2),
(2, 3, 2, 2),
(3, 3, 3, 2),
(4, 2, 1, 3),
(5, 4, 2, 3),
(6, 2, 3, 3),
(7, 5, 1, 4),
(8, 5, 2, 4),
(9, 5, 3, 4),
(10, 0, 4, 2),
(11, 0, 4, 3),
(12, 0, 4, 4),
(34, 0, 12, 2),
(35, 0, 12, 3),
(36, 0, 12, 4),
(43, 1, 1, 5),
(44, 0, 3, 5),
(45, 0, 4, 5),
(46, 4, 12, 5),
(51, 3, 1, 6),
(52, 0, 4, 6),
(53, 0, 12, 6),
(54, 2, 1, 7),
(55, 0, 4, 7),
(56, 0, 12, 7),
(57, 0, 1, 8),
(58, 0, 3, 8),
(59, 0, 4, 8),
(60, 2, 12, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `Idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) DEFAULT NULL,
  `Descripcion` text,
  PRIMARY KEY (`Idcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`Idcategoria`, `Nombre`, `Descripcion`) VALUES
(1, 'Bebidas', 'Categoria de bebidas.'),
(2, 'Enlatados', 'Categoria de enlatados.'),
(3, 'Lacteos', 'Categoria de lacteos'),
(6, 'Granos', 'Categoria de granos.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios`
--

DROP TABLE IF EXISTS `privilegios`;
CREATE TABLE IF NOT EXISTS `privilegios` (
  `Idprivilegio` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE latin1_danish_ci DEFAULT NULL,
  PRIMARY KEY (`Idprivilegio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

--
-- Volcado de datos para la tabla `privilegios`
--

INSERT INTO `privilegios` (`Idprivilegio`, `Nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `Idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `Cod_producto` varchar(30) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Precio` decimal(10,3) DEFAULT NULL,
  `Idcategoria` int(11) NOT NULL,
  `Img` varchar(150) DEFAULT NULL,
  `Descripcion` text,
  `Idusuario` int(11) DEFAULT NULL,
  `Fecha_ingreso` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Fecha_ultimamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Idproducto`),
  KEY `FK_Productos_Categorias_idx` (`Idcategoria`),
  KEY `FK_Productos_Usuarios_idx` (`Idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`Idproducto`, `Cod_producto`, `Nombre`, `Precio`, `Idcategoria`, `Img`, `Descripcion`, `Idusuario`, `Fecha_ingreso`, `Fecha_ultimamodificacion`) VALUES
(2, 'PBEB0001', 'ROCAMADRE Pet Nat', '150.000', 1, 'rocamadre-pet-nat-520x520.jpg', 'DESCRIPCIÃ“N TÃ‰CNICA\r\n\r\nParaje Altamira\r\n\r\nPinot Noir\r\n\r\nSuelo de origen aluvial\r\n\r\nFermentaciÃ³n natural\r\n\r\nPrensa de racimo entero\r\n\r\nToma de espuma con mÃ©todo ancestral 1100 m.s.n.m\r\n\r\n12Âº/o alcohol\r\n\r\n3,4 ph, 6 acidez total\r\n\r\nProducciÃ³n total 1200 botellas', 1, '2022-11-10 14:05:53', '2022-11-11 18:07:48'),
(3, 'PBEB0002', 'Galileo White Blend', '55.000', 1, 'galileo-white-blend-520x520.jpg', 'Galileo White Blend', 1, '2022-11-10 14:06:27', '2022-11-14 15:10:11'),
(4, 'PBEB0003', 'Familia Deicas Extreme Vineyard Subsuelo Tannat', '200.000', 1, 'deicas-subsuelo-520x520.jpg', 'HISTORIA\r\nLa regiÃ³n de GarzÃ³n es caracterizada por su subsuelo de granito degradado, por eso, fue una sorpresa total para la familia Deicas descubrir un lugar con un subsuelo rico en arcilla y calcÃ¡reo, durante un recorrido buscando proyectos especiales. AdemÃ¡s de ser muy distinto del resto de la regiÃ³n por su composiciÃ³n de subsuelo, lo que es Ãºnico en este terreno es la meteorizaciÃ³n muy fina de la arcilla y del calcÃ¡reo. O sea que son los mismos materiales que en la regiÃ³n de Canelones, con una forma distinta, parecida a la regiÃ³n de BorgoÃ±a. Muy interesada para ver cÃ³mo serÃ­a un vino de este terroir, la familia Deicas tomÃ³ la decisiÃ³n de plantar un viÃ±edo ahÃ­, en 2007, en el Pueblo GarzÃ³n, junto a la familia Varela. Y para acercarse de las condiciones de la BorgoÃ±a, se sacÃ³ la capa fÃ©rtil superior para tener el calcÃ¡reo a la superficie. AsÃ­ que este viÃ±edo es plantado en el subsuelo directamente. El vino de la cosecha 2020 es el primero a ser lanzado con esta etiqueta.\r\nCOMPOSICIÃ“N\r\n100 % Chardonnay\r\nELABORACIÃ“N Y CRIANZA\r\nFermentaciÃ³n en barricas de roble francÃ©s de primer y segundo uso, levaduras nativas. Algunas barricas hacen fermentaciÃ³n malolÃ¡ctica. Crianza 12 meses en barrica.\r\nNOTAS DE CATA\r\nColor verde claro. Aroma intenso a flores blancas, hierbas aromÃ¡ticas y tiza mojada. En boca es cremoso, con una acidez jugosa y final bien largo. Es elegante y con volumen.', 1, '2022-11-10 14:07:55', '2022-11-16 12:26:07'),
(5, 'PENL0004', 'Papa', '2.500', 2, 'Ã±Ã±Ã±Ã±Ã±Ã±!@-.png', 'Papa', 1, '2022-11-12 16:09:31', '2022-11-16 17:43:19'),
(6, 'PBEB0005', 'BacÃ¡n Rosso Malbec', '49.500', 1, 'bacan-malbec-520x520.jpg', 'DESCRIPCIÃ“N TÃ‰CNICA\r\nVariedad Malbec 100% UbicaciÃ³n viÃ±edos Agrelo â€“ LujÃ¡n de Cuyo (Mza â€“ Argentina) AÃ±ada 2010 Cosecha Manual en cajas de 20 kg AÃ±o de plantaciÃ³n 2000 Sistema de conducciÃ³n Doble cordÃ³n pitoneado Densidad de plantaciÃ³n 4500 plantas/ha ComposiciÃ³n del suelo Arcillo limoso con canto rodado Altitud 1050 msnm ProducciÃ³n por planta 1,5 Kg VinificaciÃ³n MaceraciÃ³n de 18 dÃ­as en tanque de acero inoxidable, terminada la maceraciÃ³n, 20% ha sido enviado a barrica de segundo y tercer uso donde ha hecho malolÃ¡ctica y descansado por 12 meses mientras el restante 80% se ha criado en tanque. Temperatura de FermentaciÃ³n Entre 22 y 28 Â°C Alcohol 14,50 % vol. EnÃ³logo: Giuseppe Franceschini.', 1, '2022-11-15 12:14:42', '2022-11-17 12:01:15'),
(7, 'PENL0006', 'Lentejas', '1.500', 2, 'Lentejas_425-800x800-1.png', 'Lentejas', 1, '2022-11-15 12:20:08', '2022-11-17 12:01:01'),
(8, 'PLAC0007', 'Leche Dos Pinos Pinito Uht Entera - 1000Ml', '2.500', 3, '226748-800-600.jpg', 'Leche Dos Pinos Pinito Uht Entera - 1000Ml', 1, '2022-11-17 12:04:33', '2022-11-20 15:58:22');

--
-- Disparadores `productos`
--
DROP TRIGGER IF EXISTS `trg_GeneraCodigo_Agregar`;
DELIMITER $$
CREATE TRIGGER `trg_GeneraCodigo_Agregar` BEFORE INSERT ON `productos` FOR EACH ROW BEGIN
	DECLARE siguiente_codigo INT; /*Declarar variable que contendra el ultimo id ingresado y se*/
    DECLARE producto varchar(15);
    SET producto=(concat('P',(SELECT UPPER(substring(a.Nombre,1,3)) FROM categorias as a WHERE a.Idcategoria=new.Idcategoria)));
    SET siguiente_codigo = (SELECT ifnull(max(convert(substring(Cod_producto,6),signed integer)),0) FROM productos)+1;
    SET new.Cod_producto = concat(producto,LPAD(siguiente_codigo,4,'0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `Idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  `Login` varchar(30) COLLATE latin1_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  `Email` varchar(100) COLLATE latin1_bin DEFAULT NULL,
  `Estado` bit(1) DEFAULT NULL,
  `Idprivilegio` int(11) DEFAULT NULL,
  `Fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Idusuario`),
  KEY `FK_Usuarios_Privilegios_idx` (`Idprivilegio`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Idusuario`, `Nombre`, `Login`, `Password`, `Email`, `Estado`, `Idprivilegio`, `Fecha`) VALUES
(1, 'Administrador Administrador', 'admin', 'µºh¾HtÙ\Z?Ûs%ö®L', 'administrador.administrador@crio.com', b'1', 1, '2022-10-05 13:40:44'),
(2, 'Diego Ceron', 'DC', 'µºh¾HtÙ\Z?Ûs%ö®L', 'diego.ceron@crio.com', b'0', 2, '2022-10-10 13:43:43'),
(3, 'Prueba Prueba', 'PB', 'µºh¾HtÙ\Z?Ûs%ö®L', 'prueba.prueba@crio.com', b'1', 2, '2022-11-10 21:53:36'),
(4, 'Alejandro Rodriguez', 'AR', 'räÏÇOËWÃÖ‰iœÞ', 'alejandro.rodriguez@crio.com', b'0', 2, '2022-11-10 21:54:22'),
(5, 'Invitado Invitado', 'IN', 'µºh¾HtÙ\Z?Ûs%ö®L', 'invitado.invitado@crio.com', b'1', 2, '2022-11-10 21:54:46'),
(6, 'UsuarioPB UsuarioPB', 'USPB', 'µºh¾HtÙ\Z?Ûs%ö®L', 'usuariopb.usuariopb@crio.com', b'0', 2, '2022-11-10 21:57:18');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productoscategorias`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_productoscategorias`;
CREATE TABLE IF NOT EXISTS `vw_productoscategorias` (
`Cantidad` bigint(21)
,`Nombre` varchar(50)
,`Productos` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productoscategorias`
--
DROP TABLE IF EXISTS `vw_productoscategorias`;

DROP VIEW IF EXISTS `vw_productoscategorias`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productoscategorias`  AS SELECT (select count(`a`.`Idproducto`) AS `Productos` from `productos` `a`) AS `Cantidad`, `b`.`Nombre` AS `Nombre`, concat(round(((`FN_PRODUCTOSCATEGORIA`(`b`.`Idcategoria`) / (select count(`productos`.`Idproducto`) from `productos`)) * 100),2),'%') AS `Productos` FROM (`productos` `a` join `categorias` `b` on((`a`.`Idcategoria` = `b`.`Idcategoria`))) GROUP BY `b`.`Idcategoria` ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bodegainventario`
--
ALTER TABLE `bodegainventario`
  ADD CONSTRAINT `FK_Bodegainve_Bodega` FOREIGN KEY (`Idbodega`) REFERENCES `bodega` (`Idbodega`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Bodegainve_Productos` FOREIGN KEY (`Idproducto`) REFERENCES `productos` (`Idproducto`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_Productos_Categorias` FOREIGN KEY (`Idcategoria`) REFERENCES `categorias` (`Idcategoria`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Productos_Usuarios` FOREIGN KEY (`Idusuario`) REFERENCES `usuarios` (`Idusuario`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_Usuarios_Privilegios` FOREIGN KEY (`Idprivilegio`) REFERENCES `privilegios` (`Idprivilegio`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
