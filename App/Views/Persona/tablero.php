<?php require_once(__DIR__ . '/../Layout/encabezado.php');
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>
<div class="content">
    <section class="py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <h1 class="fw-bold mb-0">Bienvenido <?php echo $nameuser[0]; ?></h1>
                    <p class="lead text-muted">Revisa la ultimá información.</p>
                </div>
                <div class="col-lg-3 d-flex">
                </div>
            </div>
        </div>
    </section>

    <section class="bg-mix">
        <div class="container-fluid">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 d-flex stat my-3">
                            <div class="mx-auto">
                                <h6 class="text-muted">Productos activos</h6>
                                <h3 class="fw-bold" id="productosactivos"></h3>
                                <div class="row" id="productosporcategoria">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex stat my-3">
                            <div class="mx-auto">
                                <h6 class="text-muted">Bodegas activas</h6>
                                <h3 class="fw-bold" id="bodegasactivas"></h3>
                                <h6 class="text-success"><i class="icon ion-md-arrow-dropup-circle"></i><strong class="fw-normal" id="bodegasactivasporcentaje"></strong></h6>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex stat my-3">
                            <div class="mx-auto">
                                <h6 class="text-muted">Usuarios activos</h6>
                                <h3 class="fw-bold" id="usuariosactivos"></h3>
                                <h6 class="text-success"><i class="icon ion-md-arrow-dropup-circle"></i><strong class="fw-normal" id="usuariosactivosporcentaje"></strong></h6>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex my-3">
                            <div class="mx-auto">
                                <h6 class="text-muted">Usuarios nuevos</h6>
                                <h3 class="fw-bold" id="usuariosnuevos"></h3>
                                <h6 class="text-success"><i class="icon ion-md-arrow-dropup-circle"></i><strong class="fw-normal" id="usuariosnuevosporcentaje"></strong></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 my-3">
                    <div class="card rounded-0 my-3">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold mb-0">Número de productos en bodega</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:40vh; width:40vw">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
</div>
</div>
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/Persona/tablero.js"></script>
</body>

</html>