<?php
class Viaticosgenerales extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		//session_regenerate_id(true);
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
	}

	public function Viaticosgenerales()
	{
		if (empty($_SESSION['login'])) {
			header("Location: " . base_url() . "/logout");
			exit();
		}
		$data['page_tag'] = "Viáticos Generales";
		$data['page_title'] = "Solicitudes  <small>Viáticos Generales</small>";
		$data['page_name'] = "Viáticos Generales";
		$data['page_functions_js'] = "functions_visticosgenerales.js";
		$this->views->getView($this, "viaticosgenerales", $data);
	}

	public function setViaticogeneral()
	{

		$fecha = date('Ymd'); // 20250606
		$prefijo = 'SOL-' . $fecha . '-';

		if ($_POST) {
			if (empty($_POST['usuarioid']) || empty($_POST['nombreusuario']) || empty($_POST['listCentrosCosto']) || empty($_POST['motivo']) || empty($_POST['fecha_salida']) || empty($_POST['fecha_salida']) || empty($_POST['fecha_regreso']) || empty($_POST['lugar_destino'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdviatico = intval($_POST['idviatico']);
				$intUsuarioid = intval($_POST['usuarioid']);
				$strNombreusuario =  strClean($_POST['nombreusuario']);
				$intlistCentrosCosto = intval($_POST['listCentrosCosto']);
				$strFecha_salida =  strClean($_POST['fecha_salida']);
				$strFecha_regreso =  strClean($_POST['fecha_regreso']);
				$strMotivo =  strClean($_POST['motivo']);
				$strDescripcion =  strClean($_POST['txtDescripcion']);
				$strLugardestino =  strClean($_POST['lugar_destino']);
				$strFechacreacion = strClean($_POST['fechacreacion']);

				$intEstado = intval(2);
				$intActualizadopor = intval($_POST['usuarioid']);
				$strTotal = $_POST['total_limpio'];
				//$strEmailJefeDirecto = strClean($_POST['email_jefe_directo']);//En productivo de descomenta 
				$strEmailJefeDirecto = strClean('carlosbunti97@gmail.com');
				$dias = intval($_POST['inputDias']);



				$conceptosJson = $_POST['conceptos'];

				$request_viaticos = "";
				$request_detalle_viaticos = "";


				if ($intIdviatico == 0) {

					$codigoSolicitud = $this->model->generarCodigoSolicitud();

					//Crear Viaticos
					$request_viaticos = $this->model->insertViaticosgeneral($codigoSolicitud, $intUsuarioid, $strNombreusuario, $intlistCentrosCosto, $strFecha_salida, $strFecha_regreso, $strMotivo, $strDescripcion, $strLugardestino, $strFechacreacion, $intEstado, $intActualizadopor, $strTotal, $dias);
					//Crear detalle Viaticos
					if ($request_viaticos) {
						$request_detalle_viaticos = $this->model->insertDetalleViaticosgeneral($request_viaticos, $conceptosJson, $dias);
						$option = 1;
						$nombreUsuario = "Carlos Cruz";
						$url_recovery = base_url() . '/viaticosgenerales/solicitud/' . $request_viaticos;
						$correos_copias = "carloscc_1997@outlook.com";

						$dataUsuario = array(
							'nombreUsuario' => $nombreUsuario,
							'email' => $strEmailJefeDirecto,
							'asunto' => 'Nueva Solicitud de Viáticos',
							'url_recovery' => $url_recovery
						);


						$sendEmail = sendMailLocal($dataUsuario, 'mail_new_general_request', $correos_copias);

						if ($sendEmail) {
							$arrResponse = array(
								'status' => true,
								'msg' => 'Se ha enviado un email a el jefe de área correspondido.'
							);
						} else {
							$arrResponse = array(
								'status' => false,
								'msg' => 'No es posible realizar el proceso, intenta más tarde.'
							);
						}
					}
				} else {
					//Actualizar


					$request_viaticos = $this->model->updateCategoria($intIdviatico, $strCategoria, $strDescipcion, $imgPortada, $ruta, $intStatus);
					$option = 2;
				}
				if ($request_viaticos > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($request_viaticos == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! La categoría ya existe.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getViaticos()
	{

		//Se muestran las solicitudes según el usuario que las ha generado.


		$id_usuario = $_SESSION['userData']['id_usuario'];
		$arrData = $this->model->selectViaticos($id_usuario);
		for ($i = 0; $i < count($arrData); $i++) {
			// $btnView = '';
			// $btnComprobante = '';
			$btnDelete = '';
			$estadoViaticoNum = $arrData[$i]['estado_viatico'];


			if ($estadoViaticoNum == 2) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-warning">En revisión</span>';
			} else if ($estadoViaticoNum == 4) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-danger">Rechazada por Jefe Directo</span>';
			} else if ($estadoViaticoNum == 5) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-success">Aprobado por Jefe Directo</span>';
			} else if ($estadoViaticoNum == 7) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-danger">Rechazada por Jefe Superior</span>';
			} else if ($estadoViaticoNum == 8) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-success">Aprobado por Jefe Superior</span>';
			} else if ($estadoViaticoNum == 9) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-success">Rechazada por Compras</span>';
			} else if ($estadoViaticoNum == 10) {
				$arrData[$i]['estado_viatico'] = '<span class="badge badge-success">Solicitud Aprobada</span>';
			}


			// Botones
			$btnView = '<a title="Ver Detalle" href="' . base_url() . '/viaticosgenerales/solicitud/' . $arrData[$i]['idviatico'] . '" target="_blank" class="btn btn-info btn-sm"> <i class="far fa-eye"></i> </a>';

			$btnComprobante = '<a title="Comprobantes" href="' . base_url() . '/viaticosgenerales/comprobantes/' . $arrData[$i]['idviatico'] . '"  class="btn btn-danger btn-sm"> <i class="fas fa-file-pdf"></i> </a>';

			// ✅ Ahora haces la validación usando la variable **numérica**
			if ((int)$estadoViaticoNum === 10) {
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnComprobante . '</div>';
			} else {
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . '</div>';
			}
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);

		die();
	}


	public function solicitud($idviatico)
	{
		if (!is_numeric($idviatico)) {
			header("Location:" . base_url() . '/viaticosgenerales');
		}



		$idpersona = $_SESSION['userData']['id_usuario'];
		// dep($idpersona);
		// if( $_SESSION['userData']['idrol'] == RCLIENTES ){
		// 	$idpersona = $_SESSION['userData']['idpersona'];
		// }


		$data['page_tag'] = "Solicitud - viáticos";
		$data['page_title'] = "SOLICITUD <small>Viáticos</small>";
		$data['page_name'] = "solicitud";
		$data['page_functions_js'] = "functions_aprobaciones.js";
		$data['arrSolicitud'] = $this->model->selectSolicitud($idviatico, $idpersona);
		//dep($data['arrSolicitud']);
		$this->views->getView($this, "solicitud", $data);
	}

	public function comprobantes($idviatico)
	{
		if (!is_numeric($idviatico)) {
			header("Location:" . base_url() . '/viaticosgenerales');
		}



		$idpersona = $_SESSION['userData']['id_usuario'];
		// dep($idpersona);
		// if( $_SESSION['userData']['idrol'] == RCLIENTES ){
		// 	$idpersona = $_SESSION['userData']['idpersona'];
		// }


		$data['page_tag'] = "Comprobantes - viáticos";
		$data['page_title'] = "COMPROBANTES <small>Viáticos</small>";
		$data['page_name'] = "comprobantes";
		$data['page_functions_js'] = "functions_comprobantes.js";
		$data['arrSolicitud'] = $this->model->selectSolicitud($idviatico, $idpersona);
		//dep($data['arrSolicitud']);
		$this->views->getView($this, "comprobantes", $data);
	}

	public function respuestaPorJefatura()
	{

		if ($_POST) {
			if (empty($_POST['idviatico'])  || empty($_POST['listStatus']) || empty($_POST['txtComentarios'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdviatico = intval($_POST['idviatico']);
				$intEstatus = intval($_POST['listStatus']);
				$strComentarios = strClean($_POST['txtComentarios']);
				$correo_solicitante = strClean($_POST['correo_solicitante']);
				//$email_jefe_superior = strClean($_POST['email_jefe_superior']);

				$email_jefe_superior = strClean('carlosbunti97@gmail.com');

				$request_viaticos = "";

				//Actualizar

				$request_viaticos = $this->model->gestionJefatura($intIdviatico, $intEstatus, $strComentarios);


				$url_recovery = base_url() . '/viaticosgenerales/solicitud/' . $request_viaticos;
				$respuestaJefe = "";
				$asunto = "";
				if ($intEstatus == 5) {
					$asunto = "Avance en el proceso de tu solicitud de viáticos";
					$respuestaJefe = "Te informamos que tu solicitud de viáticos ha sido aprobada por tu jefe directo y ha sido enviada al jefe superior para su validación final.";
				} else if ($intEstatus == 4) {
					$asunto = "Solicitud de viáticos rechazada";
					$respuestaJefe = "Lamentamos informarte que tu solicitud de viáticos ha sido rechazada durante el proceso de revisión.";
				}

				if ($request_viaticos > 0) {

					$correos_copias = "carloscc_1997@outlook.com";

					$dataSolicitante = array(
						'email' => $correo_solicitante,
						'asunto' => $asunto,
						'respuesta' => $respuestaJefe,
						'area' => 'Jefe Directo',
						'url_recovery' => $url_recovery
					);


					$sendEmail = sendMailLocal($dataSolicitante, 'mail_respuesta_solicitud_usuario', $correos_copias);

					if ($sendEmail) {
						if ($intEstatus == 5) {
							$arrResponse = array('status' => true, 'msg' => 'Se ha enviado una notificación al jefe superior para su validación final!');

							$dataSolicitudU = array(
								'email' => $email_jefe_superior,
								'asunto' => 'Solicitud de aprobación de viáticos',
								'area' => 'Jefe Superior',
								'url_recovery' => $url_recovery
							);
							$sendEmail = sendMailLocal($dataSolicitudU, 'mail_sol_jefe_superior', $correos_copias);
						} else {
							$arrResponse = array('status' => true, 'msg' => 'El solicitante ha sido notificado por correo sobre el rechazo de su solicitud!');
						}
					}
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible enviar tu información.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function respuestaPorJefaturaSuperior()
	{

		if ($_POST) {
			if (empty($_POST['idviaticoap'])  || empty($_POST['listStatusap']) || empty($_POST['txtComentariosap'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdviatico = intval($_POST['idviaticoap']);
				$intEstatus = intval($_POST['listStatusap']);
				$strComentarios = strClean($_POST['txtComentariosap']);
				$correo_solicitante = strClean($_POST['correo_solicitante_aprob_final']);
				//$email_jefe_superior = strClean($_POST['email_jefe_superior']);

				$email_jefe_superior = strClean('carlosbunti97@gmail.com');
				$email_compras = strClean("carlosbunti97@gmail.com");

				$request_viaticos = "";

				//Actualizar

				$request_viaticos = $this->model->gestionJefaturaSuperior($intIdviatico, $intEstatus, $strComentarios);


				$url_recovery = base_url() . '/viaticosgenerales/solicitud/' . $request_viaticos;
				$respuestaJefe = "";
				$asunto = "";
				if ($intEstatus == 8) {
					$asunto = "Solicitud de viáticos aprobada";
					$respuestaJefe = "Te informamos que tu solicitud de viáticos ha sido aprobada por tu jefe superior y ha sido enviada al área de compras para finalizar el proceso correspondiente.Te pedimos estar atento(a) a cualquier notificación adicional por parte del área encargada.";
				} else if ($intEstatus == 7) {
					$asunto = "Solicitud de viáticos no aprobada";
					$respuestaJefe = "Te informamos que tu solicitud de viáticos no fue aprobada por tu jefe superior, por lo que el proceso ha sido detenido.";
				}

				if ($request_viaticos > 0) {

					$correos_copias = "carloscc_1997@outlook.com";

					$dataSolicitante = array(
						'email' => $correo_solicitante,
						'asunto' => $asunto,
						'respuesta' => $respuestaJefe,
						'area' => 'Jefe Directo',
						'url_recovery' => $url_recovery
					);


					$sendEmail = sendMailLocal($dataSolicitante, 'mail_respuesta_solicitud_usuario', $correos_copias);

					if ($sendEmail) {
						if ($intEstatus == 8) {
							$arrResponse = array('status' => true, 'msg' => '¡Tu solicitud ha sido remitida al área de compras para su gestión y cierre final!');

							$dataSolicituCompras = array(
								'email' => $email_compras,
								'asunto' => 'Solicitud de aprobación de viáticos aprobada',
								'area' => 'compas',
								'url_recovery' => $url_recovery
							);
							$sendEmail = sendMailLocal($dataSolicituCompras, 'mail_solicitud_compras', $correos_copias);
						} else {
							$arrResponse = array('status' => true, 'msg' => 'El solicitante ha sido notificado por correo sobre el rechazo de su solicitud!');
						}
					}
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible enviar tu información.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}



	public function respuestaPorCompras()
	{

		if ($_POST) {
			if (empty($_POST['idviatico_comp'])  || empty($_POST['listStatus_comp'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdviatico = intval($_POST['idviatico_comp']);
				$intEstatus = intval($_POST['listStatus_comp']);
				$strComentarios = strClean($_POST['txtComentarios_comp']);
				$correo_solicitante = strClean($_POST['correo_solicitante_comp']);
				//$email_jefe_superior = strClean($_POST['email_jefe_superior']);

				$email_jefe_superior = strClean('carlosbunti97@gmail.com');
				$email_compras = strClean("carlosbunti97@gmail.com");

				$request_viaticos = "";

				//Actualizar

				$request_viaticos = $this->model->gestionCompras($intIdviatico, $intEstatus, $strComentarios);


				$url_recovery = base_url() . '/viaticosgenerales/solicitud/' . $request_viaticos;
				$respuestaCompras = "";
				$asunto = "";
				if ($intEstatus == 10) {
					$asunto = "Solicitud Finalizada";
					$respuestaCompras = "Te informamos que tu <strong>solicitud de viáticos</strong> ha sido gestionada por el área de compras.</p> <p>¡Te deseamos un excelente viaje! Recuerda que al regresar deberás adjuntar las facturas correspondientes a cada rubro solicitado.";
				} else if ($intEstatus == 9) {
					$asunto = "Solicitud de viáticos no aprobada";
					$respuestaCompras = "Te informamos que tu solicitud de viáticos no fue aprobada por tu jefe superior, por lo que el proceso ha sido detenido.";
				}

				if ($request_viaticos > 0) {

					$correos_copias = "carloscc_1997@outlook.com";

					$dataSolicitante = array(
						'email' => $correo_solicitante,
						'asunto' => $asunto,
						'respuesta' => $respuestaCompras,
						'area' => 'Solicitante',
						'url_recovery' => $url_recovery
					);


					$sendEmail = sendMailLocal($dataSolicitante, 'mail_respuesta_solicitud_usuario', $correos_copias);

					if ($sendEmail) {
						if ($intEstatus == 10) {
							$arrResponse = array('status' => true, 'msg' => 'El solicitante ha sido notificado sobre la aprobación de su solicitud. ¡Gracias!');

							// 	$dataSolicituCompras = array(
							// 	'email' => $email_compras,
							// 	'asunto' => 'Solicitud de aprobación de viáticos aprobada',
							// 	'area' => 'compas',
							// 	'url_recovery' => $url_recovery
							// );
							// 		 $sendEmail = sendMailLocal($dataSolicituCompras, 'mail_solicitud_compras', $correos_copias);
						} else {
							$arrResponse = array('status' => true, 'msg' => 'El solicitante ha sido notificado por correo sobre el rechazo de su solicitud!');
						}
					}
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible enviar tu información.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}



	public function procesarXML()
	{
		header('Content-Type: application/json');

		if ($_FILES['archivoXML']['error'] !== UPLOAD_ERR_OK) {
			echo json_encode(['status' => false, 'message' => 'Error al subir el archivo XML.']);
			return;
		}

		$contenido = file_get_contents($_FILES['archivoXML']['tmp_name']);
		$xml = simplexml_load_string($contenido);

		if (!$xml) {
			echo json_encode(['status' => false, 'message' => 'Error al leer el archivo XML.']);
			return;
		}

		$namespaces = $xml->getNamespaces(true);

		if (isset($namespaces['cfdi'])) {
			$xml->registerXPathNamespace('cfdi', $namespaces['cfdi']);
		}
		if (isset($namespaces['tfd'])) {
			$xml->registerXPathNamespace('tfd', $namespaces['tfd']);
		}

		$comprobante = $xml->xpath('//cfdi:Comprobante')[0] ?? null;
		$emisor = $xml->xpath('//cfdi:Emisor')[0] ?? null;
		$receptor = $xml->xpath('//cfdi:Receptor')[0] ?? null;
		$timbre = $xml->xpath('//cfdi:Complemento/tfd:TimbreFiscalDigital')[0] ?? null;

		if (!$comprobante || !$emisor || !$receptor) {
			echo json_encode(['status' => false, 'message' => 'No se pudo obtener toda la información del comprobante.']);
			return;
		}

		$subtotal = (string) $comprobante['SubTotal'];
		$total = (string) $comprobante['Total'];
		$fecha = (string) $comprobante['Fecha'];            // 📅 Fecha de emisión
		$rfcEmisor = (string) $emisor['Rfc'];               // RFC del emisor
		$rfcReceptor = (string) $receptor['Rfc'];           // RFC del receptor
		$uuid = $timbre ? (string) $timbre['UUID'] : '';    // UUID

		echo json_encode([
			'status' => true,
			'subtotal' => $subtotal,
			'total' => $total,
			'fecha' => $fecha,
			'rfcEmisor' => $rfcEmisor,
			'rfcReceptor' => $rfcReceptor,
			'uuid' => $uuid
		]);
	}


	public function guardarComprobantes()
	{
		dep($_POST);
	}


	public function guardarComprobantess()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$concepto = $_POST['concepto'] ?? '';
			$comprobantes = $_POST['comprobantes'] ?? [];
			$archivos = $_FILES;

			$respuesta = ['status' => false, 'message' => 'Error desconocido', 'debug' => []];

			if (!empty($concepto) && !empty($archivos['comprobantes']['name'])) {
				$numComprobantes = count($archivos['comprobantes']['name']);
				$respuesta['debug'][] = "Número de comprobantes recibidos: $numComprobantes";

				for ($i = 0; $i < $numComprobantes; $i++) {
					$fecha = $_POST['comprobantes'][$i]['fecha'] ?? null;
					$comentario = $_POST['comprobantes'][$i]['comentario'] ?? '';


					    $uuid = $_POST['comprobantes'][$i]['uuid'] ?? '';
                $rfcEmisor = $_POST['comprobantes'][$i]['rfcEmisor'] ?? '';
                $rfcReceptor = $_POST['comprobantes'][$i]['rfcReceptor'] ?? '';
                $subtotal = $_POST['comprobantes'][$i]['subtotal'] ?? '';
                $total = $_POST['comprobantes'][$i]['total'] ?? '';
                $fechaFactura = $_POST['comprobantes'][$i]['fechaFactura'] ?? '';

				$fechaFacturaFormateada = date('Y-m-d H:i:s', strtotime($fechaFactura ));


					$xmlFileName = $archivos['comprobantes']['name'][$i]['xml'] ?? null;
					$xmlTmpName = $archivos['comprobantes']['tmp_name'][$i]['xml'] ?? null;

					$pdfFileName = $archivos['comprobantes']['name'][$i]['pdf'] ?? null;
					$pdfTmpName = $archivos['comprobantes']['tmp_name'][$i]['pdf'] ?? null;

					

					$codigoAleatorio = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
					$fechaHora = date('Ymd_His');

					// Obtener extensión del archivo XML y PDF
					$extensionXML = pathinfo($xmlFileName, PATHINFO_EXTENSION);
					//$extensionPDF = pathinfo($pdfFileName, PATHINFO_EXTENSION);

					// Construir el nuevo nombre SIN el nombre original
					$nombreArchivoXML = $fechaHora . '_' . $codigoAleatorio . '.' . $extensionXML;
					//$nombreArchivoPDF = $fechaHora . '_' . $codigoAleatorio . '.' . $extensionPDF;




					$rutaXML = null;

					if ($xmlTmpName && is_uploaded_file($xmlTmpName)) {
						$rutaXML = 'Assets/uploads/xml/' . $nombreArchivoXML;
						if (!move_uploaded_file($xmlTmpName, $rutaXML)) {
							$respuesta['message'] = "Error al mover el archivo XML para comprobante $i";
							echo json_encode($respuesta);
							exit;
						}
					} else {
						$respuesta['message'] = "Falta o no es válido el archivo XML para comprobante $i";
						echo json_encode($respuesta);
						exit;
					}

					$rutaPDF = '';
					if ($pdfTmpName && is_uploaded_file($pdfTmpName)) {
						//$rutaPDF = 'Assets/uploads/pdf/' . uniqid() . '_' . basename($pdfFileName);
						$extensionPDF = pathinfo($pdfFileName, PATHINFO_EXTENSION);
					$nombreArchivoPDF = $fechaHora . '_' . $codigoAleatorio . '.' . $extensionPDF;
					$rutaPDF =  $nombreArchivoPDF;
						if (!move_uploaded_file($pdfTmpName, $rutaPDF)) {
							$respuesta['message'] = "Error al mover el archivo PDF para comprobante $i";
							echo json_encode($respuesta);
							exit;
						}
					}

					// Guardar en la base de datos:
					try {
						$insertId = $this->model->inserComprobantesViaticos($concepto, $concepto, $nombreArchivoXML, $rutaPDF, $fecha, $comentario, $uuid, $rfcEmisor, $subtotal, $total, $fechaFacturaFormateada);

						if ($insertId) {
							$respuesta['debug'][] = "Comprobante $i guardado en BD con ID $insertId";
						} else {
							$respuesta['message'] = "Error al guardar comprobante $i en BD";
							echo json_encode($respuesta);
							exit;
						}
					} catch (Exception $e) {
						$respuesta['message'] = "Error al guardar comprobante $i en BD: " . $e->getMessage();
						echo json_encode($respuesta);
						exit;
					}
				}

				echo json_encode(['status' => true, 'msg' => 'Datos guardados correctamente.']);
			} else {
				$respuesta['message'] = "Datos incompletos o no se enviaron archivos";
				echo json_encode($respuesta);
				exit;
			}
		}
	}
}
