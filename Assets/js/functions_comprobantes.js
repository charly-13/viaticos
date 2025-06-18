  

function modalAgregarComprobantes(idconcepto, idviatico, numDias, fechaInicial = null) {
  console.log(rfcEmpresa);
  conceptoActual = idconcepto;
    viaticoActual = idviatico;
  // document.getElementById('titleModalComprobante').innerText = `Adjuntar Comprobantes - ${idconcepto}`;
  document.getElementById('titleModalComprobante').innerText = `Adjuntar Comprobantes`;
  const contenedor = document.getElementById('contenedorDias');
  contenedor.innerHTML = '';

    // ✅ Reiniciar el resumen y el input al abrir el modal
  const resumenTotal = document.getElementById('resumenTotalGastos');
  if (resumenTotal) resumenTotal.innerText = 'Total: $0.00';

  const inputTotal = document.getElementById('totalGastosFactura');
  if (inputTotal) inputTotal.value = '';

    // ✅ Limpiar array/set de UUIDs
  facturasAdjuntadas.clear();

  let fechaBase;
  if (fechaInicial) {
    const [anio, mes, dia] = fechaInicial.split('-').map(Number);
    fechaBase = new Date(anio, mes - 1, dia);
  } else {
    fechaBase = new Date();
  }

  for (let i = 0; i < numDias; i++) {
    const fecha = new Date(fechaBase);
    fecha.setDate(fechaBase.getDate() + i);
    const fechaFormateada = fecha.toISOString().split('T')[0];

    const div = document.createElement('div');
    div.className = 'bg-section';
    div.innerHTML = `
      <strong>📅 Día ${i + 1} (${fecha.toLocaleDateString()})</strong>
      <input type="hidden" name="fechas[]" value="${fechaFormateada}">
      <div class="mb-2">
        <label class="form-label fw-semibold">Archivo XML (obligatorio):</label>
        <input type="file" class="form-control mb-2" accept=".xml" required onchange="procesarXMLDinamico(this)">
        <div class="datos-xml mt-2"></div>
      </div>
      <div class="mb-2">
        <label class="form-label fw-semibold">Archivo PDF o Imagen (opcional):</label>
        <input type="file" class="form-control mb-2" accept="application/pdf,image/*">
      </div>
      <div>
        <label class="form-label fw-semibold">Comentario:</label>
        <textarea class="form-control" rows="2" placeholder="Comentario para este comprobante"></textarea>
      </div>
    `;
    contenedor.appendChild(div);
  }

  document.querySelector('#idconcepto').value = idconcepto;
    document.querySelector('#idviatico').value = idviatico;
  $('#modalFormAddComprobantes').modal('show');
}


// Array global para guardar UUIDs ya adjuntados en este modal
    const facturasAdjuntadas = new Set();
// Objeto para almacenar temporalmente los datos de cada XML procesado por UUID
const datosFacturas = {};

function procesarXMLDinamico(inputFile) {
  const archivo = inputFile.files[0];
  const formData = new FormData();
  formData.append('archivoXML', archivo);

  const contenedorDatos = inputFile.parentElement.querySelector('.datos-xml');
  contenedorDatos.innerHTML = '<div class="text-muted">Procesando archivo...</div>';

  let ajaxUrl = base_url + '/Viaticosgenerales/procesarXML';

  fetch(ajaxUrl, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status) {
      // Validar si el UUID ya existe en facturasAdjuntadas
      if (facturasAdjuntadas.has(data.uuid)) {
        contenedorDatos.innerHTML = `<div class="alert alert-warning p-2 mb-1">Esta factura ya fue adjuntada anteriormente.</div>`;
        inputFile.value = ''; // Limpia el input para que suba otro archivo
      } else {
        // Si no existe, agrega el UUID al set y muestra los datos
        facturasAdjuntadas.add(data.uuid);
                  datosFacturas[data.uuid] = {
            rfcEmisor: data.rfcEmisor,
            rfcReceptor: data.rfcReceptor,
            subtotal: data.subtotal,
            total: data.total,
            fecha: data.fecha,
            uuid: data.uuid
          };
          // Guardar el UUID en el input file para relacionarlo después
          inputFile.setAttribute('data-uuid', data.uuid);
        contenedorDatos.innerHTML = `
          <div class="alert alert-success p-2 mb-1">
            <small><strong>RFC Emisor:</strong> ${data.rfcEmisor}</small><br>
            <small><strong>RFC Receptor:</strong> ${data.rfcReceptor}</small><br>
            <small><strong>Subtotal:</strong> $${data.subtotal}</small><br>
            <small class="total-xml"><strong>Total:</strong> $${data.total}</small><br>
            <small><strong>Fecha:</strong> ${data.fecha}</small><br>
            <small><strong>UUID:</strong> ${data.uuid}</small>
          </div>
        `;
         actualizarTotalGeneral();
      }
    } else {
      contenedorDatos.innerHTML = `<div class="alert alert-danger p-2 mb-1">${data.message}</div>`;
          actualizarTotalGeneral();
    }
  })
  .catch(err => {
    console.error(err);
    contenedorDatos.innerHTML = `<div class="alert alert-danger p-2 mb-1">Error al procesar XML.</div>`;
    actualizarTotalGeneral();
  });
}



function guardarComprobantes() {
  const contenedor = document.getElementById('contenedorDias');
  const comprobantesData = new FormData();

  // Se agrega el concepto actual (puede ser idconcepto)
  comprobantesData.append('concepto', conceptoActual);
    comprobantesData.append('viatico', viaticoActual);

      // ✅ Captura del total de gastos
  const totalGastos = document.getElementById('totalGastosFactura').value || 0;
  comprobantesData.append('totalGastosFactura', totalGastos);

  // Recorrer cada div de día
  const dias = contenedor.querySelectorAll('.bg-section');

  dias.forEach((dia, index) => {
    // Fecha (hidden input)
    const fecha = dia.querySelector('input[name="fechas[]"]').value;

    // Archivos XML y PDF
    const inputXML = dia.querySelector('input[type="file"][accept=".xml"]');
    const inputPDF = dia.querySelector('input[type="file"][accept="application/pdf,image/*"]');

    // Comentario
    const comentario = dia.querySelector('textarea').value;

        // Validar que haya XML (obligatorio)
        if (inputXML.files.length === 0) {
        swal({
  type: 'warning',
  title: 'Archivo XML faltante',
  text: `Debe adjuntar un archivo XML para el día ${index + 1}`,
  confirmButtonText: 'Entendido'
});
throw new Error('Falta archivo XML');
        }

            const uuid = inputXML.getAttribute('data-uuid');
    const datosFactura = datosFacturas[uuid];

        // Adjuntar archivos con nombres dinámicos para PHP
        comprobantesData.append(`comprobantes[${index}][fecha]`, fecha);
        comprobantesData.append(`comprobantes[${index}][comentario]`, comentario);
        comprobantesData.append(`comprobantes[${index}][xml]`, inputXML.files[0]);

            // 👇 Agregamos los datos extraídos del XML
    comprobantesData.append(`comprobantes[${index}][uuid]`, datosFactura.uuid);
    comprobantesData.append(`comprobantes[${index}][rfcEmisor]`, datosFactura.rfcEmisor);
    comprobantesData.append(`comprobantes[${index}][rfcReceptor]`, datosFactura.rfcReceptor);
    comprobantesData.append(`comprobantes[${index}][subtotal]`, datosFactura.subtotal);
    comprobantesData.append(`comprobantes[${index}][total]`, datosFactura.total);
    comprobantesData.append(`comprobantes[${index}][fechaFactura]`, datosFactura.fecha);

        if (inputPDF.files.length > 0) {
        comprobantesData.append(`comprobantes[${index}][pdf]`, inputPDF.files[0]);
        }
    });

    // Enviar datos al backend con fetch
    fetch(base_url + '/Viaticosgenerales/guardarComprobantess', {
        method: 'POST',
        body: comprobantesData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {


          swal({
    title: "Comprobantes!",
    text: data.msg,
    type: "success"
}, function () {
    location.reload();
});
        // Aquí puedes cerrar modal o resetear formulario
        $('#modalFormAddComprobantes').modal('hide');
        // Opcional: limpiar variables
        facturasAdjuntadas.clear();
        Object.keys(datosFacturas).forEach(key => delete datosFacturas[key]);
        document.getElementById('contenedorDias').innerHTML = '';
        mostrarGaleria(); // o la función que actualice la galería si tienes
        } else {
        alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        //alert('Error al guardar comprobantes');
    });
    }


function actualizarTotalGeneral() {
  let totalGastos = 0;

  const totales = document.querySelectorAll('.total-xml');

  totales.forEach(item => {
    const match = item.textContent.match(/\$([\d,\.]+)/);
    if (match && match[1]) {
      totalGastos += parseFloat(match[1].replace(/,/g, ''));
    }
  });

  document.getElementById('resumenTotalGastos').innerText = `Total: $${totalGastos.toFixed(2)}`;

    // ✅ También actualizar el input oculto o visible
  const inputTotal = document.getElementById('totalGastosFactura');
  if (inputTotal) {
    inputTotal.value = totalGastos.toFixed(2); // Solo el número, sin el símbolo $
  }
}




// function procesarXMLDinamico(inputFile) {
//   const archivo = inputFile.files[0];
//   const formData = new FormData();
//   formData.append('archivoXML', archivo);

//   const contenedorDatos = inputFile.parentElement.querySelector('.datos-xml');
//   contenedorDatos.innerHTML = '<div class="text-muted">Procesando archivo...</div>';

//   fetch('<?= base_url() ?>/viaticos/procesarXML', {
//     method: 'POST',
//     body: formData
//   })
//   .then(response => response.json())
//   .then(data => {
//     if (data.status) {
//       contenedorDatos.innerHTML = `
//         <div class="alert alert-success p-2 mb-0">
//           <small><strong>RFC:</strong> ${data.rfc}</small><br>
//           <small><strong>Subtotal:</strong> $${data.subtotal}</small><br>
//           <small><strong>Total:</strong> $${data.total}</small><br>
//           <small><strong>UUID:</strong> ${data.uuid}</small>
//         </div>
//       `;
//     } else {
//       contenedorDatos.innerHTML = `<div class="alert alert-danger p-2 mb-0">${data.message}</div>`;
//     }
//   })
//   .catch(err => {
//     console.error(err);
//     contenedorDatos.innerHTML = `<div class="alert alert-danger p-2 mb-0">Error al procesar XML.</div>`;
//   });
// }

