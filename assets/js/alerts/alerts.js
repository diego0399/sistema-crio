function Validarfrm(){
	Swal.fire({ //Sweet Alert que pedirá completar la información requerida en el formulario.
            title: "<h3 style='color:black'>¡Advertencia!</h3>",
            text: "Debe completar el formulario.",
            icon: 'info',
            confirmButtonColor: '#37C8F2',
            confirmButtonText: "Aceptar",
	});
}
function UserInactivo(){
    Swal.fire({ //Sweet Alert que mostrara mensaje que el usuario esta desactivado
        title: "<h3 style='color:black'>¡Error de Ingreso!</h3>",
        text: "El usuario ingresado ha sido desactivado.",
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: "OK",
    });
}

function Errorvalidarlogueo(){ //Error de logueo
    Swal.fire({
        title: "<h3 style='color:black'>¡Error!</h3>",
        text: "Ocurrio un error.",
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: "Aceptar",
    });
}

function Logueo(){ //Logueo exitoso
    Swal.fire({icon: 'success',title: 'Inicio de sesión exitoso!',showConfirmButton: false,timer: 1600});
}

function Insert(cod,resp) { //Mensaje de insert
    if(resp>0){
        Swal.fire('Registro '+cod+' insertado correctamente!','Presione aceptar!','success');
    }
    else{
        Swal.fire('Ocurrio un error al insertar el registro!','Presione aceptar!','error');
    }
}

function Update(cod,op,resp) { //Mensaje de update
    if(resp>0){
        if(op==1){
            Swal.fire('Registro '+cod+' actualizado correctamente!','Presione aceptar!','success');
        }
        else{
            Swal.fire('Perfil actualizado correctamente!','Presione aceptar!','success');
        }
    }else{
        Swal.fire('Ocurrio un error al actualizar el registro!','Presione aceptar!','error');
    }
}

function Delete(cod,resp) { //Mensaje de delete
    if(resp>0){
        Swal.fire('Registro '+cod+' eliminado correctamente!','Presione aceptar!','success');
    }
    else{
        Swal.fire('Ocurrio un error al eliminar el registro!','Presione aceptar!','error');
    }
  
}

