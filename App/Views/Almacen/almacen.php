<?php require_once(__DIR__ . '/../Layout/encabezado.php');
$_SESSION['url'] = $_SERVER['REQUEST_URI']; ?>
<div class="content">
    <section class="py-3">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-9">
                    <h1 class="fw-bold mb-0 text-center">Tabla Almacén</h1>
                </div>
                <div class="col-lg-3 d-flex">
                    <button class="btn btn-primary w-100 align-self-center" id="agregaralmacen" data-bs-toggle="modal" data-bs-target="#MdBodega">Nuevo Almacén</button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-mix">
        <div class="container-fluid">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row">
                        <table class="table table-hover text-center" id="listaalmacen">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" style="display: none;">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Productos</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyalmacen">
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
<!--MODAL BODEGAS-->
<div class="modal fade" id="MdBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="titleformus">Agregar nuevo almacén</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="frmbodegas" action="" method="post">
                    <div class="row">
                        <input type="hidden" name="txtIdbodega" id="txtIdbodega">
                        <div class="form-group form-floating col">
                            <input type="text" maxlength="50" size="50" class="form-control" name="txtDireccion" id="txtDireccion" placeholder="Ingresar Dirección" required style="width:100%;">
                            <label for="txtDireccion" class="text-muted">&nbsp;&nbsp;&nbsp;Dirección:</label>
                            <div class="invalid-tooltip" id="valnombre">Por favor, digite la Dirección.</div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group form-floating col">
                            <input type="text" maxlength="8" size="8" class="form-control" name="txtTelefono" id="txtTelefono" placeholder="Ingresar Teléfono" required style="width:100%;" oninput="soloNumeros(this)">
                            <label for="txtApellido" class="text-muted">&nbsp;&nbsp;&nbsp;Teléfono:</label>
                            <div class="invalid-tooltip">Por favor, digite el Teléfono.</div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col">
                            <label class="text-muted d-flex justify-content-center">Estado:</label>
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input type="checkbox" name="chkEstado" id="chkEstado" value="true" class="form-check-input">
                                <label class="text-muted">Activo</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="Editarbodega" value="Editarbodega" name="accion">Guardar cambios</button>
                        <button type="submit" class="btn btn-success" id="InsertarBodega" value="InsertarBodega" name="accion">Agregar bodega</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--MODAL Productos-->
<div class="modal fade" style="display: none;" id="MdProductos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="titleformusproductos"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container" id="Productos">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper" id="bodycarrusel">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var link = document.createElement('link');
    link.rel = "stylesheet";
    link.href = "http://" + window.location.host + "/SistemaCrio/assets/css/carrusel.css";
    document.head.appendChild(link);
</script>
<script type="text/javascript">
    var link = document.createElement('link');
    link.rel = "stylesheet";
    link.href = "https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css";
    document.head.appendChild(link);
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script async src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/Almacen/almacen.js"></script>
<script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/utils/app.js"></script>
</script>
</body>

</html>