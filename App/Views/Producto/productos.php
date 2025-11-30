<?php require_once(__DIR__ . '/../Layout/encabezado.php');
$_SESSION['url'] = $_SERVER['REQUEST_URI']; ?>
<div class="content">
    <section class="py-3">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-9">
                    <h1 class="fw-bold mb-0 text-center">Productos</h1>
                </div>
                <div class="col-lg-3 d-flex">
                    <button class="btn btn-primary w-100 align-self-center" id="agregarproducto" data-bs-toggle="modal" data-bs-target="#MdProductos">Nuevo Productos</button>
                    <a href="<?php echo $_SESSION['URL_PATH'] ?>/App/Views/Reports/Listaproductos.php" target="_blank" style="cursor: pointer; font-weight: bold; text-decoration:none;" title="Imprimir"><i class="fas fa-print" style="font-size: 35px; color: black;"></i><br>
                        <p class="text-dark">Imprimir</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-mix">
        <div class="container-fluid">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <table class="table text-center" id="tablaproductos">
                                <thead>
                                    <tr>
                                        <th scope="col" style="display: none;">ID</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col" style="display: none;">Precio</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col" style="display: none;">Existencias</th>
                                        <th scope="col" style="display: none;">Img</th>
                                        <th scope="col" style="display: none;">Descripcion</th>
                                        <th scope="col" style="display: none;">Usuario</th>
                                        <th scope="col" style="display: none;">Fecha</th>
                                        <th scope="col" style="display: none;">Fechaupdate</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyproductos">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-auto" id="cardprod">
                            <!-- CardListaproductos !-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
</div>
<!--MODAL Productos-->
<div class="modal fade" id="MdProductos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="titleformus">Agregar nuevo producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="frmproductos" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="txtIdproducto" id="txtIdproducto">
                    <input type="hidden" name="txtCodProducto" id="txtCodProducto">
                    <input type="hidden" name="txtIdusuario" value="<?php echo $p->getIdUsuario(); ?>">
                    <div class="row justify-content-center">
                        <div class="form-group form-floating col-sm-6">
                            <div class="container text-center">
                                <figure class="image-container">
                                    <img alt="" id="choosen-image" src="">
                                    <figcaption id="file-name">
                                    </figcaption>
                                    <div id="valimagen"></div>
                                    <input type="file" class="form-control is-invalid" name="imagen" id="imagen" accept="image/png,image/jpeg" title="Inserte una imagen.">
                                    <label for="imagen" id="btnimage"><i class="fa-solid fa-upload"></i>&nbsp; Agregar imagen</label>
                                </figure>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group form-floating col">
                            <input type="text" maxlength="50" size="50" class="form-control" name="txtNombre" id="txtNombre" placeholder="Ingresar Nombre" required>
                            <label for="txtNombre" class="text-muted">&nbsp;&nbsp;&nbsp;Nombre:</label>
                            <div class="invalid-tooltip" id="valnombre"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <select name="cboCategorias" id="listCategorias" class="form-control" required>
                            </select>
                            <label for="listCategorias" class="text-muted">&nbsp;&nbsp;&nbsp;Categoria:</label>
                            <div class="invalid-tooltip" id="valprivile">Por favor, seleccione una categoria.</div>
                        </div>
                        <div class="form-group form-floating col">
                            <input type="text" class="form-control" name="txtPrecio" id="txtPrecio" placeholder="Ingresar Precio" required oninput="soloNumeros(this)">
                            <label for="txtPrecio" class="text-muted">&nbsp;&nbsp;&nbsp;Precio:</label>
                            <div class="invalid-tooltip" id="valprecio">Por favor, digite un precio.</div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <input type="text" class="form-control" name="txtStock" id="txtStock" placeholder="Ingresar Existencias" required readonly>
                            <label for="txtExistencias" class="text-muted">&nbsp;&nbsp;&nbsp;Existencias:</label>
                            <div class="invalid-tooltip" id="valexistencias">Por favor, digite existencias.</div>
                        </div>

                        <!-- Modal Existencias -->
                        <div class="modal fade" id="MdExistencias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Existencias</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="bodybodegasmodal">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="btnexistencias" data-bs-dismiss="modal">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <textarea class="form-control" name="txtDescripcion" id="txtDescripcion" placeholder="Digite una descripción" required></textarea>
                            <label for="txtDescripcion" class="form-label">&nbsp;&nbsp;&nbsp;Descripción:</label>
                            <div class="invalid-tooltip">Ingrese una descripción.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="EditarProducto" value="EditarProducto" name="accion">Guardar cambios</button>
                        <button type="submit" class="btn btn-success" id="InsertarProducto" value="InsertarProducto" name="accion">Agregar producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var link = document.createElement('link');
    link.rel = "stylesheet";
    link.type = "text/css";
    link.href = "http://" + window.location.host + "/SistemaCrio/assets/css/inputimage.css";

    document.head.appendChild(link);
</script>
<script async src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/Producto/producto.js"></script>
</body>

</html>