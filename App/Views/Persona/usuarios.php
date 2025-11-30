<?php require_once(__DIR__ . '/../Layout/encabezado.php');
$_SESSION['url'] = $_SERVER['REQUEST_URI']; ?>
<div class="content">
    <section class="py-3">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-9">
                    <h1 class="fw-bold mb-0 text-center">Tabla Usuarios</h1>
                </div>
                <div class="col-lg-3 d-flex">
                    <button class="btn btn-primary w-100 align-self-center" id="agregarusuario" data-bs-toggle="modal" data-bs-target="#MdUsuarios">Nuevo Usuario</button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-mix">
        <div class="container-fluid">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row">
                        <table class="table table-hover text-center" id="listausuarios">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Login</th>
                                    <th scope="col" style="display: none;">Password</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Privilegio</th>
                                    <th scope="col">Fecha de registro</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyusuarios">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
</div>
<!--MODAL USUARIOS-->
<div class="modal fade" id="MdUsuarios" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="titleformus">Agregar nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="frmusuarios" action="" method="post">
                    <div class="row">
                        <div class="form-group form-floating col">
                            <input type="text" maxlength="25" size="25" class="form-control" name="txtNombre" id="txtNombre" aria-describedby="emailHelp" placeholder="Ingresar su Nombre" required style="width:100%;" onkeypress="return sololetras(event);">
                            <label for="txtNombre" class="text-muted">&nbsp;&nbsp;&nbsp;Nombre:</label>
                            <div class="invalid-tooltip" id="valnombre">Por favor, digite un nombre.</div>
                        </div>

                        <div class="form-group form-floating col">
                            <input type="text" maxlength="25" size="25" class="form-control" name="txtApellido" id="txtApellido" aria-describedby="emailHelp" placeholder="Ingresar su Nombre" required style="width:100%;" onkeypress="return sololetras(event);">
                            <label for="txtApellido" class="text-muted">&nbsp;&nbsp;&nbsp;Apellido:</label>
                            <div class="invalid-tooltip" id="valapellido">Por favor, digite un apellido.</div>
                        </div>
                        <input type="hidden" class="form-control" name="txtIdusuario" id="txtIdusuario" aria-describedby="emailHelp">
                        <input type="hidden" class="form-control" name="txtOpcion" id="txtOpcion" aria-describedby="emailHelp" value="1" required>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <input type="text" maxlength="30" size="30" class="form-control" name="txtLogin" id="txtLogin" aria-describedby="emailHelp" placeholder="Ingresar su Login" required>
                            <label for="txtLogin" class="text-muted">&nbsp;&nbsp;&nbsp;Login:</label>
                            <div class="invalid-tooltip" id="vallogin"></div>
                        </div>
                        <div class="form-group form-floating col">
                            <input type="password" maxlength="8" size="8" class="form-control" name="txtPassword" id="txtPassword" aria-describedby="emailHelp" placeholder="Ingresar su contraseña" required>
                            <label for="txtPassword" class="text-muted">&nbsp;&nbsp;&nbsp;Password:</label>
                            <div class="invalid-tooltip" id="valpass">Por favor, digite una contraseña.</div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group form-floating">
                        <input type="Email" maxlength="100" size="100" class="form-control" name="txtEmail" id="txtEmail" aria-describedby="emailHelp" placeholder="Ingresar su Correo" required readonly>
                        <label for="txtEmail" class="text-muted">Email:</label>
                        <div class="invalid-tooltip" id="valemail">Este correo ya esta en uso, por favor intente con otro.</div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <select name="cboPrivilegio" id="listPrivilegios" class="form-control" required>
                            </select>
                            <label for="listPrivilegios" class="text-muted">&nbsp;&nbsp;&nbsp;Privilegio:</label>
                            <div class="invalid-tooltip" id="valprivile">Por favor, seleccione un privilegio.</div>
                        </div>
                        <div class="form-group col">
                            <label class="text-muted">Estado:</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="chkEstado" id="chkEstado" value="true" class="form-check-input">
                                <label class="text-muted">Activo</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="EditarUsuario" value="EditarUsuario" name="accion">Guardar cambios</button>
                        <button type="submit" class="btn btn-success" id="InsertarUsuario" value="InsertarUsuario" name="accion">Agregar usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script async src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/Persona/usuarios.js"></script>
</script>
</body>

</html>