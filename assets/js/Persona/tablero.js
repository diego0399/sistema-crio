async function ListadoDatosgenerales() { //Metodo que cargara la informacion de datos generales
    let datos = await ajax(1); //Tipo de opcion de solicitud en Controlador Persona y listara productos por categoria
    let bodyproductosporcategoria = document.querySelector('#productosporcategoria'); //Seleccionar componente productosporcategoria
    bodyproductosporcategoria.innerHTML = ''; //Limpiar el contenido de productosporcategoria
    for (let item of datos.Datosgenerales) { //Recorrer la variable que almacena la respuesta de la solicitud 
        document.querySelector("#productosactivos").textContent = item.Cantidad; //Asignar cantidad de productos al componente productosactivos
        bodyproductosporcategoria.innerHTML += `
        <div class="col">
            <h6>${item.Nombre}</h6>
            <h6 class="text-success"><i class="icon ion-md-arrow-dropup-circle"></i>${item.Productos}</h6>                                   
        </div>
         `; //Mostrar nombre de la categoria y el porcentaje de productos que tiene esa categoria
    }
    datos = await ajax(2); //Tipo de opcion de solicitud en Controlador Persona y listara los datos generales del sistema
    console.log(datos);
    for (let item of datos.Datosgenerales) { //Recorrer la variable que almacena la respuesta de la solicitud 
        document.querySelector("#bodegasactivas").textContent = item.Bodegas; //Asignar cantidad de bodegas activas al componente bodegas activas
        document.querySelector("#bodegasactivasporcentaje").textContent = item.Porcentajebodegas; //Asignar porcentaje de bodegas activas al componente bodegasactivasporcentaje
        document.querySelector("#usuariosactivos").textContent = item.Usuarios; //Asignar cantidad de usuarios activos al componente usuarioactivos
        document.querySelector("#usuariosactivosporcentaje").textContent = item.Porcentajeusuarios; //Asignar porcentaje de bodegas activas al componente usuariosactivosporcentaje
        document.querySelector("#usuariosnuevos").textContent = item.Ultimosusuarios; //Asignar cantidad de usuarios nuevos al componente bodegas usuariosnuevos
        document.querySelector("#usuariosnuevosporcentaje").textContent = item.Ultimosusuariosporcentaje; //Asignar porcentaje de usuarios nuevos al componente usuariosnuevosporcentaje
    }
}
ListadoDatosgenerales(); //Llamar funcion

async function ajax(opcion) { //Tipo de solicitud en el Controlador Persona
    try {
        var form = new FormData();//Crear formulario
        form.append('txtOpcion', opcion); //Crear variable POST y enviar de valor el parametro de opcion que se desea listar
        let resp = await fetch("/SistemaCrio/Persona/listar", { //Parametros de la solicitud
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: form
        });
        return await resp.json(); //Retornar resultado de la solicitud
    } catch (error) { //Si ocurre un error en la solicitud mostrarlo
        console.log("Ocurrio un error: " + error);
    }
}
async function ajax2() { //Tipo de solicitud en el Controlador Almacen
    try {
        let resp = await fetch("/SistemaCrio/Almacen/listar", { //Parametros de la solicitud
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        return await resp.json(); //Retornar resultado de la solicitud
    } catch (error) { //Si ocurre un error en la solicitud mostrarlo
        console.log("Ocurrio un error: " + error);
    }
}

async function graficobodegas() { //Funcion que creara un gráfico de barras con la cantidad de productos en cada bodega
    let datos = await ajax2(); //Tipo de solicitud en el Controlador Almacen
    new Chart( //Crear una instancia de la clase Chart de la libreria Chart js
        document.getElementById('myChart'), { //Seleccionar componente html myChart que contendra la gráfica y definir los siguientes parametros
        type: 'bar', //Tipo de gráfico de barras
        data: { //Formato del gráfico
            labels: datos.Bodegas["0"].map(row => row.Nombre), //Ejex X categorías del gráfico
            datasets: [{
                label: 'Productos por bodega', //Leyenda del gráfico
                data: datos.Bodegas["0"].map(row => row.Productos), //Eje Y Serie de datos de la gráfica
                backgroundColor: [ //Color del gráfico
                    '#111B54',
                ]
            }]
        }
    }
    );
}

graficobodegas(); //Llamar método que genera el gráfico