document.querySelector('#frmlogueo').addEventListener('submit', Loguin); //Desencadenador se ejecuta la funcion Loguin al enviar el formulario de login.

function Loguin(ev) { //Funcion que llamará el evento submit, se crea un parametro que manipulara el evento enviar
    ev.preventDefault(); //Se detiene el evento de enviar el formulario.
    var login = document.getElementById('txtLog').value.trim(); //Captura información escrita en el input txtLogin, eliminando espacios.
    var contra = document.getElementById('txtPas').value.trim(); //Captura información escrita en el input txtPassowrd, eliminando espacios.

    if (login === "" || contra === "") { //Si uno de los dos campos Login o paswword se encuentra vacío hacer:
        Validarfrm(); //Sweet Alert que pedirá completar la información requerida en el formulario.
        if (login === "" && contra === "") { //Si los dos campos Login y password estan vacíos hacer:
            document.getElementById("txtLog").classList.add("is-invalid"); //Pone borde rojo al input de Login
            document.getElementById("txtPas").classList.add("is-invalid"); //Pone borde rojo al input de password
            document.getElementById("valusuario").textContent = "Por favor, digite un login.";  //Agrega texto debajo del input login
            document.getElementById("valcontra").textContent = "Por favor, digite una contraseña."; //Agrega texto debajo del input Password
        } else if (login === "" && contra != "") { //Si solo el input login esta vacío hacer:
            document.getElementById("valcontra").textContent = "";
            document.getElementById("txtLog").classList.add("is-invalid");
            document.getElementById("valusuario").textContent = "Por favor, digite un login.";
        } else if (login != "" && contra === "") { //Si solo el input Password esta vacío hacer:
            document.getElementById("valusuario").textContent = "";
            document.getElementById("txtPas").classList.add("is-invalid");
            document.getElementById("valcontra").textContent = "Por favor, digite una contraseña.";
        }
        document.querySelector('#txtLog').addEventListener('keydown', function () { //Evento que se ejecuta al escribir en el input Login
            document.getElementById("txtLog").classList.remove("is-invalid"); //Quitara el borde rojo en el input login
            document.getElementById("valusuario").textContent = ""; //Quitara el texto que se encuentra debajo el input Login.
        });
        document.querySelector('#txtPas').addEventListener('keydown', function () { //Evento que se ejecuta al escribir en el input Password
            document.getElementById("txtPas").classList.remove("is-invalid"); //Quitara el borde rojo en el input Password
            document.getElementById("valcontra").textContent = ""; //Quitara el texto que se encuentra debajo el input Password.
        });
    }
    else {
        var form = new FormData(ev.target); //Capturar información del formulario.
        const xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
        xhttp.open('POST', '/SistemaCrio/Persona/index  ', true); //Se le detalla a la variable donde hará la petición, los tipos de datos que enviara y el tipo de solicitud sincronica o asincronica en este caso usaremos funcion asincronica con el argumento true de tal manera que el código no se detiene hasta que se completa la solicitud y permite realizar varias solicitudes a la vez y la sincronica todo lo contrario bloquea al navegador para para que no se ejecute nada más.
        xhttp.send(form); //Se ejecuta la solicitud y se manda como argumento la informacion capturada del formulario
        xhttp.onload = function () { //Respuesta de la solicitud
            if (this.readyState == 4 && this.status == 200) { //Si tuvo exito la solicutd hacer:
                let datos = JSON.parse(xhttp.responseText);
                if (datos.Validacion == "-1") { //datos.Validacion tiene el codigo de validación si la consulta arrojo -1 quiere decir que el usuario ingresado es incorrecto.
                    document.getElementById("valcontra").textContent = "";
                    document.getElementById("txtLog").classList.add("is-invalid");
                    document.getElementById("valusuario").textContent = "El usuario ingresado es incorrecto.";
                    document.getElementById("txtPas").classList.remove("is-invalid");
                    document.querySelector('#txtLog').addEventListener('keydown', function () {
                        document.getElementById("txtLog").classList.remove("is-invalid");
                        document.getElementById("valusuario").textContent = "";
                    });
                } else if (datos.Validacion == "-2") { //Si la consulta devolvio -2 la contraseña ingresada esta mal.
                    document.getElementById("valusuario").textContent = "";
                    document.getElementById("txtPas").classList.add("is-invalid");
                    document.getElementById("valcontra").textContent = "La contraseña ingresada es incorrecta.";
                    document.getElementById("txtLog").classList.remove("is-invalid");
                    document.querySelector('#txtPas').addEventListener('keydown', function () {
                        document.getElementById("txtPas").classList.remove("is-invalid");
                        document.getElementById("valcontra").textContent = "";
                    });
                }
                else if (datos.Validacion == "-3") { //Si la consulta devolvio -3 el usuario se encuentra desactivado
                    UserInactivo(); //Mostrar sweetalert de usuario inactivo
                }
                else if (datos.Validacion == null) { //Si regresa null la informacion enviada sobrepasa la longitud requerida en el procedimiento almacenado que se esta ejecutando
                    document.getElementById("txtLog").classList.add("is-invalid");
                    document.getElementById("txtPas").classList.add("is-invalid");
                    document.getElementById("valusuario").textContent = "El usuario ingresado es incorrecto.";
                    document.getElementById("valcontra").textContent = "La contraseña ingresada es incorrecta.";
                    document.querySelector('#txtLog').addEventListener('keydown', function () {
                        document.getElementById("txtLog").classList.remove("is-invalid");
                        document.getElementById("valusuario").textContent = "";
                    });
                    document.querySelector('#txtPas').addEventListener('keydown', function () {
                        document.getElementById("txtPas").classList.remove("is-invalid");
                        document.getElementById("valcontra").textContent = "";
                    });
                } else { //Si validacion regresa un numero mayor que cero el procedimiento almacenado encontró la información enviada en la BD y se procede a iniciar el sistema.
                    document.getElementById("txtLog").classList.remove("is-invalid");
                    document.getElementById("txtPas").classList.remove("is-invalid");
                    document.getElementById("valusuario").textContent = "";
                    document.getElementById("valcontra").textContent = "";
                    directory("App/Views/Persona/tablero.php");
                }
            }
            else { //Si ocurre un error al ejecutar la solicitud al servidor hacer:
                console.log("Error");
                Errorvalidarlogueo();
            }
        }
    }
}