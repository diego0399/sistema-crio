<?php
if (!isset($_SESSION)) {
    session_start();
    $_SESSION['URL_PATH'] = "http://" . $_SERVER['HTTP_HOST'] . "/SistemaCrio";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--Iconos-->
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <!--Iconos Font Awesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <!--Fuente-->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <!--Estilos-->
    <link rel="stylesheet" href="<?php echo $_SESSION['URL_PATH']; ?>/assets/css/styleslogin.css">
    <title>Ingresar</title>
</head>

<body>
    <br><br>
    <section class="intro">
        <div class="mask d-flex align-items-center h-100 gradient-custom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="card">
                            <div class="card-body-p-5">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-6 col-xl-7">
                                        <div class="text-center pt-md-5 pb-5 my-md-5" style="padding-right: 24px;">
                                            <img src="<?php echo $_SESSION['URL_PATH']; ?>/assets/img/logonegro.png" class="img-fluid me-2 position-absolute" id="logo" width="150" height="150" style="left:230px;">
                                            <i class="icon ion-md-laptop" style="color:#d6d6d6;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4 text-center">
                                        <form action="" method="post" id="frmlogueo">
                                            <h2 class="fw-bold-md-4 pb-2 text-center">Iniciar sesión</h2>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="txtLog" placeholder="name@example.com" name="txtLogin">
                                                <label for="txtLog" class="text-muted">Login</label>
                                                <div class="text-danger" id="valusuario"></div>
                                            </div>
                                            <div class="form-floating">
                                                <input type="password" class="form-control" id="txtPas" placeholder="Password" name="txtPassword">
                                                <label for="txtPas" class="text-muted">Contraseña</label>
                                                <div class="text-danger" id="valcontra"></div>
                                            </div>
                                            <br>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 align-self-center" name="accion" id="accion" value="Ingresar">Ingresar</button>
                                                <!--<p class="small mt-3 mb-4 text-danger">Datos ingresados incorrectos!</p>-->
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popper and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/alerts/alerts.js"></script>
    <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/utils/util.js"></script>
    <script src="<?php echo $_SESSION['URL_PATH']; ?>/assets/js/Persona/login.js"></script>
</body>
</html>