
<?php
	class Ccostos extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
				die();
			}
			//getPermisos(MCATEGORIAS);
		}

		public function Ccostos()
		{
			if(empty($_SESSION['login'])){
				header("Location: " . base_url() . "/logout");
            exit();
			}
			$data['page_tag'] = "Centros de Costo";
			$data['page_title'] = "Centros  <small>de Costo</small>";
			$data['page_name'] = "ccostos";
			$data['page_functions_js'] = "functions_ccostos.js";
			$this->views->getView($this,"ccostos",$data);
		}

		public function setCcostos(){
         //dep($_POST);
			if($_POST){
				if(empty($_POST['txtNombre']) || empty($_POST['listEmpresa']) || empty($_POST['listDireccion']) || empty($_POST['listArea']) || empty($_POST['txtPresupuestoAnual']) || empty($_POST['txtPresupuestoMensual']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$intIdcentero = intval($_POST['idcentro']);
					$strNombre =  strClean($_POST['txtNombre']);
					$intEmpresa = intval($_POST['listEmpresa']);
					$intDireccion = intval($_POST['listDireccion']);
                    $intArea = intval($_POST['listArea']);
					// $strResposable = strClean($_POST['txtResponsable']);
                    $strPresupuestoAnual = $_POST['txtPresupuestoAnual'];
                    $strPresupuestoMensual = $_POST['txtPresupuestoMensual'];
                    $strFechacreacion = strClean($_POST['fechacreacion']);
                    $intEstado = intval(1);
                    $intCreado_por = intval($_POST['creado_por']);
                    $intActualizado_por = intval($_POST['actualizado_por']);

					$request_ccosto = "";

					if($intIdcentero == 0)
					{
						//Crear
							$request_ccosto = $this->model->inserCentroCosto($strNombre, $intEmpresa, $intDireccion, $intArea, $strPresupuestoAnual, $strPresupuestoMensual, $strFechacreacion, $intEstado, $intCreado_por, $intActualizado_por);
							$option = 1;
						
					}else{
						//Actualizar
							$request_ccosto = $this->model->updateCentroCosto($intIdcentero,$strNombre, $intEmpresa, $intDireccion, $intArea, $strPresupuestoAnual, $strPresupuestoMensual, $strFechacreacion, $intEstado, $intCreado_por, $intActualizado_por);
							$option = 2;
						
					}
					if($request_ccosto > 0 )
					{
						if($option == 1)
						{
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
							
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
							
						}
					}else if($request_ccosto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! La categoría ya existe.');
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getCcostos()
		{
		
				$arrData = $this->model->selectCcostos();
				for ($i=0; $i < count($arrData); $i++) {
					$btnView = '';
					$btnEdit = '';
					$btnDelete = '';

					if($arrData[$i]['estado'] == 1)
					{
						$arrData[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					
						$btnView = '<button class="btn btn-info btn-sm btn-compact" onClick="fntViewInfo('.$arrData[$i]['idcentro'].')" title="Ver Centro de Costo"><i class="far fa-eye"></i></button>';
					
					
						$btnEdit = '<button class="btn btn-primary  btn-sm btn-compact" onClick="fntEditInfo(this,'.$arrData[$i]['idcentro'].')" title="Editar Centro de Costo"><i class="fas fa-pencil-alt"></i></button>';
					
						
						$btnDelete = '<button class="btn btn-danger btn-sm btn-compact" onClick="fntDelInfo('.$arrData[$i]['idcentro'].')" title="Eliminar Centro de Costo"><i class="far fa-trash-alt"></i></button>';
					
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			
			die();
		}

		public function getCcosto($idccosto)
		{

				$intIdccosto = intval($idccosto);
                
				if($intIdccosto > 0)
				{
					$arrData = $this->model->selectCcosto($intIdccosto);
                 
					if(empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			
			die();
		}

		public function delCcosto()
		{
			if($_POST){
			
					$intIdccosto = intval($_POST['idcentro']);
					$requestDelete = $this->model->deleteCcosto($intIdccosto);
					if($requestDelete == 'ok')
					{
						$arrResponse = array('status' => true, 'msg' => 'Centro de costo eliminado correctamente.');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No se puede eliminar un centro de costo asociado a una solicitud.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Ocurrió un error al eliminar el centro de costo.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				
			}
			die();
		} 
 
		        		public function getSelectCentrosCosto($session_id_area){
                $session_id_area = intval($session_id_area);
			//$htmlOptions = "";
			$htmlOptionsC = '<option value="0">-- Seleccione --</option>';
			$arrData = $this->model->selectCcostosArea($session_id_area);
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					
					$htmlOptionsC .= '<option value="'.$arrData[$i]['idcentro'].'">'.$arrData[$i]['nombre_centro'].'</option>';
					
				}
			}
			echo $htmlOptionsC;
			die();	 
		}

		public function getSelectEmpresas(){

						//$htmlOptions = "";
			$htmlOptionsA = '<option value="0">-- Seleccione --</option>';
			$arrData = $this->model->selectEmpresas();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					
					$htmlOptionsA .= '<option value="'.$arrData[$i]['id_empresa'].'">'.$arrData[$i]['nombre_empresa'].'</option>';
					
				}
			}
			echo $htmlOptionsA;
			die();	

		}


			public function getSelectDirecciones($idEmpresa){

						//$htmlOptions = "";
			$htmlOptionsD = '<option value="0">-- Seleccione --</option>';
			$arrDataD = $this->model->selectDirecciones($idEmpresa);
			if(count($arrDataD) > 0 ){
				for ($i=0; $i < count($arrDataD); $i++) { 
					
					$htmlOptionsD .= '<option value="'.$arrDataD[$i]['id_direccion'].'">'.$arrDataD[$i]['nombre_direccion'].'</option>';
					
				}
			}
			echo $htmlOptionsD;
			die();	

		}

					public function getSelectAreas($idDireccion){

						//$htmlOptions = "";
			$htmlOptionsA = '<option value="0">-- Seleccione --</option>';
			$arrDataD = $this->model->selectAreas($idDireccion);
			if(count($arrDataD) > 0 ){
				for ($i=0; $i < count($arrDataD); $i++) { 
					
					$htmlOptionsA .= '<option value="'.$arrDataD[$i]['id_area'].'">'.$arrDataD[$i]['nombre_area'].'</option>';
					
				}
			}
			echo $htmlOptionsA;
			die();	

		}


		// public  function getSaldoDisponible($idcentro){
		
		// 		$intIdcentro = intval($idcentro);
		// 		if($intIdcentro > 0)
		// 		{
		// 			$arrData = $this->model->select($intIdcentro);
		// 			if(empty($arrData))
		// 			{
		// 				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
		// 			}else{
		// 				$arrData['url_portada'] = media().'/images/uploads/'.$arrData['portada'];
		// 				$arrResponse = array('status' => true, 'data' => $arrData);
		// 			}
		// 			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		// 		}
		// 	die();

		// }

	}


 ?>