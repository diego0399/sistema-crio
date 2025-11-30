let tabla = document.querySelector("#listausuarios");  //Captura tabla usuarios
function Pintartablausuario() { //Método encargado de rellenar la tabla usuarios
    var xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
    xhttp.open('GET', '/SistemaCrio/Persona/listar', true); //Ubicacion donde realizara la busquedad de la petición realizada
    xhttp.send(); //Ejecutar solicitud
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) { //Si la solicitud tuvo exito hacer:
            let datos = JSON.parse(this.responseText); //Capturar respuesta de la solicitud
            let bodyusuarios = document.querySelector('#bodyusuarios'); //Captura cuerpo de la tabla usuarios
            bodyusuarios.innerHTML = ''; //Vaciar cuerpo de la tabla usuario para borrar datos anteriores
            for (let item of datos.Usuarios["0"]) { //Recorrer todas las filas del array Usuarios y rellenar con filas y columnas el cuerpo de la tabla usuarios
                var estado = (item.Estado == "Activo") ? "<img src='http://" + window.location.host + "/SistemaCrio/assets/img/online.png' alt='' width='25' heigth='25' ></img> " + item.Estado : "<img src='http://" + window.location.host + "/SistemaCrio/assets/img/offline.png' alt='' width='25' heigth='25' ></img> " + item.Estado; //Valida estado del usuario
                bodyusuarios.innerHTML += `
            <tr>
                <td>${item.Idusuario}</td>
                <td>${item.Nombre}</td>
                <td>${item.Login}</td>
                <td style='display:none;'>${item.contra}</td>
                <td>${item.Email}</td>
                <td>${estado}</td>
                <td>${item.Privilegio}</td>
                <td>${item.Fecha}</td>
                <td>
                    <button class='btn btn-primary fa-solid fa-user-pen' id='editarusuario' title='Editar' data-bs-toggle='modal' data-bs-target='#MdUsuarios'></button>
                    <button class='btn btn-danger fa-solid fa-user-minus' id='eliminarusuario' title='Eliminar'></button>     
                </td>
                
            </tr>
            `
            } //Rellenara la tabla con filas de registros obtenidos de la solicitud realizada
            //Dar formato de datatable
            tabla = new DataTable("#listausuarios", {
                "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
                dom: 'Bfrtip',
                "responsive": true
            });
        }
        else { // Si existe un error en la solicitud
            let bodyusuarios = document.querySelector('#bodyusuarios'); //Capturar cuerpo de la tabla
            bodyusuarios.innerHTML = ''; //Vaciar cuerpo de la tabla usuario para borrar datos anteriores
            bodyusuarios.innerHTML += `<tr><td colspan="8">No se encontro información</td></tr>` //Indicar que no se encontraron datos.
        }
    }
}
Pintartablausuario(); //Ejecutar método encargado de rellenar la tabla usuarios

function Cargaprivilegio() { //Carga select Privilegios
    xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
    xhttp.open('GET', '/SistemaCrio/Persona/listar', true);  //Ubicacion donde realizara la busquedad de la petición realizada
    xhttp.send(); //Ejecutar solicitud
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {//Si la solicitud tuvo exito hacer:
            let datos = JSON.parse(this.responseText); //Capturar respuesta de la solicitud
            let bodylistaprivilegios = document.querySelector('#listPrivilegios'); //Captura select privilegios
            bodylistaprivilegios.innerHTML = `<option value='' disabled selected >Seleccionar Privilegio</option>`; //Se borran todos los options anteriores y solo deja el predeterminado
            for (let item of datos.Privilegios["0"]) { //Recorre array Privilegios y llena el select con los options de los privilegios que hay en la BD
                bodylistaprivilegios.innerHTML += `
            <option value='${item.Idprivilegio}'>${item.Nombre}</option>
            `
            } //Rellenara select con filas de registros obtenidos de la solicitud realizada
        }
    }
}
Cargaprivilegio(); //Se ejecuta el método de rellanar select de privilegios

function ValidarForm(nombre, apellido, login, contra, privilegio) { //Método encargado de validar el formulario que sirve para insertar y editar usuarios
    if (nombre == "" || apellido == "" || login == "" || contra == "" || privilegio == "") { //Si uno de los datos del formulario esta vacio hacer
        if (nombre.length == 0) { //Si nombre esta vacío
            document.getElementById("txtNombre").classList.remove("is-valid");
            document.getElementById("txtNombre").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si nombre no esta vacío
            document.getElementById("txtNombre").classList.remove("is-invalid");
            document.getElementById("txtNombre").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (apellido.length == 0) { //Si apellido esta vacío
            document.getElementById("txtApellido").classList.remove("is-valid");
            document.getElementById("txtApellido").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si apellido no esta vacío
            document.getElementById("txtApellido").classList.remove("is-invalid");
            document.getElementById("txtApellido").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (login.length == 0) { // Si login esta vacío
            document.getElementById("txtLogin").classList.remove("is-valid");
            document.getElementById("txtLogin").classList.add("is-invalid"); //Indicar que debe completar el campo
            document.getElementById("vallogin").textContent = "Por favor, digite un login.";

        }
        else { // Si login no esta vacío
            document.getElementById("txtLogin").classList.remove("is-invalid");
            document.getElementById("txtLogin").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (contra.length == 0) { // Si la contraseña esta vacía
            document.getElementById("txtPassword").classList.remove("is-valid");
            document.getElementById("txtPassword").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si la contraseña no esta vacía
            document.getElementById("txtPassword").classList.remove("is-invalid");
            document.getElementById("txtPassword").classList.add("is-valid"); //Informar que todo esta bien.
        }

        if (privilegio.length == 0) { // Si el privilegio esta vacío
            document.getElementById("listPrivilegios").classList.remove("is-valid");
            document.getElementById("listPrivilegios").classList.add("is-invalid"); //Indicar que debe completar el campo
        }
        else { // Si el privilegio no esta vacío
            document.getElementById("listPrivilegios").classList.remove("is-invalid");
            document.getElementById("listPrivilegios").classList.add("is-valid"); //Informar que todo esta bien.
        }
        return false;  //Retornar false para que indique que se debe completar el formulario
    }
    else { //Si no hay nigun dato vacio del formulario informar que todo se encuentra bien y se retorna true
        document.getElementById("txtNombre").classList.add("is-valid");
        document.getElementById("txtApellido").classList.add("is-valid");
        document.getElementById("txtLogin").classList.add("is-valid");
        document.getElementById("txtPassword").classList.add("is-valid");
        document.getElementById("listPrivilegios").classList.add("is-valid");
        return true;
    }
}
function Limpiarcomponentes() { //Método encargado de volver a su estado predeterminado el formulario 
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid");
    document.querySelector('#txtApellido').classList.remove("is-invalid", "is-valid");
    document.querySelector('#txtLogin').classList.remove("is-invalid", "is-valid");
    document.querySelector('#txtPassword').classList.remove("is-invalid", "is-valid");
    document.querySelector('#listPrivilegios').classList.remove("is-invalid", "is-valid");
    document.querySelector('#txtEmail').classList.remove("is-invalid", "is-valid");
    document.getElementById("EditarUsuario").style.display = "none";
    document.getElementById("InsertarUsuario").style.display = "block";
    document.getElementById("titleformus").textContent = "Agregar nuevo usuario";
    document.getElementById("txtIdusuario").value = "";
    document.getElementById("txtNombre").value = "";
    document.getElementById("txtApellido").value = "";
    document.getElementById("txtLogin").value = "";
    document.getElementById("txtPassword").value = "";
    document.getElementById("txtEmail").value = ".@crio.com";
    document.getElementById("chkEstado").checked = true;
    Cargaprivilegio();
}

//Insertar registros
document.querySelector("#InsertarUsuario").addEventListener('click', function (ev) {
    ev.preventDefault(); //Se detiene la accion de boton InsertarUsuario para dar paso a las siguientes instrucciones:
    //Se captura el nombre,apellido,login,contraseña y privilegio que se ha rellenado en el formulario.
    var nombre = document.getElementById("txtNombre").value.trim();
    var apellido = document.getElementById("txtApellido").value.trim();
    var login = document.getElementById("txtLogin").value.trim();
    var pass = document.getElementById("txtPassword").value.trim();
    var privilegio = document.getElementById('listPrivilegios').value.trim();
    if (ValidarForm(nombre, apellido, login, pass, privilegio)) { //Valida que los datos del formulario esten completos y si no se encuentra ningun dato vacio hacer:
        var form = new FormData(document.querySelector("#frmusuarios")); //Captura formulario completo de usuarios
        Swal.fire({ //Sweet alert que preguntara si se desea continuar
            title: '¿Desea continuar?',
            text: "Presione aceptar para continuar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#33A5FF',
            cancelButtonColor: '#758F93',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) { //Si se presiona en aceptar hacer lo siguiente:
                xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
                xhttp.open('POST', '/SistemaCrio/Persona/agregar', true);  //Ubicacion donde realizara la accion de la petición realizada
                xhttp.send(form); //Se ejecuta la solicitud y se manda como argumento la informacion capturada del formulario
                xhttp.onload = function () {
                    if (this.readyState == 4 && this.status == 200) {//Si la solicitud tuvo exito hacer:
                        let respuesta = JSON.parse(xhttp.responseText); //Capturar respuesta de la solicitud
                        if (respuesta > 0) { //Si la respuesta es mayor que cero quiere decir que el usuario fue insertado correctamente
                            Limpiarcomponentes(); //Volver al estado predeterminado el formulario
                            tabla.destroy(); //Destruir tabla actual de usuarios
                            Pintartablausuario(); //Rellenar nuevamente la tabla usuarios
                            Insert(login, respuesta); //Mostrar Sweet alert que el usuario fue insertado correctamente.
                        }
                        else if (respuesta == -1) { //Si la respuesta es -1 el login ya se encuentra registrado en la bd
                            document.getElementById("txtLogin").classList.add("is-invalid"); //Indicar que ya se encuentra registrado
                            document.getElementById("vallogin").textContent = "Este login ya esta en uso, por favor intente con otro.";
                        }
                        else if (respuesta == -2) { //Si la respuesta es -2 el email ya se encuentra registrado en la bd
                            document.getElementById("txtEmail").classList.add("is-invalid"); //Indicar que ya se encuentra registrado
                        }
                        else { //Si ocurre un error en obtener respuesta mostrar mensaje de error que no se pudo insertar un registro
                            Insert(login, respuesta);
                        }
                    }
                }
            }
        });
    }
    else { //Si la funcion ValidarForm devuelve falso mostrar un sweet alert que se debe completar el formulario
        Validarfrm();
    }
});

//Actualiza Registro
document.querySelector("#EditarUsuario").addEventListener('click', function (ev) {
    ev.preventDefault(); //Se detiene la accion de boton Editarusuario para dar paso a las siguientes instrucciones:
    var op = document.getElementById("txtOpcion").value.trim(); //Captura input sin espacios
    var nombre = document.getElementById("txtNombre").value.trim(); //Captura input sin espacios
    var apellido = document.getElementById("txtApellido").value.trim(); //Captura input sin espacios
    var login = document.getElementById("txtLogin").value.trim(); //Captura input sin espacios
    var pass = document.getElementById("txtPassword").value.trim(); //Captura input sin espacios
    var privilegio = document.getElementById('listPrivilegios').value.trim(); //Captura input sin espacios
    if (ValidarForm(nombre, apellido, login, pass, privilegio)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmusuarios")); //Capturar formulario que se enviara
        Swal.fire({ //Sweet alert mostrara mensaje de confirmacion de si se desea continuar
            title: '¿Estás seguro de actualizar este registro?',
            text: "Presione actualizar para continuar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) { //Si se presiona que si se desea continuar hacer:
                xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
                xhttp.open('POST', '/SistemaCrio/Persona/modificar', true); //Ubicacion donde realizara la accion de la petición realizada
                xhttp.send(form); //Se ejecuta la solicitud y se manda como argumento la informacion capturada del formulario
                xhttp.onload = function () {
                    if (this.readyState == 4 && this.status == 200) { //Si la solicitud tuvo exito hacer:
                        let respuesta = JSON.parse(xhttp.responseText); //Capturar respuesta de la solicitud
                        if (respuesta > 0) { //Si la respuesta es mayor que cero quiere decir que el usuario fue insertado correctamente
                            tabla.destroy(); //Borrar tabla actual
                            Pintartablausuario(); //Cargar nuevamente tabla de usuarios
                            Update(login, op, respuesta); //Mostrar SweetAlert de que el registro fue actualizado correctamente
                        }
                        else if (respuesta == -1) { //Si la respuesta es -1 el login ya se encuentra registrado en la bd
                            document.getElementById("txtLogin").classList.add("is-invalid"); //Indicar que ya se encuentra el login ingresado en la BD
                            document.getElementById("vallogin").textContent = "Este login ya esta en uso, por favor intente con otro.";
                        }
                        else if (respuesta == -2) { //Si la respuesta es -2 el email ingresado ya se encuentra en la bd
                            document.getElementById("txtEmail").classList.add("is-invalid"); //Indicar que ya se encuentra el login ingresado en la BD
                        }
                        else { //Si ocurre un error en obtener respuesta mostrar mensaje de error que no se pudo actualizar el registro
                            Update(login, op, respuesta);
                        }
                    }
                }
            }
        });
    }
    else { //Si la funcion ValidarForm devuelve falso mostrar un sweet alert que se debe completar el formulario
        Validarfrm();
    }
});

//Eventos de la tabla
addEvent(document, 'click', '#agregarusuario', function () {   //Cuando se presiona clic en el boton Agregar Usuario
    Limpiarcomponentes(); //Volver al estado predeterminado el formulario
});

//Cuando se presiona clic en editar de un registro, se recolecta informacion de la fila seleccionada y la manda al formulario editar usuario
addEvent(document, 'click', '#editarusuario', function (event) {
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtApellido').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtLogin').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtPassword').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#listPrivilegios').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtEmail').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById("txtEmail").value = ""; //Borra valor del input
    document.getElementById("InsertarUsuario").style.display = "none"; //Ocultar boton Insertar usuario
    document.getElementById("EditarUsuario").style.display = "block"; //Mostrar boton Editar usuario
    document.getElementById("titleformus").textContent = "Editar usuario";  //Mostrar titulo editar usuario
    document.getElementById("txtIdusuario").value = event.target.parentNode.parentNode.children[0].textContent; //Capturar valor de la primera columna de la fila seleccionada y setear el valor al input
    var nom = event.target.parentNode.parentNode.children[1].textContent; //Capturar valor de la segunda columna de la fila seleccionada
    let arr = nom.split(' '); //Separar Columna de nombre por Nombre y Apellido
    document.getElementById("txtNombre").value = arr[0]; //Mandar valor de nombre al input
    document.getElementById("txtApellido").value = arr[1] == undefined ? "" : arr[1]; //Mandar valor de Apellido al input, si el usuario no tiene apellido se mostrara ""
    document.getElementById("txtLogin").value = event.target.parentNode.parentNode.children[2].textContent; //Capturar valor de la tercera columna de la fila seleccionada y setear el valor al input
    document.getElementById("txtPassword").value = event.target.parentNode.parentNode.children[3].textContent; //Capturar valor de la cuarta columna de la fila seleccionada y setear el valor al input
    document.getElementById("txtEmail").value = document.getElementById("txtNombre").value.trim().toLowerCase() + "." + document.getElementById("txtApellido").value.trim().toLowerCase() + "@crio.com"; //Asignarle al email el nombre y el apellido del usuario para crear un email
    var estado = event.target.parentNode.parentNode.children[5].textContent.toLowerCase().trim();
    var privilegio = event.target.parentNode.parentNode.children[6].textContent; //Capturar valor de la septima columna de la fila seleccionada y setear el valor al input
    if (estado === "activo") { //Activa checkbox
        document.getElementById("chkEstado").checked = true;
    }
    else { //Desactiva checkbox
        document.getElementById("chkEstado").checked = false;
    }

    if (privilegio === "Administrador") { //Activa option de administrador
        document.getElementById('listPrivilegios').value = 1;
    }
    else if (privilegio === "Usuario") { //Activa option de usuario normal
        document.getElementById('listPrivilegios').value = 2;
    }
});

//Cuando se presiona clic en eliminar 
addEvent(document, 'click', '#eliminarusuario', function (event) {
    id = event.target.parentNode.parentNode.children[0].textContent; //Capturar valor de la primera columna de la fila seleccionada
    login = event.target.parentNode.parentNode.children[2].textContent; //Capturar valor de la segunda columna de la fila seleccionada
    Swal.fire({ //Sweet alert mostrara mensaje de confirmacion de si se desea continuar
        title: 'Estás seguro de eliminar a ' + login + '?',
        text: "Al eliminar un registro no podrá recuperarlo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
            var form = new FormData(); //Crear formulario
            form.append('txtIdusuario', id); //Crear variable POST y mandar como valor el Id del usuario seleccionado
            xhttp = new XMLHttpRequest(); //Variable que se encargara de ejecutar la petición al servidor
            xhttp.open('POST', '/SistemaCrio/Persona/eliminar', true); //Ubicacion donde realizara la accion de la petición realizada
            xhttp.send(form); //Se ejecuta la solicitud y se manda como argumento la informacion capturada del formulario
            xhttp.onload = function () {
                if (this.readyState == 4 && this.status == 200) { //Si la solicitud tuvo exito hacer:
                    let respuesta = JSON.parse(xhttp.responseText); //Capturar respuesta de la solicitud
                    if (respuesta > 0) { //Si se elimina correctamente
                        tabla.destroy(); //Borrar tabla actual
                        Pintartablausuario(); //Cargar nuevamente tabla de usuarios
                        Delete(login, respuesta); //Mostrar Sweet alert de usuario eliminado correctamente
                    }
                    else { //Si ocurre un problema al eliminar un usuario mostrar sweet alert que el usuario no pudo ser eliminado
                        Delete(login, respuesta);
                    }
                }
            }
        }
    });
});

document.querySelector('#txtNombre').addEventListener('keyup', function (e) { //Cuando se escribe en un input
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtEmail').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById('txtEmail').value = e.target.value.trim().toLowerCase() + "." + document.querySelector('#txtApellido').value.trim().toLowerCase() + "@crio.com"; //Mandar nombre al input email para generar un correo
});
document.querySelector('#txtApellido').addEventListener('keyup', function (e) { //Cuando se escribe en un input
    document.querySelector('#txtApellido').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtEmail').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById('txtEmail').value = document.querySelector('#txtNombre').value.trim().toLowerCase() + "." + e.target.value.trim().toLowerCase() + "@crio.com"; //Mandar el apellido al input email para generar un correo
});
document.querySelector('#txtLogin').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtLogin').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});
document.querySelector('#txtPassword').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtPassword').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});
document.querySelector('#listPrivilegios').addEventListener("change", function () { //Cuando se escribe en un input
    document.querySelector('#listPrivilegios').classList.remove("is-invalid"); //Remover clase de boostrap is-valid y is-invalid del input
});