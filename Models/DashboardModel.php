<?php
class DashboardModel extends Mysql
{
	public function __construct()
	{
		parent::__construct();
	}

	public function cantUsuarios()
	{
		$sql = "SELECT COUNT(*) as total FROM persona WHERE status != 0";
		$request = $this->select($sql);
		$total = $request['total'];
		return $total;
	}
	public function cantClientes()
	{
		$sql = "SELECT COUNT(*) as total FROM persona WHERE status != 0 AND rolid = " . RCLIENTES;
		$request = $this->select($sql);
		$total = $request['total'];
		return $total;
	}
	public function cantProductos()
	{
		$sql = "SELECT COUNT(*) as total FROM producto WHERE status != 0 ";
		$request = $this->select($sql);
		$total = $request['total'];
		return $total;
	}
	public function cantPedidos()
	{
		$rolid = $_SESSION['userData']['idrol'];
		$idUser = $_SESSION['userData']['idpersona'];
		$where = "";
		if ($rolid == RCLIENTES) {
			$where = " WHERE personaid = " . $idUser;
		}

		$sql = "SELECT COUNT(*) as total FROM pedido " . $where;
		$request = $this->select($sql);
		$total = $request['total'];
		return $total;
	}
	public function lastOrders()
	{
		$rolid = $_SESSION['userData']['idrol'];
		$idUser = $_SESSION['userData']['idpersona'];
		$where = "";
		if ($rolid == RCLIENTES) {
			$where = " WHERE p.personaid = " . $idUser;
		}

		$sql = "SELECT p.idpedido, CONCAT(pr.nombres,' ',pr.apellidos) as nombre, p.monto, p.status 
					FROM pedido p
					INNER JOIN persona pr
					ON p.personaid = pr.idpersona
					$where
					ORDER BY p.idpedido DESC LIMIT 10 ";
		$request = $this->select_all($sql);
		return $request;
	}


public function solicitudesPendientesGerentesGenerales()
{
	$rolid = $_SESSION['userData']['id_rol'];
	$idUser = $_SESSION['userData']['id_colaborador'];

	// Solo ejecutamos la consulta SI el usuario es GERENTE (rol 3)
	if ($rolid != 3) {
		return []; // No es gerente → devolver un array vacío → no muestra nada
	}

	$where = "WHERE vg.idjefedirecto = $idUser AND vg.estado = 2";

	$sql = "SELECT 
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				c.id_colaborador,
				c.nombre_1,
				c.apellido_paterno,
				c.apellido_materno,
                
				us.id_usuario ,
				us.correo 
			FROM viaticos_generales AS vg
			INNER JOIN colaboradores AS c ON vg.idjefedirecto = c.id_colaborador
			INNER JOIN usuarios as us on vg.usuarioid = us.id_usuario 
			$where
			ORDER BY vg.fechacreacion DESC";

	$request = $this->select_all($sql);
	return $request;
}

public function solicitudesPendientesDirectores()
{
	$rolid = $_SESSION['userData']['id_rol'];
	$idUser = $_SESSION['userData']['id_colaborador'];

	// Solo ejecutamos la consulta SI el usuario es DIRECTOR (rol 4)
	if ($rolid != 4) {
		return []; // No es director → devolver un array vacío → no muestra nada
	}

	$where = "WHERE 
		(vg.idjefedirectosuperior = $idUser AND vg.estado = 5)
		OR
		(vg.idjefedirecto = $idUser AND vg.estado = 2)";

	$sql = "SELECT DISTINCT
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				csup.nombre_1 as nombre_sup,
				csup.apellido_paterno as apellido_paterno_sup,
				csup.apellido_materno as apellido_materno_sup,
				cdir.nombre_1 as nombre_dir,
				cdir.apellido_paterno as apellido_paterno_dir,
				cdir.apellido_materno as apellido_materno_dir,
				us.id_usuario,
				us.correo 
			FROM viaticos_generales AS vg
			LEFT JOIN colaboradores AS csup ON vg.idjefedirectosuperior = csup.id_colaborador
			LEFT JOIN colaboradores AS cdir ON vg.idjefedirecto = cdir.id_colaborador
			INNER JOIN usuarios AS us ON vg.usuarioid = us.id_usuario
			$where
			ORDER BY vg.fechacreacion DESC";

	$request = $this->select_all($sql);
	return $request;
}




public function solicitudesPendientesCobranza()
{


	$sql = "SELECT DISTINCT
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				us.correo
			FROM viaticos_generales AS vg
			INNER JOIN usuarios AS us ON vg.usuarioid = us.id_usuario
			WHERE vg.estado = 8
			ORDER BY vg.fechacreacion DESC";

	$request = $this->select_all($sql);
	return $request;
}


public function solicitudesPorArea()
{
	$rolid = $_SESSION['userData']['id_rol'];
	$idUser = $_SESSION['userData']['id_colaborador'];

	// Solo ejecutamos la consulta SI el usuario es DIRECTOR (rol 4)
	// if ($rolid != 4) {
	// 	return []; // No es director → devolver un array vacío → no muestra nada
	// }

	$where = "WHERE 
		((vg.idjefedirectosuperior = $idUser) AND (vg.estado = 9 OR vg.estado = 10))
		OR
		((vg.idjefedirecto = $idUser) AND (vg.estado = 9 OR vg.estado = 10))";

	$sql = "SELECT DISTINCT
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				csup.nombre_1 as nombre_sup,
				csup.apellido_paterno as apellido_paterno_sup,
				csup.apellido_materno as apellido_materno_sup,
				cdir.nombre_1 as nombre_dir,
				cdir.apellido_paterno as apellido_paterno_dir,
				cdir.apellido_materno as apellido_materno_dir,
				us.id_usuario,
				us.correo 
			FROM viaticos_generales AS vg
			LEFT JOIN colaboradores AS csup ON vg.idjefedirectosuperior = csup.id_colaborador
			LEFT JOIN colaboradores AS cdir ON vg.idjefedirecto = cdir.id_colaborador
			INNER JOIN usuarios AS us ON vg.usuarioid = us.id_usuario
			$where
			ORDER BY vg.fechacreacion DESC
			LIMIT 5";

	$request = $this->select_all($sql);
	return $request;
}




public function solicitudesPendientesDierccionGral()
{
	$rolid = $_SESSION['userData']['id_rol'];
	$idUser = $_SESSION['userData']['id_colaborador'];

	// Solo ejecutamos la consulta SI el usuario es DIRECTOR (rol 4)
	// if ($rolid != 4) {
	// 	return []; // No es director → devolver un array vacío → no muestra nada
	// }

	$where = "WHERE vg.estado = 2 AND csup.id_rol = 3";

	$sql = "SELECT
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				us.id_usuario,
				us.correo, 
				us.id_colaborador, 
				col.id_colaborador,
				col.id_rol
			FROM viaticos_generales AS vg
			INNER JOIN usuarios AS us ON vg.usuarioid = us.id_usuario
			INNER JOIN colaboradores as col ON us.id_colaborador = col.id_colaborador 
			WHERE vg.estado = 2 AND col.id_rol = 4
			ORDER BY vg.fechacreacion DESC
			LIMIT 5";

	$request = $this->select_all($sql);
	return $request;
}

public function mostrarTodasSolicitudes()
{
	$rolid = $_SESSION['userData']['id_rol'];
	$idUser = $_SESSION['userData']['id_colaborador'];

	// Solo ejecutamos la consulta SI el usuario es DIRECTOR (rol 4)
	// if ($rolid != 4) {
	// 	return []; // No es director → devolver un array vacío → no muestra nada
	// }

	$where = "WHERE vg.estado = 10";

	$sql = "SELECT
				vg.idviatico,
				vg.codigo_solicitud,
				vg.usuarioid,
				vg.idjefedirecto,
				vg.idjefedirectosuperior,
				vg.nombreusuario,
				vg.centrocostoid,
				vg.fecha_salida,
				vg.fecha_regreso,
				vg.motivo,
				vg.descripcion,
				vg.lugar_destino,
				vg.fechacreacion,
				vg.estado,
				vg.actualizado_por,
				vg.fechaactualizacion,
				vg.total,
				vg.dias,
				vg.comentariosjefatura,
				vg.fechajefatura,
				vg.comentariosjefaturasup,
				vg.fechajefaturasup,
				vg.comentarioscompras,
				vg.fechacompras,
				us.id_usuario,
				us.correo, 
				us.id_colaborador, 
				col.id_colaborador,
				col.id_rol
			FROM viaticos_generales AS vg
			INNER JOIN usuarios AS us ON vg.usuarioid = us.id_usuario
			INNER JOIN colaboradores as col ON us.id_colaborador = col.id_colaborador 
			WHERE vg.estado = 10
			ORDER BY vg.fechacreacion DESC
			LIMIT 5";

	$request = $this->select_all($sql);
	return $request;
}








	public function selectPagosMes(int $anio, int $mes)
	{

		$sql = "SELECT p.tipopagoid, tp.tipopago, COUNT(p.tipopagoid) as cantidad, SUM(p.monto) as total 
					FROM pedido p 
					INNER JOIN tipopago tp 
					ON p.tipopagoid = tp.idtipopago 
					WHERE MONTH(p.fecha) = $mes AND YEAR(p.fecha) = $anio GROUP BY tipopagoid";
		$pagos = $this->select_all($sql);
		$meses = Meses();
		$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes - 1)], 'tipospago' => $pagos);
		return $arrData;
	}
	public function selectVentasMes(int $anio, int $mes)
	{
		$rolid = $_SESSION['userData']['idrol'];
		$idUser = $_SESSION['userData']['idpersona'];
		$where = "";
		if ($rolid == RCLIENTES) {
			$where = " AND personaid = " . $idUser;
		}

		$totalVentasMes = 0;
		$arrVentaDias = array();
		$dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		$n_dia = 1;
		for ($i = 0; $i < $dias; $i++) {
			$date = date_create($anio . "-" . $mes . "-" . $n_dia);
			$fechaVenta = date_format($date, "Y-m-d");
			$sql = "SELECT DAY(fecha) AS dia, COUNT(idpedido) AS cantidad, SUM(monto) AS total 
						FROM pedido 
						WHERE DATE(fecha) = '$fechaVenta' AND status = 'Completo' " . $where;
			$ventaDia = $this->select($sql);
			$ventaDia['dia'] = $n_dia;
			$ventaDia['total'] = $ventaDia['total'] == "" ? 0 : $ventaDia['total'];
			$totalVentasMes += $ventaDia['total'];
			array_push($arrVentaDias, $ventaDia);
			$n_dia++;
		}
		$meses = Meses();
		$arrData = array('anio' => $anio, 'mes' => $meses[intval($mes - 1)], 'total' => $totalVentasMes, 'ventas' => $arrVentaDias);
		return $arrData;
	}
	public function selectVentasAnio(int $anio)
	{
		$arrMVentas = array();
		$arrMeses = Meses();
		for ($i = 1; $i <= 12; $i++) {
			$arrData = array('anio' => '', 'no_mes' => '', 'mes' => '', 'venta' => '');
			$sql = "SELECT $anio AS anio, $i AS mes, SUM(monto) AS venta 
						FROM pedido 
						WHERE MONTH(fecha)= $i AND YEAR(fecha) = $anio AND status = 'Completo' 
						GROUP BY MONTH(fecha) ";
			$ventaMes = $this->select($sql);
			$arrData['mes'] = $arrMeses[$i - 1];
			if (empty($ventaMes)) {
				$arrData['anio'] = $anio;
				$arrData['no_mes'] = $i;
				$arrData['venta'] = 0;
			} else {
				$arrData['anio'] = $ventaMes['anio'];
				$arrData['no_mes'] = $ventaMes['mes'];
				$arrData['venta'] = $ventaMes['venta'];
			}
			array_push($arrMVentas, $arrData);
			# code...
		}
		$arrVentas = array('anio' => $anio, 'meses' => $arrMVentas);
		return $arrVentas;
	}
	public function productosTen()
	{
		$sql = "SELECT * FROM producto WHERE status = 1 ORDER BY idproducto DESC LIMIT 10 ";
		$request = $this->select_all($sql);
		return $request;
	}
}
