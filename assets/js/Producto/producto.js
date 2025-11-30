let tabla = document.querySelector("#tablaproductos");  //Captura tabla productos
async function Pintartablaproductos() { //Metodo que cargara la informacion en la tabla productos
    try {
        let resp = await fetch("/SistemaCrio/Producto/listar", { //Variable que guarda los parametros de la solicitud que se desea realizar 
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodyproductos = document.querySelector('#bodyproductos'); //Seleccionar cuerpo de la tabla productos
        bodyproductos.innerHTML = ''; //Limpiar cuerpo de la tabla
        for (let item of datos.Productos["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            bodyproductos.innerHTML += `
            <tr>
                <td style='display: none;'>${item.Idproducto}</td>
                <td>${item.Cod_producto}</td>
                <td>${item.Nombre}</td>
                <td style='display: none;'>${item.Precio}</td>
                <td><strong id='categoriaid' style='display:none;'>${item.Idcategoria}</strong> ${item.Categoria}</td>
                <td style='display: none;'>${item.Existencias}</td>
                <td style='display: none;'>${item.Img}</td>
                <td style='display: none;'>${item.Descripcion}</td>
                <td style='display:none;'>${item.Usuario}</td>
                <td style='display: none;'>${item.Fecha_ingreso}</td>
                <td style='display: none;'>${item.Fecha_actualizacion}</td>
                <td>
                    <button class='btn btn-primary fa-solid fa-wrench' id='editarproducto' title='Editar' data-bs-toggle='modal' data-bs-target='#MdProductos'></button>
                    <button class='btn btn-danger fa-solid fa-trash' id='eliminarproducto' title='Eliminar'></button>     
                </td>
                
            </tr>
            ` //Rellenara la tabla con filas de registros obtenidos de la solicitud realizada
        }
        //Dar formato de datatable
        tabla = new DataTable("#tablaproductos", {
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
            dom: 'Bfrtip',
            "responsive": true
        });
    } catch (err) { //Si ocurre un error, mostrara el error
        console.log("Ocurrio un error: " + err);
    }
}
Pintartablaproductos(); //Ejecutar metodo que rellenara la tabla productos

async function CargaBodega() { //Carga bodegas en modal bodegasmodal
    try {
        let resp = await fetch("/SistemaCrio/Producto/listar", { //Recorrer la variable que almacena la respuesta de la solicitud 
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        //console.log(resp);
        let datos = await resp.json();  //Guardar en una variable la respuesta recibida de la solicitud
        let bodybodegasmodal = document.querySelector('#bodybodegasmodal'); //Seleccionar cuerpo del modal
        bodybodegasmodal.innerHTML = ''; //Limpiar cuerpo del modal
        for (let item of datos.Bodegas["0"]) { //Recorrer la variable que almacena la respuesta de la solicitud 
            if (item.Estado === "Activo") { //Mostrar bodegas que solo esten disponibles
                bodybodegasmodal.innerHTML += `
                <div class="row">
                    <div class="form-group form-floating col">
                        <input type="text" class="form-control" placeholder="Ingresar bodega." id="txtBodega" value="${item.Nombre}" readonly>
                        <label for="txtBodega" class="text-muted">&nbsp;&nbsp;&nbsp;Bodega:</label>
                        <input type="hidden" class="form-control" name="txtBodegaId[]" value="${item.Idbodega}">
                    </div>
                    <div class="form-group form-floating col">
                        <input type="text" class="form-control" name="txtExistencias[]" id="txtExis" placeholder="Ingresar existencias." oninput="soloNumeros(this)">
                        <label for="txtExis" class="text-muted">&nbsp;&nbsp;&nbsp;Existencias:</label>
                    </div>
                </div>
                <br>
                `
            } //Rellenara modal con filas de registros obtenidos de la solicitud realizada
        }
    } catch (err) { //Si ocurre un error, mostrara el error
        console.log("Ocurrio un error: " + err);
    }
}

async function CargaCategoria() { //Carga select Categorias
    try {
        let resp = await fetch("/SistemaCrio/Producto/listar", { //Recorrer la variable que almacena la respuesta de la solicitud 
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });
        //console.log(resp);
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodylistacategorias = document.querySelector('#listCategorias'); //Seleccionar cuerpo del select categorias
        bodylistacategorias.innerHTML = `<option value='' disabled selected >Seleccionar categoria</option>`; //Se borran todos los options anteriores y solo deja el predeterminado
        for (let item of datos.Categorias["0"]) { //Recorre array Categorias y llena el select con los options de las categorias que hay en la BD
            bodylistacategorias.innerHTML += `
            <option value='${item.Idcategoria}'>${item.Nombre}</option>
            `
        } //Rellenara select con filas de registros obtenidos de la solicitud realizada
    } catch (err) { //Si ocurre un error, mostrara el error
        console.log("Ocurrio un error: " + err);
    }
}
CargaCategoria();

async function CargaExistencias() { //Carga existencias de un producto en modal bodegasmodal
    try {
        var form = new FormData(document.querySelector("#frmproductos")); //Enviar formulario con el Id del producto que se consultara las existencias
        let resp = await fetch("/SistemaCrio/Producto/listar", {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: form
        });
        //console.log(resp);
        let datos = await resp.json(); //Guardar en una variable la respuesta recibida de la solicitud
        let bodybodegasmodal = document.querySelector('#bodybodegasmodal'); //Seleccionar cuerpo del modal
        bodybodegasmodal.innerHTML = ''; 
        for (let item of datos.Existencias["0"]) {
            if (item.Estado === "Activo") { //Validar el estado de la bodega si esta activa tendrá habilitado el input de existencias
                bodybodegasmodal.innerHTML += `
            <div class="row">
                <div class="form-group form-floating col">
                    <input type="text" class="form-control" placeholder="Ingresar bodega." id="txtBodega" value="${item.Nombre}" readonly>
                    <label for="txtBodega" class="text-muted">&nbsp;&nbsp;&nbsp;Bodega:</label>
                    <input type="hidden" class="form-control" name="txtBodegaId[]" value="${item.Idbodega}">
                </div>
                <div class="form-group form-floating col">
                    <input type="text" class="form-control" name="txtExistencias[]" id="txtExis" value="${item.Existencias}" placeholder="Ingresar existencias." oninput="soloNumeros(this)">
                    <label for="txtExis" class="text-muted">&nbsp;&nbsp;&nbsp;Existencias:</label>
                </div>
            </div>
            <br>
            `
            }
            else //Si se encuentra no disponible el input de existencias solo sera de lectura
            {
                bodybodegasmodal.innerHTML += `
            <div class="row">
                <div class="form-group form-floating col">
                    <input type="text" class="form-control" placeholder="Ingresar bodega." id="txtBodega" value="${item.Nombre}" readonly>
                    <label for="txtBodega" class="text-muted">&nbsp;&nbsp;&nbsp;Bodega:</label>
                    <input type="hidden" class="form-control" name="txtBodegaId[]" value="${item.Idbodega}">
                </div>
                <div class="form-group form-floating col">
                    <input type="text" class="form-control" name="txtExistencias[]" id="txtExis" value="${item.Existencias}" placeholder="Ingresar existencias." oninput="soloNumeros(this)" readonly>
                    <label for="txtExis" class="text-muted">&nbsp;&nbsp;&nbsp;Existencias:</label>
                </div>
            </div>
            <br>
            `
            } //Rellenara modal con filas de registros obtenidos de la solicitud realizada
        }
    } catch (err) { //Si ocurre un error, mostrara el error
        console.log("Ocurrio un error: " + err);
    }
}

function ValidarForm(imagen, nombre, categoria, precio, existencias, descripcion) { //Metodo que valida que los formularios este completos
    if (imagen == "" || nombre == "" || categoria == "" || precio == "" || existencias == "" || descripcion == "") { //Si una o más variables se encuentra vacia hacer:
        if (imagen.length == 0) { //Si la variable esta vacia hacer:
            document.getElementById("valimagen").textContent = "Por favor inserte una imagen."; //Mostrar mensaje
            document.getElementById("valimagen").classList.add("alert", "alert-danger"); //Mostrar alerta
        }
        else { //En caso que no este vacia
            document.getElementById("valimagen").textContent = ""; //Borrar mensaje
            document.getElementById("valimagen").classList.remove("alert", "alert-danger"); //Eliminar alerta
        }
        if (nombre.length == 0) { //Si la variable esta vacia hacer:
            document.getElementById("txtNombre").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.getElementById("txtNombre").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
            document.getElementById("valnombre").textContent = "Por favor, digite un nombre para el producto.";
        }
        else { //En caso que no este vacia
            document.getElementById("txtNombre").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.getElementById("txtNombre").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        if (categoria.length == 0) { //Si la variable esta vacia hacer:
            document.getElementById("listCategorias").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.getElementById("listCategorias").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.getElementById("listCategorias").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.getElementById("listCategorias").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        if (precio.length === 0 || precio === 0) { //Si la variable esta vacia hacer:
            document.getElementById("txtPrecio").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.getElementById("txtPrecio").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.getElementById("txtPrecio").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.getElementById("txtPrecio").classList.add("is-valid");
        }
        if (existencias.length == 0 || existencias == 0) { //Si la variable esta vacia hacer:
            document.getElementById("txtStock").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.getElementById("txtStock").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.getElementById("txtStock").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.getElementById("txtStock").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        if (descripcion.length == 0) { //Si la variable esta vacia hacer:
            document.getElementById("txtDescripcion").classList.remove("is-valid"); //Remover clase de boostrap is-valid del input
            document.getElementById("txtDescripcion").classList.add("is-invalid"); //Agregar clase de boostrap is-invalid del input
        }
        else { //En caso que no este vacia
            document.getElementById("txtDescripcion").classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
            document.getElementById("txtDescripcion").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        }
        return false; //Retornar false si los datos estan vacios
    }
    else { //Si todos los datos estan completos 
        document.getElementById("valimagen").textContent = ""; //Borrar mensaje de que hace falta una imagen
        document.getElementById("txtNombre").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.getElementById("listCategorias").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.getElementById("txtPrecio").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.getElementById("txtStock").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        document.getElementById("txtDescripcion").classList.add("is-valid"); //Agregar clase de boostrap is-valid del input
        return true; //Retornar true
    }
}

function Limpiarcomponentes() { //Método encargado de volver a su estado predeterminado el formulario 
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid del input
    document.querySelector('#listCategorias').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid del input
    document.querySelector('#txtPrecio').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid del input
    document.querySelector('#txtStock').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid del input
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid del input
    document.getElementById("EditarProducto").style.display = "none"; //Ocultar boton de editar producto
    document.getElementById("InsertarProducto").style.display = "block"; //Mostrar boton de insertar producto
    document.getElementById("titleformus").textContent = "Agregar nuevo producto"; //Mostar titulo de nuevo producto
    document.getElementById("imagen").value = ""; //Vaciar input file
    document.getElementById("choosen-image").setAttribute("src", ""); //Eliminar imagen insertada
    document.getElementById("file-name").textContent = ""; //Eliminar nombre de imagen insertada
    document.getElementById("txtNombre").value = ""; //Borra valor del input
    document.getElementById("txtPrecio").value = ""; //Borra valor del input
    document.getElementById("txtStock").value = ""; //Borra valor del input
    document.getElementById("txtDescripcion").value = ""; //Borra valor del input
    document.getElementById("valimagen").textContent = ""; //Borrar mensaje de imagen
    document.getElementById("valimagen").classList.remove("alert", "alert-danger"); //Remover alerta
    CargaCategoria(); //Cargar select de categorias
    CargaBodega(); //Cargar modal de bodegas
}


//Insertar registros
document.querySelector("#InsertarProducto").addEventListener('click', function (ev) { //Evento click cuando se de click en enviar formulario para insertar nuevo producto
    ev.preventDefault(); //Detener evento default del formulario
    var imagen = document.getElementById("file-name").textContent.trim(); //Capturar el valor del input sin espacios
    var nombre = document.getElementById("txtNombre").value.trim(); //Capturar el valor del input sin espacios
    var categoria = document.getElementById("listCategorias").value.trim(); //Capturar el valor del input sin espacios
    var precio = document.getElementById("txtPrecio").value.trim(); //Capturar el valor del input sin espacios
    var existencias = document.getElementById('txtStock').value.trim(); //Capturar el valor del input sin espacios
    var descripcion = document.getElementById('txtDescripcion').value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(imagen, nombre, categoria, precio, existencias, descripcion)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmproductos")); //Capturar formulario que se enviara
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
            if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
                try {
                    fetch("/SistemaCrio/Producto/agregar", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data.Validacion > 0) { //Si la inserccion se realizo correctamente
                                Limpiarcomponentes(); //Limpiar formulario
                                tabla.destroy(); //Borrar tabla actual de almacenes
                                Pintartablaproductos(); //Cargar nuevamente los registro de la tabla productos
                                Insert(data.Codigo, data.Validacion); //Mostrar sweetalert de producto registrado correctamente
                            }
                            else if (data.Validacion == -1) { //Si el nombre ingresado ya se encuentra en la BD
                                document.getElementById("txtNombre").classList.add("is-invalid"); //Indicar que el nombre ya esta en uso
                                document.getElementById("valnombre").textContent = "El nombre de este producto ya se encuentra registrado, por favor intente con otro."; //Mostrar mensaje
                            }
                            else { //Si hay un problema en la inserccion del producto mostrar sweet alert que no se pudo insertar el producto
                                Insert(null, data.Validacion);
                            }
                        })
                } catch (err) { //Si ocurre un error, mostrara el error
                    console.log("Ocurrio un error: " + err);
                }
            }
        });
    }
    else { //Si las variables estan vacias
        Validarfrm(); //Mostrar sweetalert que avisa que el formulario debe ser completado
    }
});

//Actualizar registro
document.querySelector("#EditarProducto").addEventListener('click', function (ev) { //Evento click cuando se de click en enviar formulario para actualizar un producto
    ev.preventDefault(); //Detener evento default del formulario
    var imagen = "default"; //valor predeterminado de una imagen
    var nombre = document.getElementById("txtNombre").value.trim(); //Capturar el valor del input sin espacios
    var categoria = document.getElementById("listCategorias").value.trim(); //Capturar el valor del input sin espacios
    var precio = document.getElementById("txtPrecio").value.trim(); //Capturar el valor del input sin espacios
    var existencias = document.getElementById('txtStock').value.trim(); //Capturar el valor del input sin espacios
    var descripcion = document.getElementById('txtDescripcion').value.trim(); //Capturar el valor del input sin espacios
    if (ValidarForm(imagen, nombre, categoria, precio, existencias, descripcion)) { //Validar variables si las variables se encuetran con datos hacer:
        var form = new FormData(document.querySelector("#frmproductos")); //Capturar formulario que se enviara
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
            //document.querySelector("#frmproductos").submit();
            if (result.isConfirmed) { //Si se presiona que si se desea continuar hacer:
                try { 
                    fetch("/SistemaCrio/Producto/modificar", { //Indicar los parametros de la solicitud que se desea realizar 
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: form
                    })
                        .then(res => res.json()) //Guardar la respuesta recibida de la solicitud 
                        .then(data => { //Almacena el contenido de la respuesta en una variable
                            console.log(data);
                            if (data.Validacion > 0) { //Si la actualizacion se realizo correctamente
                                tabla.destroy(); //Borrar tabla actual de productos
                                Pintartablaproductos(); //Cargar nuevamente los registro de la tabla productos
                                Update(data.Codigo, 1, data.Validacion); //Mostrar mensaje producto actualizado correctamente
                            }
                            else if (data.Validacion == -1) { //Si el nombre ingresado ya se encuentra en la BD
                                document.getElementById("txtNombre").classList.add("is-invalid"); //Indicar que el nombre ya esta en uso
                                document.getElementById("valnombre").textContent = "El nombre de este producto ya se encuentra registrado, por favor intente con otro."; //Mostrar mensaje
                            }
                            else { //Si hay un problema en la inserccion del producto mostrar sweet alert que no se pudo insertar el producto
                                Update(null, null, data.Validacion);
                            }
                        })
                } catch (error) {  //Si ocurre un error, mostrara el error
                    console.log("Ocurrio un error: " + error);
                }
            }
        });
    }
    else {
        Validarfrm();
    }
})


//Eventos
addEvent(document, 'click', '#agregarproducto', function () {   //Cuando se presiona clic en el boton Agregar producto
    Limpiarcomponentes(); //Volver al estado predeterminado el formulario
});

addEvent(document, 'click', '#editarproducto', function (ev) {   //Cuando se presiona clic en el boton Editar producto
    document.getElementById("valimagen").textContent = ""; //Borrar mensaje de imagen
    document.getElementById("valimagen").classList.remove("alert", "alert-danger"); //Quitar alerta de imagen
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid y is-valid
    document.querySelector('#listCategorias').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid y is-valid
    document.querySelector('#txtPrecio').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid y is-valid
    document.querySelector('#txtStock').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid y is-valid
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid y is-valid
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    var row = ev.target.parentNode.parentNode; //Captura el elemento tr seleccionado
    selectrow(data, row); //Llama funcion que agregara color a la fila seleccionada y mostrara la informacion de la fila seleccionada en un card
    document.getElementById("imagen").value = ""; //Vaciar input file
    document.getElementById("txtIdproducto").value = data[0]; //Capturar valor de la primera columna de la fila seleccionada y setear el valor al input
    document.getElementById("txtCodProducto").value = data[1]; //Capturar valor de la segunda columna de la fila seleccionada y setear el valor al input
    CargaExistencias(); //Cargar modal con las existencias de los productos
    document.querySelector("#txtStock").value = data[5]; //Capturar valor de la sexta columna de la fila seleccionada y setear el valor al input
    document.getElementById("InsertarProducto").style.display = "none"; //Ocultar boton de insertar producto
    document.getElementById("EditarProducto").style.display = "block"; //Mostrar boton de editar producto
    document.getElementById("titleformus").textContent = "Editar Producto"; //Mostrar mensaje de editar producto
    var urlimagen = "http://" + window.location.host + "/SistemaCrio/assets/img/Productos/" + data[1] + "/" + data[6]; //Buscar la imagen del producto en el directorio con el primer valor y septimo valor de la columna de la fila seleccionada
    chosenImage.setAttribute("src", urlimagen); //Mostrar la imagen
    fileName.textContent = data[6]; //Capturar valor de la septima columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtNombre').value = data[2]; //Capturar valor de la tercera columna de la fila seleccionada y setear el valor al input
    document.querySelector('#txtPrecio').value = data[3]; //Capturar valor de la cuarta columna de la fila seleccionada y setear el valor al input
    var categoria = data[4].substring(47, 48); //Extraer el id de la categoria de la columna quinta de la fila seleccionada
    document.querySelector('#listCategorias').value = categoria; //Cargar categoria del producto
    document.querySelector('#txtDescripcion').value = data[7]; //Capturar valor de la octava columna de la fila seleccionada y setear el valor al input
});

addEvent(document, 'click', '#eliminarproducto', function (ev) { //Cuando se presiona clic en una fila de la tabla eliminar
    var data = tabla.row(ev.target.parentNode.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    var row = ev.target.parentNode.parentNode; //Captura el elemento tr seleccionado
    selectrow(data, row); //Llama funcion que agregara color a la fila seleccionada y mostrara la informacion de la fila seleccionada en un card
    id = data[0]; //Captura valor de la primera columna de la fila seleccionada
    var cod = data[1]; //Captura valor de la segunda columna de la fila seleccionada
    nombre = data[2]; //Captura valor de la tercera columna de la fila seleccionada
    Swal.fire({ //Sweetalert si desea continuar eliminando el registro
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
            var form = new FormData();//Crear un nuevo formulario
            form.append('txtIdproducto', data[0]); //Crear una variable POST y asignarle el valor del id del producto que se eliminara
            form.append('txtCodProducto', data[1]); //Crear una variable POST y asignarle el valor del codigo del producto que se eliminara
            try {
                fetch("/SistemaCrio/Producto/eliminar", { //Indicar los parametros de la solicitud que se desea realizar 
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
                            Pintartablaproductos(); //Recargar nuevamente tabla productos
                            Delete(cod, respuesta); //Mostrar sweet alert que la eliminacion fue correcta
                        }
                        else { //Si no se elimina correctamente el almacen 
                            Delete(cod, respuesta); //Mostrar sweetalert de que no se pudo eliminar el producto
                        }
                    })
            } catch (error) { //Si ocurre un error en la solicitud mostrar el error
                console.log("Ocurrio un error: " + error);
            }
        }
    });
});


document.querySelector("#txtStock").addEventListener("click", function () {  //Carga modal de existencias
    var ventanaexistencias = new bootstrap.Modal(document.getElementById("MdExistencias")); //Instancia de un nuevo modal Boostrap
    ventanaexistencias.show(); //Mostrar modal
});

document.querySelector('#txtNombre').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtNombre').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
});
document.querySelector('#listCategorias').addEventListener("change", function () { //Cuando se cambia la opcion del select
    document.querySelector('#listCategorias').classList.remove("is-invalid"); //Remover clase de boostrap is-invalid del input
});
document.querySelector('#txtPrecio').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtPrecio').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid del input
});
document.querySelector("#btnexistencias").addEventListener("click", function () {  //cuando se da clic en agregar en el modal de existencias, sumara todos los inputs de existencias
    var existencias = document.getElementsByName("txtExistencias[]"); //Seleccionar inputs de existencias
    var suma = 0; //declara variable de suma
    for (var i = 0; i < existencias.length; i++) { //Recorrer todos los inputs de existencias
        if (existencias[i].value > 0) { //Validar que tengan un valor mayor que cero para proceder a sumar
            suma = parseFloat(suma) + parseFloat(existencias[i].value);
        }
    }
    if (suma > 0) { //Si se ingresan existencias
        document.querySelector("#txtStock").value = suma; //Asignar valor de suma a input
        document.querySelector('#txtStock').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-valid y is-invalid del input
    }
    else { //Sino se ingresan asignar valor vacio
        document.querySelector("#txtStock").value = "";
    }
});
document.querySelector('#txtDescripcion').addEventListener('keyup', function () { //Cuando se escribe en un input
    document.querySelector('#txtDescripcion').classList.remove("is-invalid", "is-valid"); //Remover clase de boostrap is-invalid del input
});

/*INPUT FILE*/
let uploadButton = document.getElementById("imagen"); //Seleccionar input file
let chosenImage = document.getElementById("choosen-image"); //Seleccionar donde se visualizara la imagen
let fileName = document.getElementById("file-name"); //Seleccionar donde se cargara el nombre de la imagen

uploadButton.onchange = () => { //Si se ingresa una imagen hacer:
    let reader = new FileReader(); //Instanciar clase
    reader.readAsDataURL(uploadButton.files[0]); //Capturar ubicacion de la imagen
    reader.onload = () => {
        chosenImage.setAttribute("src", reader.result); //Cargar la imagen
    }
    fileName.textContent = uploadButton.files[0].name; //Mostrar el nombre de la imagen
    document.getElementById("valimagen").textContent = ""; //Eliminar mensaje de la imagen
    document.getElementById("valimagen").classList.remove("alert", "alert-danger"); //Eliminar alerta
}


document.querySelector('#bodyproductos').addEventListener('click', function (e) { //evento click cuando se cliquea el tbody
    var data = tabla.row(e.target.parentNode).data(); // Recolecta la informacion de la fila seleccionada
    var row = e.target.parentNode; //Captura el elemento tr seleccionado
    selectrow(data, row); //Llama funcion que agregara color a la fila seleccionada y mostrara la informacion de la fila seleccionada en un card
});


function selectrow(data, row) {  //Funcion que agregara color a la fila seleccionada y mostrara la informacion de la fila seleccionada en un card
    if (row.style.backgroundColor == "red") { //Compara si tr tiene el backgroun rojo si lo tiene elimina el background y el color de fuente
        row.style.backgroundColor = null;
        row.style.color = null;
        document.querySelector("#cardprod").style.display = "none";
    }
    else { //Si el tr seleccionado no tiene el background rojo y color de fuente blanco
        var rows = document.querySelectorAll('#bodyproductos tr'); //Captura todas las filas del tbody
        for (var i = 0; i < rows.length; i++) { //Recorre todos los tr del tbody y les elimina el background rojo y el color de fuente
            rows[i].style.backgroundColor = null;
            rows[i].style.color = null;
            rows[i].children[11].style.backgroundColor = null;
            rows[i].children[11].style.color = null;
        }
        row.style.backgroundColor = "red"; //Agrega background rojo a la fila seleccionada
        row.style.color = "white"; //Agrega color de fuente blanco a la fila seleccionada

        //Card
        document.querySelector("#cardprod").style.display = "block"; //Mostrar card
        var urlimagen = "http://" + window.location.host + "/SistemaCrio/assets/img/Productos/" + data[1] + "/" + data[6]; //Cargar imagen del producto
        var bodycardproductos = document.querySelector("#cardprod"); //Seleccionar cuerpo del card
        bodycardproductos.innerHTML = ""; //Limpiar card
        bodycardproductos.innerHTML += `
            <div class="card" style="width: 21rem;">
                <div class='row m-2'>
                    <div class="col-auto"><strong class="text-muted">Creador: </strong> ${data[8]}</div>
                    <div class="col-auto"><strong class="text-muted">Agregado: </strong> ${data[9]}</div>
                </div>
                <br>
                <img src="${urlimagen}" class="card-img-top" width="300" height="300" alt="...">
                <div class="card-body" id="cardlistaproductos">
                    <h5 class="card-title fw-bold">${data[2]}</h5>
                    <p class="card-text">${data[7]}</p>
                    <h5 class="fw-bold">Precio: $${data[3]}</h5>
                    <div class='row'>
                        <div class="col-auto"><p class="text-muted">Modificado ultima vez:</p> ${data[10]}</div>
                        <div class="col-auto"><p class="text-muted">Stock:</p> ${data[5]}</</div>
                    </div>
                </div>
            </div>
            ` //Mostrar en el card toda la informacion del producto seleccionado
    }
}