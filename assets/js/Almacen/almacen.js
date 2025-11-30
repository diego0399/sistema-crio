let tabla = document.querySelector("#listaalmacen");  //Captura tabla almacen
async function Pintartablaalmacen() { //Metodo que cargara la informacion en la tabla almacen
    try {
        let resp = await fetch("/SistemaCrio/Almacen/listar", { //Variable que guarda los parametros de la solicitud que se desea realizar 
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodyalmacen = document.querySelector('#bodyalmacen'); //Seleccionar cuerpo de la tabla almacen
        bodyalmacen.innerHTML = '';//Limpiar cuerpo de la tabla
        for (let item of datos.Bodegas["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            var estado = (item.Estado == "Activo") ? "<img src='http://" + window.location.host + "/SistemaCrio/assets/img/online.png' alt='' width='25' heigth='25' ></img> Disponible" : "<img src='http://" + window.location.host + "/SistemaCrio/assets/img/offline.png' alt='' width='25' heigth='25' ></img> No disponible"; //Valida el estado del almacen
            var productos = (item.Productos > 0) ? "<button type='button' id='showproductos' class='btn btn-secondary' title='Ver productos' data-bs-toggle='modal' data-bs-target='#MdProductos'> <i class='fa-solid fa-eye'></i> <strong>Ver</strong></button> " + item.Productos : item.Productos; //Valida la existencias del producto
            bodyalmacen.innerHTML += `
            <tr>
                <td style='display:none;'>${item.Idbodega}</td>
                <td>${item.Nombre}</td>
                <td>${item.Direccion}</td>
                <td>${item.Telefono}</td>
                <td>${productos}</td>
                <td>${estado}</td>
                <td>
                    <button class='btn btn-primary fa-solid fa-wrench' id='editarbodega' title='Editar' data-bs-toggle='modal' data-bs-target='#MdBodega'></button>
                    <button class='btn btn-danger fa-solid fa-trash' id='eliminarbodega' title='Eliminar'></button>     
                </td>
                
            </tr>
            ` //Rellenara la tabla con filas de registros obtenidos de la solicitud realizada
        }
        //Dar formato de datatable
        tabla = new DataTable("#listaalmacen", {
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
            dom: 'Bfrtip',
            "responsive": true
        });
    } catch (err) { //Si ocurre un error, mostrara el error 
        console.log("Ocurrio un error: " + err);
    }
}
Pintartablaalmacen(); //Ejecutar metodo que rellenara la tabla almacen
function ValidarForm(direccion, telefono) { //Metodo que valida que los formularios este completos
    if (direccion == "" || telefono == "") { //Si una o más variables se encuentra vacia hacer:
        if (direccion.length == 0) { //Si la variable esta vacia hacer:
            document.querySelector("#txtDireccion").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtDireccion").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        } else { //En caso que no este vacia
            document.querySelector("#txtDireccion").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.querySelector("#txtDireccion").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        if (telefono.length == 0) { //Si la variable esta vacia hacer:
            document.querySelector("#txtTelefono").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtTelefono").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.querySelector("#txtTelefono").classList.remove("is-invalid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtTelefono").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        return false; //Retornar false si los datos estan vacios
    }
    else { //Si todos los datos estan completos 
        document.querySelector("#txtDireccion").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.querySelector("#txtTelefono").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        return true; //Retornar true
    }
}
async function ListarProductobodegas(id) { //Metodo encargado de listar los productos de una bodega
    try {
        var form = new FormData(); //Crear un formulario
        form.append('txtIdbodega', id); //Crear una variable POST y enviarle como valor el parametro que se recibira que contiene el id de la bodega
        let resp = await fetch("/SistemaCrio/Almacen/listar", { //Variable que guarda los parametros de la solicitud que se desea realizar 
            method: 'POST', //Tipo de envio de datos
            mode: 'cors',
            cache: 'no-cache',
            body: form //Datos a enviar
        });
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodycarrusel = document.querySelector('#bodycarrusel'); //Seleccionar cuerpo del carrusel que contendra los productos de la bodega
        bodycarrusel.innerHTML = ''; //Limpiar cuerpo del carrusel
        for (let item of datos.Bodegaproductos["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            if (item.Idbodega == -1) { //Si no se encuentra informacion mostrar mensaje que no se encontraron datos
                bodycarrusel.innerHTML = `
                <h4 class='text-center'>No se encontraron datos!</h4>
            `
            }
            else { //Si se encuetra informacion se creara una card por producto que contendra la informacion del producto
                var urlimagen = "http://" + window.location.host + "/SistemaCrio/assets/img/Productos/" + item.Codigo + "/" + item.Imagen;
                bodycarrusel.innerHTML += `
                <div class="swiper-slide">
                    <div class='row m-2'>
                        <div class="col-auto"><strong class="text-muted">Creador: ${item.Login}</strong></div>
                        <div class="col-auto"><strong class="text-muted">Agregado: ${item.Fechaingreso}</strong></div>
                    </div>
                    <img src="${urlimagen}" alt="" >
                    <div class="card-description">
                        <div class="card-title">
                            <h4>${item.Codigo}</h4>
                        </div>
                        <div class="card-text">
                            <strong>${item.Nombre}</strong>
                        </div>
                    </div>
                    <div class='row m-2'>
                        <div class="col-auto"><strong class="text-muted">Actualizado: ${item.Fechaultimamodificacion}</strong></div>
                        <div class="col-auto"><strong class="text-muted">Stock: ${item.Existencias}</strong></div>
                    </div>
                </div>
            `
            }
        }

    } catch (err) { //Si ocurre un error en la solicitud mostrar error
        console.log("Ocurrio un error: " + err);
    }
}

function Limpiarcomponentes() { //Método encargado de borrar todo lo del formulario
    document.querySelector('#txtDireccion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtTelefono').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById("Editarbodega").style.display = "none"; //oculta boton de editar 
    document.getElementById("InsertarBodega").style.display = "block"; //muestra el boton insertar en el formulario
    document.getElementById("titleformus").textContent = "Agregar nuevo almacén"; //Muestra titulo agregar almacen
    document.querySelector("#txtDireccion").value = ""; //Borra valor del input
    document.querySelector("#txtTelefono").value = ""; //Borra valor del input
    document.querySelector("#chkEstado").checked = true; //Pone el valor default del checkbox
}


document.querySelector("#InsertarBodega").addEventListener("click", function (ev) { //Evento click cuando se de click en enviar formulario para insertar nuevo almacen
    ev.preventDefault(); //Detener evento default del formulario
    var direccion = document.querySelector("#txtDireccion").value.trim(); //Capturar el valor del input sin espacios
    var telefono = document.querySelector("#txtTelefono").value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(direccion, telefono)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmbodegas")); //Capturar formulario que se enviara
        Swal.fire({ //Sweet alert mostrara mensaje de confirmacion de si se desea continuar
            title: '¿Desea continuar?',
            text: "Presione aceptar para continuar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#33A5FF',
            cancelButtonColor: '#758F93',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            //document.querySelector("#frmbodegas").submit();
            if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
                try {
                    fetch("/SistemaCrio/Almacen/agregar", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data.Respuesta > 0) { //Si la inserccion se realizo correctamente
                                Limpiarcomponentes(); //Limpiar formulario
                                tabla.destroy(); //Borrar tabla actual de almacenes
                                Pintartablaalmacen(); //Cargar nuevamente los registro de la tabla almacen
                                Insert(data.Codigo, data.Respuesta); //Mostrar sweetalert de almacen registrado correctamente
                            }
                            else { //Si no se inserta correctamente el almacen 
                                Insert(null, data.Respuesta); //Mostrar sweetalert de que no se pudo ingresar el almacen
                            }

                        })
                } catch (error) { //Si ocurre un error en la solicitud mostrar el error
                    console.log("Ocurrio un error: " + error);
                }
            }
        });
    }
    else { //Si las variables estan vacias
        Validarfrm(); //Mostrar sweetalert que avisa que el formulario debe ser completado
    }
});

document.querySelector("#Editarbodega").addEventListener("click", function (ev) { //Evento click cuando se de click en enviar formulario para actualizar un almacen
    ev.preventDefault(); //Detener evento default del formulario
    var direccion = document.querySelector("#txtDireccion").value.trim(); //Capturar el valor del input sin espacios
    var telefono = document.querySelector("#txtTelefono").value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(direccion, telefono)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmbodegas")); //Capturar formulario que se enviara
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
            //document.querySelector("#frmbodegas").submit();
            if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
                try {
                    fetch("/SistemaCrio/Almacen/modificar", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data.Respuesta > 0) { //Si la actualizacion se realizo correctamente
                                tabla.destroy(); //Borrar tabla actual de almacenes
                                Pintartablaalmacen(); //Cargar nuevamente los registro de la tabla almacen
                                Update(data.Codigo, 1, data.Respuesta); //Mostrar sweetalert de almacen actualizado correctamente
                            }
                            else { //Si no se actualiza correctamente el almacen 
                                Update(null, null, data.Respuesta); //Mostrar sweetalert de que no se pudo actualizar el almacen
                            }

                        })
                } catch (error) { //Si ocurre un error en la solicitud mostrar el error
                    console.log("Ocurrio un error: " + error);
                }
            }
        });
    }
    else { //Si las variables estan vacias
        Validarfrm(); //Mostrar sweetalert que avisa que el formulario debe ser completado
    }
});


document.querySelector("#agregaralmacen").addEventListener("click", function () { //Evento si se clickea en agregar almacen
    Limpiarcomponentes(); //Pone de fabrica el formulario para registrar nuevos almacenes
});


addEvent(document, 'click', '#editarbodega', function (ev) {   //Cuando se presiona clic en una fila de la tabla editar
    document.querySelector('#txtDireccion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtTelefono').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById("Editarbodega").style.display = "block"; //Mostrar boton editar almacen
    document.getElementById("InsertarBodega").style.display = "none"; //Ocultar boton insertar registro
    document.getElementById("titleformus").textContent = "Editar almacén"; //Mostrar titulo de editar almacen
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    document.querySelector('#txtIdbodega').value = data[0]; //Capturar valor de la primera columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtDireccion').value = data[2]; //Capturar valor de la tercera columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtTelefono').value = data[3]; //Capturar valor de la cuarta columna de la fila seleccionada y setear el valor al input
    var estado = ev.target.parentNode.parentNode.children[5].textContent.toLowerCase().trim();  //Capturar valor de la sexta columna de la fila seleccionada y setear el valor al input
    if (estado === "disponible") { //Activa checkbox 
        document.getElementById("chkEstado").checked = true;
    }
    else {//Desactiva checkbox
        document.getElementById("chkEstado").checked = false;
    }

});

addEvent(document, 'click', '#eliminarbodega', function (ev) {   //Cuando se presiona clic en una fila de la tabla eliminar
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    id = data[0]; //Capturar valor de la primera columna de la fila seleccionada
    var cod = data[1]; //Capturar valor de la segunda columna de la fila seleccionada
    Swal.fire({ //Sweetalert si desea continuar eliminando el registro
        title: 'Estás seguro de eliminar a ' + cod + '?',
        text: "Al eliminar un registro no podrá recuperarlo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => { 
        if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
            var form = new FormData(); //Crear un nuevo formulario
            form.append('txtIdbodega', data[0]); //Crear una variable POST y asignarle el valor del id de la bodega que se eliminara
            try {
                fetch("/SistemaCrio/Almacen/eliminar", { //Indicar los parametros de la solicitud que se desea realizar 
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    body: form
                })
                    .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                    .then(data => { //Almacena el contenido de la respuesta en una variable
                        console.log(data);
                        let respuesta = data;
                        if (respuesta > 0) { //Si se elimina correctamente
                            tabla.destroy(); //Borrar tabla actual
                            Pintartablaalmacen(); //Recargar nuevamente tabla almacen
                            Delete(cod, respuesta); //Mostrar sweet alert que la eliminacion fue correcta
                        }
                        else { //Si no se elimina correctamente el almacen 
                            Delete(cod, respuesta); //Mostrar sweetalert de que no se pudo eliminar el almacen
                        }
                    })
            } catch (error) { //Si ocurre un error en la solicitud mostrar el error
                console.log("Ocurrio un error: " + error);
            }
        }
    });
});

addEvent(document, 'click', '#showproductos', function (ev) { //Cuando se da clic en mostrar productos
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    document.getElementById("titleformusproductos").textContent = "Bodega " + data[1]; //Mostrar el titulo de la bodega que se consultando los productos
    ListarProductobodegas(data[0]); //Llamar metodo que mostrara los productos de una bodega y mandarle como argumento el valor de la primera columna de fila selecciona que contiene el Id de la bodega

});

document.querySelector('#txtDireccion').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtDireccion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});

document.querySelector('#txtTelefono').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtTelefono').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});