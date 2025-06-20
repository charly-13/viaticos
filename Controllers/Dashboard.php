<?php

class Dashboard extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
	}

	public function dashboard()
	{
		if (empty($_SESSION['login'])) {
			header("Location: " . base_url() . "/logout");
			exit();
		}

		$data['page_id'] = 2;
		$data['page_tag'] = "Dashboard - Viáticos ";
		$data['page_title'] = "Dashboard - viáticos ";
		$data['page_name'] = "dashboard";
		$data['page_functions_js'] = "functions_dashboard.js";


		if ($_SESSION['userData']['id_rol'] === '3') {
			$data['solicitudesPendientes'] = $this->model->solicitudesPendientesGerentesGenerales();
		} else if ($_SESSION['userData']['id_rol'] === '4') {
			$data['solicitudesPendientes'] = $this->model->solicitudesPendientesDirectores();
		}
		//$data['solicitudesPendientes'] = $this->model->solicitudesPendientesGerentes();


		if ($_SESSION['userData']['id_rol'] === '3' || $_SESSION['userData']['id_rol'] === '4' || $_SESSION['userData']['id_rol'] === '5') {

			$this->views->getView($this, "dashboardDirectores", $data);
		} else {
			$this->views->getView($this, "dashboard", $data);
		}
		//$this->views->getView($this,"dashboard",$data);

	}




	public function tipoPagoMes()
	{
		if ($_POST) {
			$grafica = "tipoPagoMes";
			$nFecha = str_replace(" ", "", $_POST['fecha']);
			$arrFecha = explode('-', $nFecha);
			$mes = $arrFecha[0];
			$anio = $arrFecha[1];
			$pagos = $this->model->selectPagosMes($anio, $mes);
			$script = getFile("Template/Modals/graficas", $pagos);
			echo $script;
			die();
		}
	}
	public function ventasMes()
	{
		if ($_POST) {
			$grafica = "ventasMes";
			$nFecha = str_replace(" ", "", $_POST['fecha']);
			$arrFecha = explode('-', $nFecha);
			$mes = $arrFecha[0];
			$anio = $arrFecha[1];
			$pagos = $this->model->selectVentasMes($anio, $mes);
			$script = getFile("Template/Modals/graficas", $pagos);
			echo $script;
			die();
		}
	}
	public function ventasAnio()
	{
		if ($_POST) {
			$grafica = "ventasAnio";
			$anio = intval($_POST['anio']);
			$pagos = $this->model->selectVentasAnio($anio);
			$script = getFile("Template/Modals/graficas", $pagos);
			echo $script;
			die();
		}
	}
}
