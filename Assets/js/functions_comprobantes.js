  

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

 const tipos = ['Desayuno', 'Comida', 'Cena'];

for (let i = 0; i < numDias; i++) {
  const fecha = new Date(fechaBase);
  fecha.setDate(fechaBase.getDate() + i);
  const fechaFormateada = fecha.toISOString().split('T')[0];

  const div = document.createElement('div');
  div.className = 'bg-section';

  let contenido = `<strong>📅 Día ${i + 1} (${fecha.toLocaleDateString()})</strong><br>`;

  tipos.forEach(tipo => {
    contenido += `
      <h3>${tipo}</h3>
      <input type="hidden" name="fechas[]" value="${fechaFormateada}">
      <input type="hidden" name="tipos[]" value="${tipo}"> <!-- 👈 INPUT OCULTO PARA EL TIPO -->

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
  });

  div.innerHTML = contenido;
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
      // if (facturasAdjuntadas.has(data.uuid)) {
      //   contenedorDatos.innerHTML = `<div class="alert alert-warning p-2 mb-1">Esta factura ya fue adjuntada anteriormente.</div>`;
      //   inputFile.value = ''; // Limpia el input para que suba otro archivo
      // } else {
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
      // }
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

function modalAgregarComprobantes(idconcepto, idviatico, numDias, fechaInicial = null) {
  conceptoActual = idconcepto;
  viaticoActual = idviatico;

  document.getElementById('titleModalComprobante').innerText = `Adjuntar Comprobantes`;
  const contenedor = document.getElementById('contenedorDias');
  contenedor.innerHTML = '';

  const resumenTotal = document.getElementById('resumenTotalGastos');
  if (resumenTotal) resumenTotal.innerText = 'Total: $0.00';

  const inputTotal = document.getElementById('totalGastosFactura');
  if (inputTotal) inputTotal.value = '';

  facturasAdjuntadas.clear();

  let fechaBase = fechaInicial
    ? (() => {
        const [anio, mes, dia] = fechaInicial.split('-').map(Number);
        return new Date(anio, mes - 1, dia);
      })()
    : new Date();

  const tipos = ['Desayuno', 'Comida', 'Cena'];

  let tabla = `
    <div class="legend-box mb-3">
      📌 Adjunta los comprobantes XML (obligatorio) y PDF o imagen (opcional) por cada comida y día. 
      Al seleccionar un XML, se mostrará automáticamente la información extraída.
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-cente">
        <thead>
          <tr>
            <th style="width: 10%;">Día</th>
            <th>🍳 Desayuno</th>
            <th>🍽 Comida</th>
            <th>🌙 Cena</th>
          </tr>
        </thead>
        <tbody>`;

  for (let i = 0; i < numDias; i++) {
    const fecha = new Date(fechaBase);
    fecha.setDate(fechaBase.getDate() + i);
    const fechaFormateada = fecha.toISOString().split('T')[0];

    tabla += `<tr><td class="fw-semibold text-nowrap">📅 Día ${i + 1} (${fecha.toLocaleDateString()})</td>`;

    tipos.forEach(tipo => {
      tabla += `
        <td>
          <input type="hidden" name="fechas[]" value="${fechaFormateada}">
          <input type="hidden" name="tipos[]" value="${tipo}">
          <input 
            type="file" 
            class="form-control form-control-sm mb-1" 
            accept=".xml" 
            required 
            data-dia="${i + 1}" 
            data-tipo="${tipo}" 
            onchange="procesarXMLDinamico(this)">
          <div class="datos-xml mb-1">Información XML aparecerá aquí...</div>
          <input type="file" class="form-control form-control-sm mb-1" accept="application/pdf,image/*">
          <textarea class="form-control form-control-sm" rows="4" placeholder="Comentario..."></textarea>
        </td>`;
    });

    tabla += `</tr>`;
  }

  tabla += `
        </tbody>
      </table>
    </div>`;

  contenedor.innerHTML = tabla;
  $('#modalFormAddComprobantes').modal('show');
}



function guardarComprobantes() {
  const contenedor = document.getElementById('contenedorDias');
  const comprobantesData = new FormData();

  comprobantesData.append('concepto', conceptoActual);
  comprobantesData.append('viatico', viaticoActual);

  const totalGastos = document.getElementById('totalGastosFactura').value || 0;
  comprobantesData.append('totalGastosFactura', totalGastos);

  // Seleccionamos directamente las filas <tr> de la tabla
  const filas = contenedor.querySelectorAll('tbody tr');

  filas.forEach((fila, index) => {
    // Recuperamos cada celda correspondiente a desayuno, comida, cena
    const celdas = fila.querySelectorAll('td');

    // Saltamos la primera celda que tiene el texto del día (📅 Día X)
    for (let i = 1; i < celdas.length; i++) {
      const celda = celdas[i];

      const fecha = celda.querySelector('input[name="fechas[]"]').value;
      const tipo = celda.querySelector('input[name="tipos[]"]').value;
      const inputXML = celda.querySelector('input[type="file"][accept=".xml"]');
      const inputPDF = celda.querySelector('input[type="file"][accept="application/pdf,image/*"]');
      const comentario = celda.querySelector('textarea').value;

      if (!inputXML.files.length) {
        swal({
          type: 'warning',
          title: 'Archivo XML faltante',
          text: `Debe adjuntar un archivo XML para el día ${index + 1} (${tipo})`,
          confirmButtonText: 'Entendido'
        });
        throw new Error(`Falta archivo XML en Día ${index + 1} (${tipo})`);
      }

      const uuid = inputXML.getAttribute('data-uuid');
      const datosFactura = datosFacturas[uuid];

      const itemIndex = `${index}-${i}`; // Clave única por fila-columna (día-comida)

      comprobantesData.append(`comprobantes[${itemIndex}][fecha]`, fecha);
      comprobantesData.append(`comprobantes[${itemIndex}][tipo]`, tipo);
      comprobantesData.append(`comprobantes[${itemIndex}][comentario]`, comentario);
      comprobantesData.append(`comprobantes[${itemIndex}][xml]`, inputXML.files[0]);

      // Datos del XML procesado
      comprobantesData.append(`comprobantes[${itemIndex}][uuid]`, datosFactura.uuid);
      comprobantesData.append(`comprobantes[${itemIndex}][rfcEmisor]`, datosFactura.rfcEmisor);
      comprobantesData.append(`comprobantes[${itemIndex}][rfcReceptor]`, datosFactura.rfcReceptor);
      comprobantesData.append(`comprobantes[${itemIndex}][subtotal]`, datosFactura.subtotal);
      comprobantesData.append(`comprobantes[${itemIndex}][total]`, datosFactura.total);
      comprobantesData.append(`comprobantes[${itemIndex}][fechaFactura]`, datosFactura.fecha);

      if (inputPDF.files.length > 0) {
        comprobantesData.append(`comprobantes[${itemIndex}][pdf]`, inputPDF.files[0]);
      }
    }
  });

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
      $('#modalFormAddComprobantes').modal('hide');
      facturasAdjuntadas.clear();
      Object.keys(datosFacturas).forEach(key => delete datosFacturas[key]);
      contenedor.innerHTML = '';
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
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

