<?php
if (!isset($_SESSION)) {
    session_start();
    $_SESSION['URL_PATH'] = "http://" . $_SERVER['HTTP_HOST'] . "/SistemaCrio";
}
require_once(__DIR__ . '/../../Models/Persona/Persona.php');
if (!isset($_SESSION['persona'])) {
    $_SESSION['URL_PATH'] = "http://" . $_SERVER['HTTP_HOST'] . "/SistemaCrio";
    echo "<script>window.location.replace('" . $_SESSION['URL_PATH'] . "/App/Views/Persona/index.php')</script>";
}
$p = new Persona();
$p = unserialize($_SESSION['persona']);
?>
<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- Datatable -->
    <link rel="stylesheet" href="<?php echo $_SESSION['URL_PATH']; ?>/assets/css/datatablestyles.css">
    <!--Iconos Ionicons-->
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <!--Iconos Font Awesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <!--Fuente-->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <!--Estilos propios-->
    <link rel="stylesheet" href="<?php echo $_SESSION['URL_PATH']; ?>/assets/css/styles.css">
    <title>CRIO</title>
</head>

<body class="overflow-auto">
    <div class="d-flex">
        <!-- 1. </div> -->
        <!--SIDEBAR-->
        <div id="sidebar-container" class="bg-primary">
            <div class="logo">
                <img src="<?php echo $_SESSION['URL_PATH']; ?>/assets/img/logoblanco.png" class="img-fluid me-2" width="150" height="150">
            </div>
            <div class="menu">
                <a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Persona/tablero.php" class="d-block text-light p-3"><i class="icon ion-md-apps me-2 lead"></i>Tablero</a>
                <?php if ($p->getIdPrivilegio() == 1) { ?>
                    <a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Persona/usuarios.php" class="d-block text-light p-3"><i class="icon ion-md-people me-2 lead"></i>Usuarios</a>
                <?php } ?>
                <div class="dropdown">
                    <a href="#" class="d-block text-light p-3 dropdown-toggle" id="dropdownMenuProductos" data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static" aria-expanded="false"><i class="fas fa-cubes"></i> Productos</a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end dropdown-menu-lg-start" aria-labelledby="dropdownMenuProductos">
                        <li><a class="dropdown-item" href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Producto/productos.php"><i class="fas fa-cube"></i> Registro</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Producto/categorias.php"><i class="fas fa-tags"></i> Categorías</a></li>
                    </ul>
                </div>
                <a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Almacen/almacen.php" class="d-block text-light p-3"><i class="fa-solid fa-truck"></i> Almacén</a>
                <a href="#" class="d-block text-light p-3" data-bs-toggle="modal" data-bs-target="#MdEditarUsuario"><i class="icon ion-md-person me-2 lead"></i> Perfil</a>
                <a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Layout/exit.php" class="d-block text-light p-3"><i class="icon ion-md-settings me-2 lead"></i> Configuración</a>
            </div>
        </div>
        <!--NAVBAR-->
        <div class="w-100">
            <!-- 2. </div> -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="d-flex position-relative d-inline-block">
                        <input class="form-control me-2" type="search" placeholder="Buscar" id="myInput" aria-label="Buscar">
                        <button class="btn btn-search position-absolute" type="button"><i class="icon ion-md-search"></i></button>
                        <ul class="list-group position-absolute me-2 list-unstyled" style="z-index:1; top:40px; display:none;" id="myList">
                            <li class="articulo"><a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Producto/categorias.php" class="list-group-item list-group-item-action">Categorias</a></li>
                            <li class="articulo"><a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Producto/productos.php" class="list-group-item list-group-item-action">Productos</a></li>
                            <li class="articulo"><a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Persona/usuarios.php" class="list-group-item list-group-item-action">Usuarios</a></li>
                            <li class="articulo"><a href="<?php echo $_SESSION['URL_PATH']; ?>/App/Views/Almacen/almacen.php" class="list-group-item list-group-item-action">Almacen</a></li>
                        </ul>
                    </div>

                    <div class="wap text-center">
                        <h5 class="fw-bold ">
                            <strong class="reloj">
                                <strong id="horas" class="horas"></strong>
                                <strong>:</strong>
                                <strong id="minutos" class="minutos"></strong>
                                <strong>:</strong>
                                <strong class="caja-segundos">
                                    <strong id="segundos" class="segundos"></strong>
                                    <strong id="ampm" class="ampm"></strong>
                                </strong>
                            </strong>
                            <br>
                            <strong class="fecha">
                                <strong id="diaSemana" class="diaSemana"></strong>
                                <strong id="dia" class="dia"></strong>
                                <strong>de </strong>
                                <strong id="mes" class="mes"></strong>
                                <strong>del </strong>
                                <strong id="year" class="year"></strong>
                            </strong>
                        </h5>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static" aria-expanded="false">
                                    <img src="<?php echo $_SESSION['URL_PATH']; ?>/assets/img/User_icon_2.png" class="img-fluid rounded-circle avatar me-2">
                                    <strong class="txt"><?php $nameuser = explode(' ', $p->getNombre());
                                                        echo $nameuser[0]; ?></strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#MdEditarUsuario">Mi perfil</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="/SistemaCrio/Persona/exit">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


            <!-- Aca Termina encabezado-->
            <!--Mi perfil -->
            <div class="modal fade" id="MdEditarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Mi perfil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" class="needs-validation" id="frmusuariomiperfil" method="post">
                                <input type="hidden" class="form-control" value="<?php echo $p->getIdusuario(); ?>" name="txtIdusuario" aria-describedby="emailHelp">
                                <input type="hidden" class="form-control" name="txtOpcion" aria-describedby="emailHelp" value="2">
                                <select name="cboPrivilegio" class="form-control" required style="display: none;">
                                    <option value="<?php echo $p->getIdPrivilegio(); ?>"></option>
                                </select>
                                <div class="row">
                                    <div class="form-group form-floating col">
                                        <input type="text" value="<?php echo $nameuser[0]; ?>" maxlength="25" size="25" class="form-control" name="txtNombre" id="txtNombre1" aria-describedby="emailHelp" placeholder="Ingresar su Nombre" style="width:100%;" onkeypress="return sololetras(event);">
                                        <label for="txtNombre1" class="text-muted">&nbsp;&nbsp;&nbsp;Nombre:</label>
                                        <div class="invalid-tooltip" id="valnombre1">Por favor, digite un nombre.</div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group form-floating col">
                                        <input type="text" value="<?php echo $nameuser[1]; ?>" maxlength="25" size="25" class="form-control" name="txtApellido" id="txtApellido1" aria-describedby="emailHelp" placeholder="Ingresar su Nombre" style="width:100%;" onkeypress="return sololetras(event);">
                                        <label for="txtApellido1" class="text-muted">&nbsp;&nbsp;&nbsp;Apellido:</label>
                                        <div class="invalid-tooltip" id="valapellido1">Por favor, digite un apellido.</div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group form-floating col">
                                        <input type="text" value="<?php echo $p->getLogin(); ?>" maxlength="30" size="30" class="form-control" name="txtLogin" id="txtLogin1" aria-describedby="emailHelp" placeholder="Ingresar su Login">
                                        <label for="txtLogin1" class="text-muted">&nbsp;&nbsp;&nbsp;Login:</label>
                                        <div class="invalid-tooltip" id="vallogin1"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group form-floating col">
                                        <input type="password" value="<?php echo $p->getPassword(); ?>" maxlength="8" size="8" class="form-control" name="txtPassword" id="txtPassword1" aria-describedby="emailHelp" placeholder="Ingresar su contraseña">
                                        <label for="txtPassword1" class="text-muted">&nbsp;&nbsp;&nbsp;Password:</label>
                                        <div class="invalid-tooltip" id="valpass1">Por favor, digite una contraseña.</div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group form-floating col">
                                        <input type="Email" value="<?php echo $p->getEmail(); ?>" maxlength="100" size="100" class="form-control" name="txtEmail" id="txtEmail1" aria-describedby="emailHelp" placeholder="Ingresar su Correo" required readonly>
                                        <label for="txtEmail1" class="text-muted">&nbsp;&nbsp;&nbsp;Email:</label>
                                        <div class="invalid-tooltip" id="valemail1">Este correo ya esta en uso, por favor intente con otro.</div>
                                    </div>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="Editarmiperfil" value="Editarmiperfil" name="accion">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Popper, Bootstrap JS-->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

            <!-- Datatable -->
            <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

            <!-- SweetAlert -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <!--Reloj -->
            <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/reloj/reloj.js"></script>
            <!--Alerts-->
            <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/alerts/alerts.js"></script>
            <!--Utilidades -->
            <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/utils/util.js"></script>
            <!--Se encarga de editar mi perfil-->
            <script type="text/javascript">
                document.querySelector("#frmusuariomiperfil").addEventListener("submit", Editarmiperfil);
            </script>
            <?php
            //Muestra alerta de logueo
            if (isset($_SESSION['logueoalert'])) {
                echo "<script>Logueo();</script>";
                unset($_SESSION['logueoalert']);
            }
            ?>