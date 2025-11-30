window.addEventListener("click", () => { //Evento cuando se da clic en cualquier parte del sitio
    document.querySelector("#myList").style.display = "none"; //Ocultar resultado de busquedad
});

document.addEventListener("keyup", e=>{ //Evento cuando se escribe
    if (e.target.matches("#myInput")){ //Capturar input search
  
        if (e.key ==="Escape")e.target.value = "" //Si se presiona barra espaciadora asignar valor vacio
  
        document.querySelectorAll(".articulo").forEach(opciones =>{ //Seleccionar los elementos li 
            
            opciones.textContent.toLowerCase().includes(e.target.value.toLowerCase())
              ?opciones.classList.remove("filtro") //Buscar el dato escrito en los elementos li si se encuentra mostrarlo
              :opciones.classList.add("filtro"),document.querySelector("#myList").style.display = 'block';
        })
  
    }
});
(function(){ //Funcion que muestra el reloj y la fecha del sistema
    var actualizarHora = function(){
        var fecha = new Date(),
            horas = fecha.getHours(),
            ampm,
            minutos = fecha.getMinutes(),
            segundos = fecha.getSeconds(),
            diaSemana = fecha.getDay(),
            dia = fecha.getDate(),
            mes = fecha.getMonth(),
            year = fecha.getFullYear();

        var sHoras = document.getElementById('horas'),
            sAMPM = document.getElementById('ampm'),
            sMinutos = document.getElementById('minutos'),
            sSegundos = document.getElementById('segundos'),
            sDiaSemana = document.getElementById('diaSemana'),
            sDia = document.getElementById('dia'),
            sMes = document.getElementById('mes'),
            sYear = document.getElementById('year');

        var semana = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
        sDiaSemana.textContent = semana[diaSemana];
        sDia.textContent=dia;
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        sMes.textContent=meses[mes];
        sYear.textContent=year;

        if(horas>=12){
            horas = horas-12;
            ampm = 'PM';
        }
        else{
            ampm = 'AM';
        }

        if(horas==0){
            horas=12;
        }

        if(minutos<10)
        {
            minutos = "0" + minutos;
        }
        
        if(segundos<10)
        {
            segundos= "0" + segundos;
        }
    
        sHoras.textContent=horas;
        sAMPM.textContent=ampm;
        sMinutos.textContent=minutos;
        sSegundos.textContent=segundos;
    };
    actualizarHora();
    var intervalo = setInterval(actualizarHora,1000);
}())
