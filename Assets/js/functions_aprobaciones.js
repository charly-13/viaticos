function openModal(idviatico) {

    console.log(userData.email_usuario);
    //document.querySelector("#formViaticoAprobacionuno").reset();
    document.querySelector('#idviatico').value = idviatico;
    document.querySelector('#correo_solicitante').value = userData.email_usuario;
    document.querySelector('#email_jefe_superior').value = userData.email_jefe_superior;
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Enviar decisión";

    document.querySelector('#titleModal').innerHTML = "Gestionar Solicitud de Viáticos";
    $('#modalFormValidacionuno').modal('show');

}
//IMPLEMENTAMOS FUNCIÓN PARA LA APROBACIÓN POR PARTE DEL JEFE DIRECTO
let formViaticoAprobacionuno = document.querySelector("#formViaticoAprobacionuno");
formViaticoAprobacionuno.onsubmit = function (e) {
    e.preventDefault();
    let strEstado = document.querySelector('#listStatus').value;
    let strDescripcion = document.getElementById('txtComentarios').value.trim();


    if (strEstado == '0' || !strDescripcion) {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Viaticosgenerales/respuestaPorJefatura';

    let formData = new FormData(formViaticoAprobacionuno);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            let objData = JSON.parse(request.responseText);
            if (objData.status) {

                //tableViaticos.api().ajax.reload();
                $('#modalFormValidacionuno').modal("hide");
                formViaticoAprobacionuno.reset();
                //swal("Viáticos", objData.msg, "success");
                swal({
                    title: "Viáticos",
                    text: objData.msg,
                    type: "success"
                }, function () {
                    window.location.href = base_url + '/Viaticosgenerales';
                });


            } else {
                swal("Error", objData.msg, "error");
            }
        }
        divLoading.style.display = "none";
        return false;
    }
}



function openModalValidatejefeSuperior(idviatico) {
   //document.querySelector("#formViaticoAprobacionJefaturaSuperior").reset();
    document.querySelector('#idviaticoap').value = idviatico;
    document.querySelector('#correo_solicitante_aprob_final').value = userData.email_usuario;
    //document.querySelector('#email_jefe_superior').value =userData.email_jefe_superior;
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Enviar decisión";
     
    document.querySelector('#titleModalC').innerHTML = "Gestionar Solicitud de Viáticos";
    $('#modalFormValidacionjefaturaSuperior').modal('show');

}


//IMPLEMENTAMOS FUNCIÓN PARA LA APROBACIÓN POR PARTE DEL JEFE SUPERIOR
let formViaticoAprobacionJefaturaSuperior = document.querySelector("#formViaticoAprobacionJefaturaSuperior");
formViaticoAprobacionJefaturaSuperior.onsubmit = function (e) {
    e.preventDefault();
    let strEstadoap = document.querySelector('#listStatusap').value;
    let strDescripcionap = document.getElementById('txtComentariosap').value.trim();


    if (strEstadoap == '0' || !strDescripcionap) {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Viaticosgenerales/respuestaPorJefaturaSuperior';

    let formData = new FormData(formViaticoAprobacionJefaturaSuperior);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            let objData = JSON.parse(request.responseText);
            if (objData.status) {

                //tableViaticos.api().ajax.reload();
                $('#modalFormValidacionjefaturaSuperior').modal("hide");
                formViaticoAprobacionJefaturaSuperior.reset();
                //swal("Viáticos", objData.msg, "success");
                swal({
                    title: "Viáticos",
                    text: objData.msg,
                    type: "success"
                }, function () {
                    window.location.href = base_url + '/Viaticosgenerales';
                });


            } else {
                swal("Error", objData.msg, "error");
            }
        }
        divLoading.style.display = "none";
        return false;
    }
}


function openModalValidateCompras(idviatico) {
   //document.querySelector("#formViaticoAprobacionJefaturaSuperior").reset();
    document.querySelector('#idviatico_comp').value = idviatico;
    document.querySelector('#correo_solicitante_comp').value = userData.email_usuario;
    //document.querySelector('#email_jefe_superior').value =userData.email_jefe_superior;
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Enviar decisión";
     
    document.querySelector('#titleModalCompras').innerHTML = "Finalizar Solicitud";
    $('#modalFormValidacionCompras').modal('show');

}



//IMPLEMENTAMOS FUNCIÓN PARA LA APROBACIÓN POR PARTE DEL JEFE SUPERIOR
let formViaticoAprobacionCompras = document.querySelector("#formViaticoAprobacionCompras");
formViaticoAprobacionCompras.onsubmit = function (e) {
    e.preventDefault();
    let strEstadoap = document.querySelector('#listStatus_comp').value;
    let strDescripcionap = document.getElementById('txtComentarios_comp').value.trim();


    if (strEstadoap == '0') {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Viaticosgenerales/respuestaPorCompras';

    let formData = new FormData(formViaticoAprobacionCompras);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            let objData = JSON.parse(request.responseText);
            if (objData.status) {

                //tableViaticos.api().ajax.reload();
                $('#modalFormValidacionCompras').modal("hide");
                formViaticoAprobacionCompras.reset();
                //swal("Viáticos", objData.msg, "success");
                swal({
                    title: "Viáticos",
                    text: objData.msg,
                    type: "success"
                }, function () {
                    window.location.href = base_url + '/Viaticosgenerales';
                });


            } else {
                swal("Error", objData.msg, "error");
            }
        }
        divLoading.style.display = "none";
        return false;
    }
}



// function gestionCompras(valor) {
//     const divDocumentos = document.getElementById('divDocumentos');
//     const inputDocumentos = document.getElementById('documentos');

//     if (valor == '5') {
//         divDocumentos.style.display = 'block';
//         inputDocumentos.disabled = false;
//     } else {
//         divDocumentos.style.display = 'none';
//         inputDocumentos.disabled = true;
//         inputDocumentos.value = ""; //
//     }
// }