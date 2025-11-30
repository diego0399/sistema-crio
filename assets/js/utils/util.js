function directory(dir) { //Funcion que contiene la url del proyecto
    window.location.replace("http://" + window.location.host + "/SistemaCrio/" + dir + "");
}

//funciona como $(document).on("evento", "elemento", function ());
function addEvent(parent, evt, selector, handler) {
    parent.addEventListener(evt, function (event) {
        if (event.target.matches(selector + ', ' + selector + ' *')) {
            handler.apply(event.target.closest(selector), arguments);
        }
    }, false);
}

//Solo letras y sin espacios
function sololetras(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    letras = "qwertyuiopasdfghjklñzxcvbnm ";

    especiales = "8-37-38-46-164";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            break;
        }
    }

    if (e.keyCode == 32 || e.code == "Space") {
        return false;
    }

    if (letras.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
    }
}

//Solo permite introducir numeros.
function soloNumeros(el) {
    const regex = /^[0-9.]+$/
    if (!regex.test(el.value)) {
        el.value = el.value.substring(0, el.value.length - 1)
    }
}


function ValidarForm(nombre, apellido, login, contra) { //Método encargado de validar el formulario que sirve para insertar y editar usuarios
    if (nombre == "" || apellido == "" || login == "" || contra == "") { //Si uno de los datos del formulario esta vacio hacer
        if (nombre.length == 0) { //Si nombre esta vacío
            document.getElementById("txtNombre1").classList.remove("is-valid");
            document.getElementById("txtNombre1").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si nombre no esta vacío
            document.getElementById("txtNombre1").classList.remove("is-invalid");
            document.getElementById("txtNombre1").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (apellido.length == 0) { //Si apellido esta vacío
            document.getElementById("txtApellido1").classList.remove("is-valid");
            document.getElementById("txtApellido1").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si apellido no esta vacío
            document.getElementById("txtApellido1").classList.remove("is-invalid");
            document.getElementById("txtApellido1").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (login.length == 0) { // Si login esta vacío
            document.getElementById("txtLogin1").classList.remove("is-valid");
            document.getElementById("txtLogin1").classList.add("is-invalid"); //Indicar que debe completar el campo
            document.getElementById("vallogin1").textContent = "Por favor, digite un login.";

        }
        else { // Si login no esta vacío
            document.getElementById("txtLogin1").classList.remove("is-invalid");
            document.getElementById("txtLogin1").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (contra.length == 0) { // Si la contraseña esta vacía
            document.getElementById("txtPassword1").classList.remove("is-valid");
            document.getElementById("txtPassword1").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si la contraseña no esta vacía
            document.getElementById("txtPassword1").classList.remove("is-invalid");
            document.getElementById("txtPassword1").classList.add("is-valid"); //Informar que todo esta bien.
        }

        return false;  //Retornar false para que indique que se debe completar el formulario
    }
    else { //Si no hay nigun dato vacio del formulario informar que todo se encuentra bien y se retorna true
        document.getElementById("txtNombre1").classList.add("is-valid");
        document.getElementById("txtApellido1").classList.add("is-valid");
        document.getElementById("txtLogin1").classList.add("is-valid");
        document.getElementById("txtPassword1").classList.add("is-valid");
        return true;
    }
}



function Editarmiperfil(ev) { //Funcion que sirve para actualizar los datos de un usuario
    ev.preventDefault();
    var nombre = document.getElementById("txtNombre1").value.trim(); //Captura valor de input sin espacios
    var apellido = document.getElementById("txtApellido1").value.trim(); //Captura valor de input sin espacios
    var login = document.getElementById("txtLogin1").value.trim(); //Captura valor de input sin espacios
    var pass = document.getElementById("txtPassword1").value.trim(); //Captura valor de input sin espacios
    if (ValidarForm(nombre, apellido, login, pass)) { //Validar que las variables esten completas
        var form = new FormData(document.querySelector("#frmusuariomiperfil")); //Captura formulario que se enviara
        Swal.fire({ //Mostrar sweet alert de si se desea continuar
            title: '¿Estás seguro de actualizar este registro?',
            text: "Presione actualizar para continuar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) { //Si se presiona que si
                xhttp = new XMLHttpRequest(); //Instanciar clase de la variable que hara la solicitud al servidor
                xhttp.open('POST', '/SistemaCrio/Persona/modificar', true); //Ubicacion donde se realizar la peticion
                xhttp.send(form); //Se ejecuta la solicitud y se manda como argumento la informacion capturada del formulario
                xhttp.onload = function () {
                    if (this.readyState == 4 && this.status == 200) { //Si la solicitud tuvo exito
                        let respuesta = JSON.parse(xhttp.responseText);
                        if (respuesta > 0) { //Se actualizo correctamente mostrar sweetalert que el perfil fue actualizado correctamente
                            Update(login, 2, respuesta);
                        }
                        else if (respuesta == -1) { //El login ya esta en uso
                            document.getElementById("txtLogin1").classList.add("is-invalid"); //Indicar que el login ya esta registrado en la BD
                            document.getElementById("vallogin1").textContent = "Este login ya esta en uso, por favor intente con otro."; //Mostrar mensaje
                        }
                        else if (respuesta == -2) { //El email ya esta en uso
                            document.getElementById("txtEmail1").classList.add("is-invalid"); //Indicar que el email ya esta registrado en la BD
                        }
                        else { //Si hay un error en actualizar el perfil mostrar un sweet alert que no se pudo actualizar el perfil
                            Update(login, 2, respuesta);
                        }
                    }
                }
            }
        });
    }
    else { //Si las variables estan vacias mostrar sweet alert que el formulario debe estar completo
        Validarfrm();
    }
}



//Eventos
document.querySelector('#txtNombre1').addEventListener('keyup', function (e) { //Evento cuando se escribe en el input
    document.querySelector('#txtNombre1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
    document.querySelector('#txtEmail1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
    document.getElementById('txtEmail1').value = e.target.value.trim().toLowerCase() + "." + document.querySelector('#txtApellido1').value.trim().toLowerCase() + "@crio.com"; //Captura el nombre y  forma un email
});
document.querySelector('#txtApellido1').addEventListener('keyup', function (e) { //Evento cuando se escribe en el input
    document.querySelector('#txtApellido1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
    document.querySelector('#txtEmail1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
    document.getElementById('txtEmail1').value = document.querySelector('#txtNombre1').value.trim().toLowerCase() + "." + e.target.value.trim().toLowerCase() + "@crio.com"; //Captura el apellido y  forma un email
});
document.querySelector('#txtLogin1').addEventListener('keyup', function () { //Evento cuando se escribe en el input
    document.querySelector('#txtLogin1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
});
document.querySelector('#txtPassword1').addEventListener('keyup', function () { //Evento cuando se escribe en el input
    document.querySelector('#txtPassword1').classList.remove("is-invalid", "is-valid"); //Remover clases de Bootstrap
});