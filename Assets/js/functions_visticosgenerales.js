let tableViaticos;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {



    if (localStorage.getItem('abrirModalViaticos') === '1') {
        localStorage.removeItem('abrirModalViaticos'); // Evita que se abra siempre
        openModal();
        console.log("abrimos");
    }

    tableViaticos = $('#tableViaticos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Viaticosgenerales/getViaticos",
            "dataSrc": ""
        },
        "columns": [
            { "data": "codigo_solicitud" },
            { "data": "nombreusuario" },
            { "data": "fecha_salida" },
            { "data": "fecha_regreso" },
            { "data": "nombre_centro" },
            {
                "data": "total",
                "render": function (data, type, row) {
                    return formatNumberCurrency(data);
                }
            },
            { "data": "estado_viatico" },
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






    //NUEVA SOLICITUD DE  VIÁTICO
    let formViaticoGeneral = document.querySelector("#formViaticoGeneral");
    formViaticoGeneral.onsubmit = function (e) {
        e.preventDefault();
        tinymce.triggerSave();
        let strNombre = document.querySelector('#nombreusuario').value;
        let intlistCentrosCosto = document.querySelector('#listCentrosCosto').value;
        let strfecha_salida = document.querySelector('#fecha_salida').value;
        let strfecha_regreso = document.querySelector('#fecha_regreso').value;
        let strmotivo = document.querySelector('#motivo').value;
        //let strDescripcion = document.querySelector('#txtDescripcion').value;
        let strlugar_destino = document.querySelector('#lugar_destino').value;

        const strDescripcion = document.getElementById('txtDescripcion').value.trim();


         let strlistBancos = document.querySelector('#listBancos').value;
         let strbiaticos_nombre_titular = document.querySelector('#biaticos_nombre_titular').value;
        let strbiaticos_numero_cuenta = document.querySelector('#biaticos_numero_cuenta').value;
        let strbiaticos_clabe_interbancaria = document.querySelector('#biaticos_clabe_interbancaria').value;
        



        if (strNombre == '' || intlistCentrosCosto == '0' || strfecha_salida == '' || strfecha_regreso == '' || strmotivo == '' || strlugar_destino == '' || strlistBancos == '0' || strlistBancos == '1' || strbiaticos_nombre_titular == '' || strbiaticos_numero_cuenta == '' || strbiaticos_clabe_interbancaria == '' ) {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else if (!strDescripcion) {
            swal("Atención", "Ingresa la descripción de las actividades a realizar.", "error");
            return false;

        }
        divLoading.style.display = "flex";
        tinyMCE.triggerSave();
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url + '/Viaticosgenerales/setViaticogeneral';

        let formData = new FormData(formViaticoGeneral);
        formData.append("conceptos", JSON.stringify(conceptos));
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                let objData = JSON.parse(request.responseText);
                if (objData.status) {

                    tableViaticos.api().ajax.reload();


                    $('#modalFormViaticosGenerales').modal("hide");
                    formViaticoGeneral.reset();
                    swal("Viáticos", objData.msg, "success");

                } else {
                    swal("Error", objData.msg, "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }

    fntBancos();

}, false);

tinymce.init({
    selector: '#txtDescripcion',
    width: "100%",
    height: 280,
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});



function fntBancos(){
    if(document.querySelector('#listBancos')){
        let ajaxUrl = base_url+'/Viaticosgenerales/getSelectBancos';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listBancos').innerHTML = request.responseText;
                $('#listBancos').selectpicker('render');
            }
        }
    }
}



// function fntViewInfo(idcategoria) {
//     let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//     let ajaxUrl = base_url + '/Categorias/getCategoria/' + idcategoria;
//     request.open("GET", ajaxUrl, true);
//     request.send();
//     request.onreadystatechange = function () {
//         if (request.readyState == 4 && request.status == 200) {
//             let objData = JSON.parse(request.responseText);
//             if (objData.status) {
//                 let estado = objData.data.status == 1 ?
//                     '<span class="badge badge-success">Activo</span>' :
//                     '<span class="badge badge-danger">Inactivo</span>';
//                 document.querySelector("#celId").innerHTML = objData.data.idcategoria;
//                 document.querySelector("#celNombre").innerHTML = objData.data.nombre;
//                 document.querySelector("#celDescripcion").innerHTML = objData.data.descripcion;
//                 document.querySelector("#celEstado").innerHTML = estado;
//                 document.querySelector("#imgCategoria").innerHTML = '<img src="' + objData.data.url_portada + '"></img>';
//                 $('#modalViewCategoria').modal('show');
//             } else {
//                 swal("Error", objData.msg, "error");
//             }
//         }
//     }
// }

// function fntEditInfo(element, idcategoria) {
//     rowTable = element.parentNode.parentNode.parentNode;
//     document.querySelector('#titleModal').innerHTML = "Actualizar Categoría";
//     document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
//     document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
//     document.querySelector('#btnText').innerHTML = "Actualizar";
//     let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//     let ajaxUrl = base_url + '/Categorias/getCategoria/' + idcategoria;
//     request.open("GET", ajaxUrl, true);
//     request.send();
//     request.onreadystatechange = function () {
//         if (request.readyState == 4 && request.status == 200) {
//             let objData = JSON.parse(request.responseText);
//             if (objData.status) {
//                 document.querySelector("#idCategoria").value = objData.data.idcategoria;
//                 document.querySelector("#txtNombre").value = objData.data.nombre;
//                 document.querySelector("#txtDescripcion").value = objData.data.descripcion;
//                 document.querySelector('#foto_actual').value = objData.data.portada;
//                 document.querySelector("#foto_remove").value = 0;

//                 if (objData.data.status == 1) {
//                     document.querySelector("#listStatus").value = 1;
//                 } else {
//                     document.querySelector("#listStatus").value = 2;
//                 }
//                 $('#listStatus').selectpicker('render');

//                 if (document.querySelector('#img')) {
//                     document.querySelector('#img').src = objData.data.url_portada;
//                 } else {
//                     document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objData.data.url_portada + ">";
//                 }

//                 if (objData.data.portada == 'portada_categoria.png') {
//                     document.querySelector('.delPhoto').classList.add("notBlock");
//                 } else {
//                     document.querySelector('.delPhoto').classList.remove("notBlock");
//                 }

//                 $('#modalFormViaticosGenerales').modal('show');

//             } else {
//                 swal("Error", objData.msg, "error");
//             }
//         }
//     }
// }

// function fntDelInfo(idcategoria) {
//     swal({
//         title: "Eliminar Categoría",
//         text: "¿Realmente quiere eliminar al categoría?",
//         type: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Si, eliminar!",
//         cancelButtonText: "No, cancelar!",
//         closeOnConfirm: false,
//         closeOnCancel: true
//     }, function (isConfirm) {

//         if (isConfirm) {
//             let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//             let ajaxUrl = base_url + '/Categorias/delCategoria';
//             let strData = "idCategoria=" + idcategoria;
//             request.open("POST", ajaxUrl, true);
//             request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//             request.send(strData);
//             request.onreadystatechange = function () {
//                 if (request.readyState == 4 && request.status == 200) {
//                     let objData = JSON.parse(request.responseText);
//                     if (objData.status) {
//                         swal("Eliminar!", objData.msg, "success");
//                         tableViaticos.api().ajax.reload();
//                     } else {
//                         swal("Atención!", objData.msg, "error");
//                     }
//                 }
//             }
//         }

//     });

// }



const usuarioEnSesion = 'usuario1'; // <-- Cámbialo a otro valor para probar el otro caso

const fechaSalidaInput = document.getElementById('fecha_salida');
const fechaRegresoInput = document.getElementById('fecha_regreso');

// function openModalDos() {

//     rowTable = "";
//     document.querySelector('#idviatico').value = "";
//     document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
//     document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
//     document.querySelector('#btnText').innerHTML = "Solicitar";
//     document.querySelector('#titleModal').innerHTML = '<i class="fa fa-file-alt mr-2"></i> Registrar Nueva Solicitud';
//     document.querySelector("#formViaticoGeneral").reset();
 
//     const ahora = new Date();
//     const year = ahora.getFullYear();
//     const month = String(ahora.getMonth() + 1).padStart(2, '0');
//     const day = String(ahora.getDate()).padStart(2, '0');
//     const hours = String(ahora.getHours()).padStart(2, '0');
//     const minutes = String(ahora.getMinutes()).padStart(2, '0');
//     const fechaHoraLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
//     document.querySelector('#fechacreacion').value = fechaHoraLocal;


//     // --- Lógica de fechas para inputs fechaSalida y fechaRegreso ---

//     // Si el usuario es diferente a el área de POST VENTA el usuario podrá seleccionar despues de 7 días de anticipo
//     let fechaMin = new Date();
//     if (userData.id_area != '90') {
//         fechaMin.setDate(fechaMin.getDate() + 8); // +7 días para usuario1
//     }
//     // Convertimos a formato YYYY-MM-DD para el input date
//     const fechaMinStr = fechaMin.toISOString().split('T')[0];

//     // Asignamos los min y valor inicial para ambos inputs
//     const fechaSalidaInput = document.querySelector('#fecha_salida');
//     const fechaRegresoInput = document.querySelector('#fecha_regreso');

//     if (fechaSalidaInput && fechaRegresoInput) {
//         fechaSalidaInput.min = fechaMinStr;
//         fechaRegresoInput.min = fechaMinStr;
//         fechaSalidaInput.value = fechaMinStr;
//         fechaRegresoInput.value = fechaMinStr;
//     }
//     // -------------------------------------------------------------



//     $('#modalFormViaticosGenerales').modal('show');
//     fntAreas();
//     fntCentrosCosto(session_id_area);
//     renderTabla();

//       if(userData.id_rol=='4'){
// // console.log("deberiamos de mandar directamente a telles");
//  document.querySelector('#email_jefe_directo').value = 'raul.tellez@ldrsolutions.com.mx';
//   }

//       document.querySelector('#idjefedirecto').value = userData.id_colaborador_jefe;

//       document.querySelector('#idjefedirectosuperior').value = userData.id_colaborador_jefe_superior;

//       if(userData.biaticos_id_banco != '1'){
// document.getElementById('divDatosBancarios').style.display = 'none';
//       }else{
// document.getElementById('divDatosBancarios').style.display = 'block';
//       }

//   document.querySelector('#listBancos').value = userData.id_banco;
//   $('#listBancos').selectpicker('render');
//  document.querySelector('#biaticos_numero_cuenta').value = userData.cuenta_bancaria;
//  document.querySelector('#biaticos_clabe_interbancaria').value = userData.	clabe_interbancaria;
//  document.querySelector('#id_colaborador').value = userData.id_colaborador;
 

// }


function openModal() {
  fetch(base_url+'/Viaticosgenerales/getDataUsuario')
    .then(res => res.json())
    .then(response => {
      if (response.status) {
        const data = response.data; 

        // Ya tienes los datos, ahora los usas para preparar el modal
        rowTable = "";
        document.querySelector('#idviatico').value = "";
        document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
        document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
        document.querySelector('#btnText').innerHTML = "Solicitar";
        document.querySelector('#titleModal').innerHTML = '<i class="fa fa-file-alt mr-2"></i> Registrar Nueva Solicitud';
        document.querySelector("#formViaticoGeneral").reset();

        // Fecha creación
        const ahora = new Date();
        const year = ahora.getFullYear();
        const month = String(ahora.getMonth() + 1).padStart(2, '0');
        const day = String(ahora.getDate()).padStart(2, '0');
        const hours = String(ahora.getHours()).padStart(2, '0');
        const minutes = String(ahora.getMinutes()).padStart(2, '0');
        const fechaHoraLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.querySelector('#fechacreacion').value = fechaHoraLocal;

        // Fechas mínimas
        let fechaMin = new Date();
        if (userData.id_area != '90') {
          fechaMin.setDate(fechaMin.getDate() + 8);
        }
        const fechaMinStr = fechaMin.toISOString().split('T')[0];
        document.querySelector('#fecha_salida').min = fechaMinStr;
        document.querySelector('#fecha_regreso').min = fechaMinStr;
        document.querySelector('#fecha_salida').value = fechaMinStr;
        document.querySelector('#fecha_regreso').value = fechaMinStr;

        // Mostrar modal y cargar selects
        $('#modalFormViaticosGenerales').modal('show');
        fntAreas();
        fntCentrosCosto(session_id_area);
        renderTabla();

        if (userData.id_rol == '4') {
          document.querySelector('#email_jefe_directo').value = 'raul.tellez@ldrsolutions.com.mx';
        }

        document.querySelector('#idjefedirecto').value = userData.id_colaborador_jefe;
        document.querySelector('#idjefedirectosuperior').value = userData.id_colaborador_jefe_superior;

        // Mostrar u ocultar sección bancaria según base de datos
        if (data.biaticos_id_banco != '1') {
          document.getElementById('divDatosBancarios').style.display = 'none';
        } else {
          document.getElementById('divDatosBancarios').style.display = 'block';
        }

        // Llenar inputs bancarios
        document.querySelector('#listBancos').value = data.id_banco;
        $('#listBancos').selectpicker('render');
        document.querySelector('#biaticos_numero_cuenta').value = data.cuenta_bancaria;
        document.querySelector('#biaticos_clabe_interbancaria').value = data.clabe_interbancaria;
        document.querySelector('#id_colaborador').value = data.id_colaborador;

      } else {
        alert("No se pudieron cargar los datos bancarios.");
      }
    })
    .catch(err => {
      console.error("Error al consultar datos bancarios:", err);
    });
}



const fecha_salida = document.getElementById('fecha_salida');
const fecha_regreso = document.getElementById('fecha_regreso');
const infoDias = document.getElementById('infoDias'); // Para mostrar el resultado (opcional)
let diasEnRango = 0;


function calcularDiasEntreFechas(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    const diffTime = fin - inicio;
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
    return diffDays;
}


fechaRegresoInput.addEventListener('change', () => {
    const salida = fecha_salida.value;
    const regreso = fecha_regreso.value;

    if (salida && regreso) {
        if (new Date(regreso) < new Date(salida)) {
            infoDias.textContent = 'La fecha de regreso no puede ser anterior a la de salida.';
            diasEnRango = 0;
        } else {
            diasEnRango = calcularDiasEntreFechas(salida, regreso);
            infoDias.textContent = `Días seleccionados: ${diasEnRango}`;
            const input = document.getElementById('inputDias');
            input.value = diasEnRango;

            montoPorDia();


        }
    } else {
        infoDias.textContent = '';
        diasEnRango = 0;

        const input = document.getElementById('inputDias');
        input.value = '';
        montoPorDia();

    }
});

// function montoPorDia(){

// const inputRaw = document.getElementById('monto_Diario1').value;
// const inputLimpio = inputRaw.replace(/[$,]/g, '');
// const input = parseFloat(inputLimpio);

// const intDias = parseInt(document.getElementById('inputDias').value);


// const monto1 = 1; // Si monto1 es 1, resultado será input * inputDias
// const resultado = input * monto1 * intDias;

//  document.getElementById('monto_Subtotal1').value = formatMoney(resultado);


// }


// 🔧 Esta función recalcula TODOS los subtotales según el número de días
function montoPorDia() {
    const dias = parseInt(document.getElementById('inputDias').value) || 0;

    conceptos.forEach((c, i) => {
        const montoInput = document.getElementById(`monto_Diario${i + 1}`);
        // Quitar el símbolo de moneda y comas
        const valorLimpio = (montoInput.value || "0").toString().replace(/[^0-9.]/g, "");
        const monto = parseFloat(valorLimpio) || 0;

        const subtotal = monto * dias;
        c.subTotal = subtotal;

        const subtotalInput = document.getElementById(`monto_Subtotal${i + 1}`);
        subtotalInput.value = formatMoney(subtotal);
    });

    calcularTotalGeneral();
}



function fntAreas() {
    if (document.querySelector('#listArea')) {
        let ajaxUrl = base_url + '/Areas/getSelectAreas';
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('#listArea').innerHTML = request.responseText;
                $('#listArea').selectpicker('destroy');
                // Deshabilitar el input después de cargar las opciones
                document.querySelector('#listArea').disabled = true;
                $('#listArea').selectpicker('render');
                // console.log(session_id_area);

                document.querySelector('#listArea').value = session_id_area;
                $('#listArea').selectpicker('refresh');

            }
        }
    }
}

function fntCentrosCosto(session_id_area) {
    if (document.querySelector('#listCentrosCosto')) {
        let ajaxUrl = base_url + '/Ccostos/getSelectCentrosCosto/' + session_id_area;
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('#listCentrosCosto').innerHTML = request.responseText;
                $('#listCentrosCosto').selectpicker('destroy');
                $('#listCentrosCosto').selectpicker('render');


            }
        }
    }
}

let conceptos = "";

if (userData.id_rol == '4') {//MONTOS CUANDO ES DIRECTO
    conceptos = [
        { concepto: "Alimentación", solicituddiaria: "1540.00", subTotal: "$0.00", comentario: "" },
        { concepto: "Otros (Especificar)", solicituddiaria: "", subTotal: "$0.00", comentario: "" }
    ];
} else if (userData.id_rol == '3') { //MONTOS CUANDO ES GERENTE GENERAL
    conceptos = [
        { concepto: "Alimentación", solicituddiaria: "1100.00", subTotal: "$0.00", comentario: "" },
        { concepto: "Otros (Especificar)", solicituddiaria: "", subTotal: "$0.00", comentario: "" }
    ];

} else if (userData.id_rol == '1') { //MONTOS CUANDO ES PERSONAL OPERATIVO
    conceptos = [
        { concepto: "Alimentación", solicituddiaria: "485.00", subTotal: "$0.00", comentario: "" },
        { concepto: "Otros (Especificar)", solicituddiaria: "", subTotal: "$0.00", comentario: "" }
    ];

}






function renderTabla() {
    const tbody = document.getElementById("tabla-conceptos");
    tbody.innerHTML = "";
    let total = 0;

    conceptos.forEach((c, i) => {
        const filaIndex = i + 1; // Para que inicie desde 1
        const montoFormateado = c.solicituddiaria !== "" ? formatMoney(c.solicituddiaria) : "";
        total += parseFloat(c.solicituddiaria || 0);

        tbody.innerHTML += `<tr>
            <td>
                <input 
                    type="text" 
                    class="form-control" 
                    value="${c.concepto}" 
                    readonly>
            </td>
            <td>
                <input 
                    type="text" 
                    class="form-control" 
                    autocomplete="off"
                    id="monto_Diario${i + 1}"
                    value="${montoFormateado}" 
                    onchange="aplicarDiasporMontos(this.value, ${filaIndex})"
                    ${i === 0 ? 'readonly' : ''}>
            </td>
            <td>
                <input 
                    type="text" 
                    class="form-control" 
                    value="${c.subTotal}" 
                    id="monto_Subtotal${filaIndex}"
                    readonly 
                    onblur="manejarCambioMonto(this, ${i})">
            </td>
            <td>
                <textarea 
                    class="form-control" 
                    rows="1" 
                    onchange="actualizar(${i}, 'comentario', this.value)">
                    ${c.comentario}
                </textarea>
            </td>
        </tr>`;
    });
}

function aplicarDiasporMontos(valor, index) {
    const inputDias = document.getElementById("inputDias");
    const dias = parseInt(inputDias.value) || 0;

    const valorNum = parseFloat(valor.toString().replace(/,/g, "")) || 0;
    const subtotal = valorNum * dias;

    const valorFormateado = formatMoney(valorNum);
    const subtotalFormateado = formatMoney(subtotal);

    document.getElementById(`monto_Diario${index}`).value = valorFormateado;
    document.getElementById(`monto_Subtotal${index}`).value = subtotalFormateado;

    // Actualizar el arreglo conceptos si quieres conservar los datos
    //conceptos[index - 1].subTotal = subtotal;
    conceptos[index - 1].solicituddiaria = valorNum.toFixed(2); // String con 2 decimales ✔
    conceptos[index - 1].subTotal = subtotal.toFixed(2);

    // Recalcular el total general
    calcularTotalGeneral();
}

function calcularTotalGeneral() {
    let total = 0;

    conceptos.forEach(c => {
        total += parseFloat(c.subTotal || 0);
    });

    const totalFormateado = formatMoney(total);

    document.getElementById("total").value = totalFormateado;
    document.getElementById("total_limpio").value = total;
}




function actualizar(index, campo, valor) {
    conceptos[index][campo] = valor;
    console.log(conceptos);
    sincronizarConceptos();


}

function manejarCambioMonto(input, index) {
    const valorNumerico = parseFloat(input.value.replace(/[^0-9.]/g, '')) || 0;
    conceptos[index]['solicituddiaria'] = valorNumerico.toFixed(2); // Guardar limpio con 2 decimales
    input.value = formatMoney(conceptos[index]['solicituddiaria']); // Mostrar formateado
    actualizarTotal();

}


function actualizarTotal() {
    const total = conceptos.reduce((acc, c) => acc + parseFloat(c.solicituddiaria || 0), 0);
    //document.getElementById("total-montos").innerText = formatMoney(total.toFixed(2));
    document.getElementById("total").value = formatMoney(total.toFixed(2));
    document.getElementById("total_limpio").value = total.toFixed(2);
}

// Formatea un número como dinero: 1234.5 => $1,234.50
function formatMoney(valor) {
    return '$' + Number(valor).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}


function sincronizarConceptos() {
    conceptos.forEach((c, i) => {
        const filaIndex = i + 1;

        const conceptoInput = document.querySelector(`#tabla-conceptos tr:nth-child(${filaIndex}) input[readonly]`);
        const montoInput = document.getElementById(`monto_Diario${filaIndex}`);
        const subtotalInput = document.getElementById(`monto_Subtotal${filaIndex}`);
        const comentarioInput = document.querySelector(`#tabla-conceptos tr:nth-child(${filaIndex}) textarea`);

        const conceptoValor = conceptoInput ? conceptoInput.value : c.concepto;
        const montoValor = parseFloat((montoInput.value || "0").toString().replace(/[^0-9.]/g, "")) || 0;
        //const subtotalValor = parseFloat((subtotalInput.value || "0").toString().replace(/[^0-9.]/g, "")) || 0;
        const subtotalValor = parseFloat((subtotalInput.value || "0").toString().replace(/[^0-9.]/g, "")) || 0;
        const comentarioValor = comentarioInput ? comentarioInput.value.trim() : "";

        // 🔄 Actualizar el objeto en el arreglo
        c.concepto = conceptoValor;
        c.solicituddiaria = montoValor.toFixed(2);
        //c.subTotal = subtotalValor.toFixed(2);  // 🔄 Aquí también lo guardas como "75000.00"
        c.subTotal = subtotalValor.toFixed(2);   // 🔔 CORREGIDO → también string
        c.comentario = comentarioValor;


    });

    console.log(conceptos); // ✅ Visualiza el arreglo actualizado
}



function consultarSaldo(idcentro) {
    if (idcentro === "") return;

    if (document.querySelector('#listCentrosCosto')) {
        let ajaxUrl = base_url + '/Ccostos/getCcosto/' + idcentro;
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                if (objData.status) {
                    document.querySelector("#saldoDisponible").value = objData.data.presupuestoanual;
                }


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

