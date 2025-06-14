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
				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' ' . $btnComprobante . '</div>';
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
}
