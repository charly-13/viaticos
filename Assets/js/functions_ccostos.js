let tableCcostos;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {

    fntEmpresas(); // Cargar empresas al iniciar 
    setupCurrencyInput(document.querySelector('#txtPresupuestoAnual'));
    setupCurrencyInput(document.querySelector('#txtPresupuestoMensual'));


    // EVENTO: Cuando selecciona una empresa
    document.querySelector("#listEmpresa").addEventListener("change", function () {
        let idEmpresa = this.value;
        if (idEmpresa != "0") {
            fntDirecciones(idEmpresa);
            groupDireccion.style.display = "block"; // Mostrar select de direcciones
            groupArea.style.display = "none";       // Ocultar áreas hasta que seleccione dirección
        } else {
            groupDireccion.style.display = "none";
            groupArea.style.display = "none";
        }
    });

    // EVENTO: Cuando selecciona una dirección
    document.querySelector("#listDireccion").addEventListener("change", function () {
        let idDireccion = this.value;
        if (idDireccion != "0") {
            fntAreas(idDireccion);
            groupArea.style.display = "block"; // Mostrar select de áreas
        } else {
            groupArea.style.display = "none";
        }
    });




    tableCcostos = $('#tableCcostos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Ccostos/getCcostos",
            "dataSrc": ""
        },
        "columns": [
            { "data": "nombre_centro" },
            { "data": "nombre_area" },
            // { "data": "responsable" },
                   { 
          "data": "presupuestomensual",
          "render": function(data, type, row) {
              return formatNumberCurrency(data);
          }
        },
                   { 
          "data": "presupuestoanual",
          "render": function(data, type, row) {
              return formatNumberCurrency(data);
          }
        },
            { "data": "fechacreacion" },
            //{ "data": "creado_por" },
            { "data": "email_corporativo" },
            { "data": "options" }
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr": "Copiar",
                "className": "btn btn-secondary"
            }, {
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr": "Esportar a Excel",
                "className": "btn btn-success"
            }, {
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr": "Esportar a PDF",
                "className": "btn btn-danger"
            }, {
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr": "Esportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });



    //NUEVO CENTRO DE COSTO
    let formCcostos = document.querySelector("#formCcostos");
    formCcostos.onsubmit = function (e) {
        e.preventDefault();
        let strNombre = document.querySelector('#txtNombre').value;
        let intEmpresa = document.querySelector('#listEmpresa').value;
        let intDirteccion = document.querySelector('#listDireccion').value;
        let intArea = document.querySelector('#listArea').value;
        // let strResponsable = document.querySelector('#txtResponsable').value;
        let strPresupuestoAnual = document.querySelector('#txtPresupuestoAnual').value;
        let strPresupuestoMnesual = document.querySelector('#txtPresupuestoMensual').value;
        if (intEmpresa == '0' || intDirteccion == '0' || intArea == '0' || strNombre == ''  || strPresupuestoAnual == '' || strPresupuestoMnesual == '') {
            swal("Atención", "Todos los campos con (*) son obligatorios.", "error");
            return false;
        }

        // 🔔 LIMPIAMOS los inputs antes de enviar (quitamos $ y comas si hubiera)
        document.querySelector('#txtPresupuestoAnual').value = strPresupuestoAnual.replace(/[^0-9.]/g, '');
        document.querySelector('#txtPresupuestoMensual').value = strPresupuestoMnesual.replace(/[^0-9.]/g, '');

        divLoading.style.display = "flex";
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url + '/Ccostos/setCcostos';
        let formData = new FormData(formCcostos);
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                let objData = JSON.parse(request.responseText);
                if (objData.status) {
                    tableCcostos.api().ajax.reload();

                    $('#modalFormCcostos').modal("hide");
                    formCcostos.reset();
                    swal("Centro de Costos", objData.msg, "success");

                } else {
                    swal("Error", objData.msg, "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }

    // fntEmpresas(); 

}, false);



function fntEmpresas() {
    if (document.querySelector('#listEmpresa')) {
        let ajaxUrl = base_url + '/Ccostos/getSelectEmpresas';
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('#listEmpresa').innerHTML = request.responseText;
                //$('#listEmpresa').selectpicker('render');
            }
        }
    }
}

// CARGAR DIRECCIONES por Empresa
function fntDirecciones(idEmpresa) {
    let ajaxUrl = base_url + '/Ccostos/getSelectDirecciones/' + idEmpresa;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#listDireccion').innerHTML = request.responseText;
        }
    }
}



// CARGAR ÁREAS por Dirección
function fntAreas(idDireccion) {
    let ajaxUrl = base_url + '/Ccostos/getSelectAreas/' + idDireccion;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#listArea').innerHTML = request.responseText;
        }
    }
}


function fntViewInfo(idcentro) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Ccostos/getCcosto/' + idcentro;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {

                document.querySelector("#celNombre").innerHTML = objData.data.nombre_centro;
                document.querySelector("#celEmpresa").innerHTML = objData.data.nombre_empresa;
                document.querySelector("#celDireccion").innerHTML = objData.data.nombre_direccion;
                document.querySelector("#celArea").innerHTML = objData.data.nombre_area;
                // document.querySelector("#celResponsable").innerHTML = objData.data.responsable;
                document.querySelector("#celPresupuestoanual").innerHTML = objData.data.presupuestoanual;
                document.querySelector("#celPresupuestomensual").innerHTML = objData.data.presupuestomensual;
                document.querySelector("#celFechacreacion").innerHTML = objData.data.fechacreacion;
                document.querySelector("#celCreado_por").innerHTML = objData.data.creado_por_email_corporativo;
                //document.querySelector("#celActualizado_por").innerHTML = objData.data.actualizado_por;


                $('#modalViewCcosto').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

function fntEditInfo(element, idcentro) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Centro de Costo";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Ccostos/getCcosto/' + idcentro;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idcentro").value = objData.data.idcentro;
                document.querySelector("#txtNombre").value = objData.data.nombre_centro;
                // document.querySelector("#listEmpresa").value = objData.data.idempresa;
                //  document.querySelector("#listDireccion").value = objData.data.iddireccion;
                //   document.querySelector("#listArea").value = objData.data.idarea;
                // document.querySelector("#txtResponsable").value = objData.data.responsable;
                document.querySelector("#fechacreacion").value = objData.data.fechacreacion;
                document.querySelector("#listStatus").value = objData.data.estado;

                // 📌 Aplicamos formato con separador de miles
                setupCurrencyInput(document.querySelector('#txtPresupuestoAnual'));
                setupCurrencyInput(document.querySelector('#txtPresupuestoMensual'));

                // 📌 Formato MXN
             document.querySelector("#txtPresupuestoAnual").value = formatNumberCurrency(objData.data.presupuestoanual);
    document.querySelector("#txtPresupuestoMensual").value = formatNumberCurrency(objData.data.presupuestomensual);
    // ...


                
                //$('#listArea').selectpicker('render');

                //  PROCESO: Cargar empresas y mantener seleccionada
                fntEmpresasEdit(objData.data.idempresa, objData.data.iddireccion, objData.data.idarea);

                $('#modalFormCcostos').modal('show');

            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

// 👉 Función para dar formato bonito en MXN
function formatNumberCurrency(value) {
    let cleaned = (typeof value === 'string') ? value.replace(/[^0-9.]/g, '') : value;
    let number = parseFloat(cleaned);
    if (isNaN(number)) return '';
    return number.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
}

// 👉 Función para limpiar el formato para editar
function cleanNumberFormat(value) {
    return value.replace(/[^0-9.]/g, '');
}

// ✅ Aplica eventos al input para que funcione como esperas
function setupCurrencyInput(input) {
    input.addEventListener('focus', function () {
        this.value = cleanNumberFormat(this.value); // Limpio al enfocar
    });
    input.addEventListener('blur', function () {
        this.value = formatNumberCurrency(this.value); // Formateo al salir
    });
}


function fntEmpresasEdit(idEmpresaSel, idDireccionSel, idAreaSel) {
    let ajaxUrl = base_url + '/Ccostos/getSelectEmpresas';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#listEmpresa').innerHTML = request.responseText;
            document.querySelector('#listEmpresa').value = idEmpresaSel;

            // Mostrar select Dirección
            document.querySelector('#groupDireccion').style.display = "block";
            fntDireccionesEdit(idEmpresaSel, idDireccionSel, idAreaSel);
        }
    }
}

// Carga direcciones y mantiene seleccionada
function fntDireccionesEdit(idEmpresa, idDireccionSel, idAreaSel) {
    let ajaxUrl = base_url + '/Ccostos/getSelectDirecciones/' + idEmpresa;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#listDireccion').innerHTML = request.responseText;
            document.querySelector('#listDireccion').value = idDireccionSel;

            // Mostrar select Área
            document.querySelector('#groupArea').style.display = "block";
            fntAreasEdit(idDireccionSel, idAreaSel);
        }
    }
}


// Carga áreas y mantiene seleccionada
function fntAreasEdit(idDireccion, idAreaSel) {
    let ajaxUrl = base_url + '/Ccostos/getSelectAreas/' + idDireccion;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#listArea').innerHTML = request.responseText;
            document.querySelector('#listArea').value = idAreaSel;
        }
    }
}

function fntDelInfo(idcentro) {
    swal({
        title: "Confirmar eliminación",
        text: "¿Desea eliminar este centro de costo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {

        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Ccostos/delCcosto';
            let strData = "idcentro=" + idcentro;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableCcostos.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}

function formatearMiles(input) {
    // Remover cualquier cosa que no sea número o punto
    let value = input.value.replace(/[^0-9.]/g, '');

    // Parsear a número flotante
    let number = parseFloat(value);

    if (!isNaN(number)) {
        input.value = `$${number.toFixed(2)}`;
    } else {
        input.value = '';
    }
}

function getCleanNumber(input) {
    return input.value.replace(/[^0-9.]/g, '');
}

function colocarFechaHoraActual(input) {
    if (!input.value) {
        const ahora = new Date();
        const fecha = ahora.toISOString().slice(0, 16); // YYYY-MM-DDTHH:MM
        input.value = fecha;
    }
}



function openModal() {
    rowTable = "";
    document.querySelector('#idcentro').value = "";
document.getElementById('groupDireccion').style.display = 'none';
document.getElementById('groupArea').style.display = 'none';

    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Centro de Costo";
    document.querySelector("#formCcostos").reset();

    // Agregar fecha y hora actual al input
    const ahora = new Date();
    const year = ahora.getFullYear();
    const month = String(ahora.getMonth() + 1).padStart(2, '0');
    const day = String(ahora.getDate()).padStart(2, '0');
    const hours = String(ahora.getHours()).padStart(2, '0');
    const minutes = String(ahora.getMinutes()).padStart(2, '0');
    const fechaHoraLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.querySelector('#fechacreacion').value = fechaHoraLocal;
    $('#modalFormCcostos').modal('show');


}