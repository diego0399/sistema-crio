let tabla = document.querySelector("#tablacategorias");  //Selecciona tabla de categorias
async function Pintartablacategoria() { //Metodo que cargara la informacion en la tabla categoria
    try {
        let resp = await fetch("/SistemaCrio/Producto/listar", { //Variable que guarda los parametros de la solicitud que se desea realizar 
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodycategorias = document.querySelector('#bodycategoria'); //Seleccionar cuerpo de la tabla categorias
        bodycategorias.innerHTML = ''; //Limpiar cuerpo de la tabla
        for (let item of datos.Categorias["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            var productos = (item.Productos > 0) ? "<button type='button' id='showproductos' class='btn btn-secondary' title='Ver productos' data-bs-toggle='modal' data-bs-target='#MdProductos'> <i class='fa-solid fa-eye'></i> <strong>Ver</strong></button> " + item.Productos : item.Productos; //Validar productos
            var acciones = (item.Productos == 0) ? "<button class='btn btn-primary fa-solid fa-wrench' id='editarcategoria' title='Editar' data-bs-toggle='modal' data-bs-target='#MdCategoria'></button><button class='btn btn-danger fa-solid fa-trash' id='eliminarcategoria' title='Eliminar'></button>" : ""; //Validar productos
            bodycategorias.innerHTML += `
            <tr>
                <td>${item.Idcategoria}</td>
                <td>${item.Nombre}</td>
                <td>${item.Descripcion}</td>
                <td>${productos}</td>
                <td>${acciones}</td>
                
            </tr>
            `
        } //Rellenara la tabla con filas de registros obtenidos de la solicitud realizada
        //Dar formato de datatable
        tabla = new DataTable("#tablacategorias", {
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
            dom: 'Bfrtip',
            "responsive": true
        });
    } catch (err) { //Si ocurre un error, mostrara el error 
        console.log("Ocurrio un error: " + err);
    }
}
Pintartablacategoria(); //Ejecutar metodo que rellenara la tabla categoria
async function ListarProductocategorias(id) { //Metodo encargado de listar los productos de una categoria
    try {
        var form = new FormData(); //Crear formulario
        form.append('txtIdcategoria', id); //Crear variable POST y asignarle el id de la categoria que se consultara
        let resp = await fetch("/SistemaCrio/Producto/listar", { //Variable que guarda los parametros de la solicitud que se desea realizar 
            method: 'POST', //Tipo de envio de datos
            mode: 'cors',
            cache: 'no-cache',
            body: form //Datos a enviar
        });
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        console.log(datos.Categoriaproductos["0"]);
        let bodycarrusel = document.querySelector('#bodycarrusel'); //Seleccionar cuerpo del carrusel que contendra los productos de la categoria
        bodycarrusel.innerHTML = ''; //Limpiar cuerpo del carrusel
        for (let item of datos.Categoriaproductos["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            if (item.Idbodega == -1) { //Validar si no se encuentran datos
                bodycarrusel.innerHTML = `
                <h4 class='text-center'>No se encontraron datos!</h4>
            `
            } //Si se encuetra informacion se creara una card por producto que contendra la informacion del producto
            else {
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

    } catch (err) {
        console.log("Ocurrio un error: " + err);
    }
}


function ValidarForm(nombre, descripcion) { //Metodo que valida que los formularios este completos
    if (nombre == "" || descripcion == "") { //Si una o más variables se encuentra vacia hacer:
        if (nombre.length == 0) { //Si la variable esta vacia hacer:
            document.querySelector("#txtNombre").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtNombre").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        } else { //En caso que no este vacia
            document.querySelector("#txtNombre").classList.remove("is-invalid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtNombre").classList.add("is-valid"); //Agregar clase de boostrap is-invalid del input
        }
        if (descripcion.length == 0) { //Si la variable esta vacia hacer:
            document.querySelector("#txtDescripcion").classList.remove("is-valid") //Remover clase de boostrap is-valid del input;
            document.querySelector("#txtDescripcion").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.querySelector("#txtDescripcion").classList.remove("is-invalid"); //Remover clase de boostrap is-valid del input
            document.querySelector("#txtDescripcion").classList.add("is-valid"); //Agregar clase de boostrap is-invalid del input
        }
        return false; //Retornar false si los datos estan vacios
    }
    else { //Si todos los datos estan completos 
        document.querySelector("#txtNombre").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.querySelector("#txtDescripcion").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        return true; //Retornar true
    }
}

function Limpiarcomponentes() { //Método encargado de borrar todo lo del formulario
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById("Editarcategoria").style.display = "none"; //Ocultar boton de edita categoria
    document.getElementById("Insertarcategoria").style.display = "block"; //Mostrar boton de insertar categoria
    document.getElementById("titleformus").textContent = "Agregar nueva categoria"; //Mostrar titulo de nueva categoria
    document.querySelector("#txtNombre").value = "";  //Borra valor del input
    document.querySelector("#txtDescripcion").value = ""; //Borra valor del input
    document.querySelector("#valnombre").textContent = "Por favor, digite la categoria."; //Asignar por default este mensaje de validacion
}

document.querySelector("#Insertarcategoria").addEventListener("click", function (ev) { //Evento click cuando se de click en enviar formulario para insertar nueva categoria
    ev.preventDefault(); //Detener evento default del formulario
    var nombre = document.querySelector("#txtNombre").value.trim(); //Capturar el valor del input sin espacios
    var descripcion = document.querySelector("#txtDescripcion").value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(nombre, descripcion)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmcategorias")); //Capturar formulario que se enviara
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
                    fetch("/SistemaCrio/Producto/agregarcategoria", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data > 0) { //Si la inserccion se realizo correctamente
                                Limpiarcomponentes(); //Limpiar formulario
                                tabla.destroy(); //Borrar tabla actual de categorias
                                Pintartablacategoria(); //Cargar nuevamente los registro de la tabla categoria
                                Insert(nombre, data); //Mostrar sweetalert de categoria registrada correctamente
                            }
                            else if (data == -1) { //Si el nombre de la categoria ya se encuentra en la BD
                                document.querySelector("#txtNombre").classList.add("is-invalid"); //Indicar que ya se encuentra registrado ese nombre
                                document.querySelector("#valnombre").textContent = "Por favor intente con otro nombre, este ya se encuentra registrado."; //Mostrar mensaje
                            }
                            else { //Si ocurre un problema en insertar el registro mostrar sweet alert que no se pudo insertar el registro
                                Insert(null, data);
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

document.querySelector("#Editarcategoria").addEventListener("click", function (ev) { //Evento click cuando se de click en enviar formulario para actualizar una categoria
    ev.preventDefault(); //Detener evento default del formulario
    var nombre = document.querySelector("#txtNombre").value.trim(); //Capturar el valor del input sin espacios
    var descripcion = document.querySelector("#txtDescripcion").value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(nombre, descripcion)) { //Si se presiona que si se desea continuar hacer:
        var form = new FormData(document.querySelector("#frmcategorias")) //Capturar formulario que se enviara;
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
                    fetch("/SistemaCrio/Producto/modificarcategoria", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data > 0) { //Si la actualizacion se realizo correctamente
                                tabla.destroy(); //Borrar tabla actual de almacenes
                                Pintartablacategoria(); //Cargar nuevamente los registro de la tabla categoria
                                Update(nombre, 1, data); //Mostrar sweetalert de categoria actualizada correctamente
                            }
                            else if (data == -1) { //Si el nombre de la categoria ya se encuentra en la BD
                                document.querySelector("#txtNombre").classList.add("is-invalid"); //Indicar que ya se encuentra registrado ese nombre
                                document.querySelector("#valnombre").textContent = "Por favor intente con otro nombre, este ya se encuentra registrado."; //Mostrar mensaje
                            }
                            else { //Si ocurre un problema en actualizar el registro mostrar sweet alert que no se pudo actualizar el registro
                                Update(null, null, data);
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


document.querySelector("#agregarcategoria").addEventListener("click", Limpiarcomponentes); //Evento si se clickea en agregar categoria

addEvent(document, 'click', '#editarcategoria', function (ev) {   //Evento si se clickea en editar categoria
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    document.getElementById("Editarcategoria").style.display = "block"; //Mostrar boton editar ctegoria
    document.getElementById("Insertarcategoria").style.display = "none"; //Ocultar boton de insertar categoria
    document.getElementById("titleformus").textContent = "Editar categoria"; //Mostrar titulo de editar categoria
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    document.querySelector('#txtIdcategoria').value = data[0]; //Capturar valor de la primera columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtNombre').value = data[1]; //Capturar valor de la segunda columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtDescripcion').value = data[2]; //Capturar valor de la tercera columna de la fila seleccionada y setear el valor al input
});


addEvent(document, 'click', '#eliminarcategoria', function (ev) { //Cuando se presiona clic en una fila de la tabla eliminar
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    id = data[0]; //Capturar valor de la primera columna de la fila seleccionada
    var nombre = data[1]; //Capturar valor de la segunda columna de la fila seleccionada
    Swal.fire({ //Sweetañert si desea continuar eliminando el registro
        title: 'Estás seguro de eliminar a ' + nombre + '?',
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
            form.append('txtIdcategoria', data[0]); //Crear una variable POST y asignarle el valor del id de la bodega que se eliminara
            try {
                fetch("/SistemaCrio/Producto/eliminarcategoria", { //Indicar los parametros de la solicitud que se desea realizar 
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
                            Pintartablacategoria(); //Recargar nuevamente tabla categoria
                            Delete(nombre, respuesta); //Mostrar sweet alert que la eliminacion fue correcta
                        }
                        else { //Si no se elimina correctamente el almacen 
                            Delete(nombre, respuesta); //Mostrar sweetalert de que no se pudo eliminar el almacen
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
    document.getElementById("titleformusproductos").textContent = "Categoria " + data[1]; //Mostrar el titulo de la categoria que se consultando los productos
    ListarProductocategorias(data[0]); //Llamar metodo que mostrara los productos de una categoria y mandarle como argumento el valor de la primera columna de fila selecciona que contiene el Id de la categoria
});

document.querySelector('#txtNombre').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});

document.querySelector('#txtDescripcion').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});