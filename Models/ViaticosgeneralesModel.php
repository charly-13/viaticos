<?php

class ViaticosgeneralesModel extends Mysql
{
	public $intIdcategoria;
	public $strCategoria;
	//public $strDescripcion;
	public $intStatus;
	public $strPortada;
	public $strRuta;
	public $intViaticoid;

	public $strCodigosolicitud;
	public $intUsuarioid;
	public $intIdjefedirecto;
	public $intIdjefedirectosuperior;
	public $strNombreusuario;
	public $intlistCentrosCosto;
	public $strFecha_salida;
	public $strFecha_regreso;
	public $strMotivo;
	public $strDescripcion;
	public $strLugardestino;
	public $strFechacreacion;
	public $intEstado;
	public $intActualizadopor;
	public $strTotal;
	public $intDias;
	public $strComentarios;
	public $strRutaDestino;
	public $intConcepto;
	public $intConceptoid;
	public $strRutaxml;
	public $stRrutapdf;
	public $strUuid;
	public $strRfcemisor;
	public $strSubtotal;
	public $strFechafactura;

	public function __construct()
	{
		parent::__construct();
	}

	public function insertViaticosgeneral(string $codigo_solicitud, int $usuarioid, int $idjefedirecto, int $idjefedirectosuperior, string $nombreusuario, int $centrocostoid, string $fecha_salida, string $fecha_regreso, string $motivo, string $descripcion, string $lugar_destino, string $fechacreacion, int $estado, string $actualizado_por, string $total, int $dias)
	{

		$return = 0;
		$this->strCodigosolicitud = $codigo_solicitud;
		$this->intUsuarioid = $usuarioid;
		$this->intIdjefedirecto = $idjefedirecto;
		$this->intIdjefedirectosuperior = $idjefedirectosuperior;
		$this->strNombreusuario = $nombreusuario;
		$this->intlistCentrosCosto = $centrocostoid;
		$this->strFecha_salida = $fecha_salida;
		$this->strFecha_regreso = $fecha_regreso;
		$this->strMotivo = $motivo;
		$this->strDescripcion = $descripcion;
		$this->strLugardestino = $lugar_destino;
		$this->strFechacreacion = $fechacreacion;
		$this->intEstado = $estado;
		$this->intActualizadopor = $actualizado_por;
		$this->strTotal = $total;
		$this->intDias = $dias;

		// $sql = "SELECT * FROM  viaticos_generales WHERE usuarioid = '{$this->intUsuarioid}' ";
		// $request = $this->select_all($sql);

		// if(empty($request))
		// {
		$query_insert  = "INSERT INTO viaticos_generales(codigo_solicitud,usuarioid,idjefedirecto,idjefedirectosuperior,nombreusuario,centrocostoid,fecha_salida,fecha_regreso,motivo,descripcion,lugar_destino,fechacreacion,estado,actualizado_por,total,dias) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$arrData = array(
			$this->strCodigosolicitud,
			$this->intUsuarioid,
			$this->intIdjefedirecto,
			$this->intIdjefedirectosuperior,
			$this->strNombreusuario,
			$this->intlistCentrosCosto,
			$this->strFecha_salida,
			$this->strFecha_regreso,
			$this->strMotivo,
			$this->strDescripcion,
			$this->strLugardestino,
			$this->strFechacreacion,
			$this->intEstado,
			$this->intActualizadopor,
			$this->strTotal,
			$this->intDias
		);
		$request_insert = $this->insert($query_insert, $arrData);
		$return = $request_insert;
		// }else{
		// 	$return = "exist";
		// }
		return $return;
	}

	public function generarCodigoSolicitud()
	{
		$fecha = date('Ymd'); // 20250606
		$prefijo = 'SOL-VG' . $fecha . '-';

		$sql = "SELECT codigo_solicitud FROM viaticos_generales 
            WHERE codigo_solicitud LIKE '$prefijo%' 
            ORDER BY codigo_solicitud DESC 
            LIMIT 1";

		$result = $this->select($sql);
		$numero = 1;

		if (!empty($result)) {
			$ultimoCodigo = $result['codigo_solicitud'];
			$ultimoNumero = (int)substr($ultimoCodigo, -4);
			$numero = $ultimoNumero + 1;
		}

		return $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT); // VIA-20250606-0004

	}

	public function insertDetalleViaticosgeneral(int $viaticoid, string $conceptosJson,int $dias)
	{

		$return = 0;
		$this->intViaticoid = $viaticoid;

		// Decodificamos los conceptos desde JSON a array PHP
		$conceptos = json_decode($conceptosJson, true);
		$this->intDias = $dias;

		if (!is_array($conceptos)) {
			return 0; // Error si el JSON no es válido
		}

		// Preparamos query
		$query_insert = "INSERT INTO viaticos_conceptos (viaticoid, concepto, solicituddiaria, subtotal, comentario, dias) VALUES (?, ?, ?, ?, ?, ?)";

		foreach ($conceptos as $item) {	
			$concepto = $item['concepto'] ?? '';
			$solicituddiaria = $item['solicituddiaria'] ?? 0;
			$subtotal = $item['subTotal'] ?? 0;
			$comentario = $item['comentario'] ?? '';

			$arrData = array(
				$this->intViaticoid,
				$concepto,
				$solicituddiaria,
				$subtotal,
				$comentario,
				$this->intDias,
			);

			$request_insert = $this->insert($query_insert, $arrData);
		}

		return 1; // O podrías retornar el total insertado si deseas

	}



	public function selectViaticos(int $id_usuario)
	{
		
		$this->intUsuarioid = $id_usuario;
		$sql = "SELECT 
            vg.idviatico,
            vg.codigo_solicitud,
			 vg.usuarioid,
            vg.nombreusuario,
			vg.fecha_salida,
			vg.fecha_regreso,
			vg.motivo,
			vg.lugar_destino,
			vg.fechacreacion,
			vg.estado  AS estado_viatico,
            vg.actualizado_por AS actualizado_por_viatico,
			vg.fechaactualizacion,
			vg.total,
            cc.estado AS estado_centro,
			cc.nombre AS nombre_centro
        FROM viaticos_generales vg
        LEFT JOIN centros_costo cc ON vg.centrocostoid = cc.idcentro
        WHERE vg.estado != 0 AND  vg.usuarioid = $this->intUsuarioid";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectCategoria(int $idcategoria)
	{
		$this->intIdcategoria = $idcategoria;
		$sql = "SELECT * FROM categoria
					WHERE idcategoria = $this->intIdcategoria";
		$request = $this->select($sql);
		return $request;
	}

	public function selectSolicitud(int $viaticoid)
	{
		//$this->intIdcategoria = $idcategoria;
		$this->intViaticoid = $viaticoid;
		$sql = "SELECT vg.idviatico,
            vg.codigo_solicitud,
            vg.nombreusuario,
			vg.fecha_salida,
			vg.fecha_regreso,
			vg.motivo,
			vg.lugar_destino,
			vg.fechacreacion,
			vg.estado  AS estado_viatico,
            vg.actualizado_por AS actualizado_por_viatico,
			vg.fechaactualizacion,
			vg.total,
			vg.descripcion,
			vg.usuarioid,
			vg.comentariosjefatura,
			vg.fechajefatura,
			vg.comentariosjefaturasup,
			vg.fechajefaturasup,
            u.id_colaborador,
            c.id_colaborador,
			c.nombre_1,
			c.apellido_paterno,
			c.apellido_materno,
			c.telefono_personal,
			c.email_corporativo,
			c.id_rol
			FROM viaticos_generales as vg
			INNER JOIN usuarios AS u 
			ON vg.usuarioid = u.id_usuario
			INNER JOIN colaboradores as c
			ON u.id_colaborador = c.id_colaborador
					WHERE vg.idviatico = $this->intViaticoid";
		$requestViaticos = $this->select($sql);

		$sql_detalle = "SELECT vc.idconcepto,
						                    vc.viaticoid,
											vc.concepto,
											vc.solicituddiaria,
											vc.subtotal,
											vc.comentario,
											vc.dias
									FROM viaticos_conceptos vc
									WHERE vc.viaticoid = $viaticoid";
		$requestProductos = $this->select_all($sql_detalle);

		$request = array(
			'viaticos' => $requestViaticos,
			'detalle' => $requestProductos
		);

		return $request;
	}


		public function selectSolicitudComprobante(int $viaticoid)
	{
		//$this->intIdcategoria = $idcategoria;
		$this->intViaticoid = $viaticoid;
		$sql = "SELECT vg.idviatico,
            vg.codigo_solicitud,
            vg.nombreusuario,
			vg.fecha_salida,
			vg.fecha_regreso,
			vg.motivo,
			vg.lugar_destino,
			vg.fechacreacion,
			vg.estado  AS estado_viatico,
            vg.actualizado_por AS actualizado_por_viatico,
			vg.fechaactualizacion,
			vg.total,
			vg.descripcion,
			vg.usuarioid,
			vg.comentariosjefatura,
			vg.fechajefatura,
            u.id_colaborador,
            c.id_colaborador,
			c.nombre_1,
			c.apellido_paterno,
			c.apellido_materno,
			c.telefono_personal,
			c.email_corporativo
			FROM viaticos_generales as vg
			INNER JOIN usuarios AS u 
			ON vg.usuarioid = u.id_usuario
			INNER JOIN colaboradores as c
			ON u.id_colaborador = c.id_colaborador
					WHERE vg.idviatico = $this->intViaticoid;
";
		$requestViaticos = $this->select($sql);

		$sql_detalle = "SELECT vc.idconcepto,
						                    vc.viaticoid,
											vc.concepto,
											vc.solicituddiaria,
											vc.subtotal,
											vc.comentario,
											vc.dias,
											vc.tiene_comprobante
									FROM viaticos_conceptos vc
									WHERE vc.viaticoid = $viaticoid";
		$requestProductos = $this->select_all($sql_detalle);

		$request = array(
			'viaticos' => $requestViaticos,
			'detalle' => $requestProductos
		);

		return $request;
	}

	

		public function updateConceptoComprobante(int $idconcepto)
	{
		$this->intConceptoid = $idconcepto ;
	


			$sql = "UPDATE viaticos_conceptos SET tiene_comprobante	 = ? WHERE idconcepto = $this->intConceptoid ";
			$arrData = array(2);
			$request = $this->update($sql, $arrData);

		return $request;
	}

	public function gestionJefatura(int $dviatico , int $estado, string $comentariosjefatura)
	{
		$this->intViaticoid = $dviatico ;
		$this->intEstado = $estado;
		$this->strComentarios = $comentariosjefatura;


			$sql = "UPDATE viaticos_generales SET estado = ?, comentariosjefatura = ?,  fechajefatura = NOW() WHERE idviatico = $this->intViaticoid ";
			$arrData = array(
				$this->intEstado,
				$this->strComentarios
			);
			$request = $this->update($sql, $arrData);

		return $request;
	}

	

		public function gestionJefaturaSuperior(int $dviatico , int $estado, string $comentariosjefatura)
	{
		$this->intViaticoid = $dviatico ;
		$this->intEstado = $estado;
		$this->strComentarios = $comentariosjefatura;


			$sql = "UPDATE viaticos_generales SET estado = ?, comentariosjefaturasup = ?,  fechajefaturasup = NOW() WHERE idviatico = $this->intViaticoid ";
			$arrData = array(
				$this->intEstado,
				$this->strComentarios
			);
			$request = $this->update($sql, $arrData);

		return $request;
	}

		

			public function gestionCompras(int $dviatico , int $estado, string $comentariosCompras, string $rutadestino)
	{
		$this->intViaticoid = $dviatico ;
		$this->intEstado = $estado;
		$this->strComentarios = $comentariosCompras;
		$this->strRutaDestino = $rutadestino;


			$sql = "UPDATE viaticos_generales SET estado = ?, comentarioscompras = ?,  fechacompras = NOW(),  rutadestino = ? WHERE idviatico = $this->intViaticoid ";
			$arrData = array(
				$this->intEstado,
				$this->strComentarios,
				$this->strRutaDestino
			);
			$request = $this->update($sql, $arrData);

		return $request;
	}

	

	
	public function inserComprobantesViaticos(int $concepto, int $viaticoid, string $rutaxml, string $rutapdf, string $fecha, string $comentarios, string $uuid, string $rfcemisor , $subtotal, $total, $fechafactura)
	{

		$return = 0;
		$this->intConcepto = $concepto;
		$this->intViaticoid = $viaticoid;
		$this->strRutaxml = $rutaxml;
		$this->stRrutapdf = $rutapdf;
		$this->strFecha_salida = $fecha;
		$this->strComentarios = $comentarios;
		$this->strUuid = $uuid;
		$this->strRfcemisor = $rfcemisor;
		$this->strSubtotal = $subtotal;
		$this->strTotal = $total;
		$this->strFechafactura = $fechafactura;


		// $sql = "SELECT * FROM  viaticos_generales WHERE usuarioid = '{$this->intUsuarioid}' ";
		// $request = $this->select_all($sql);

		// if(empty($request))
		// {
		$query_insert  = "INSERT INTO comprobantes_viaticos_generales(conceptoid,viaticoid,rutaxml,rutapdf,fecha,comentarios,uuid,rfcemisor,subtotal,total,fechafactura) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
		$arrData = array(
			$this->intConcepto,
			$this->intViaticoid,
			$this->strRutaxml,
			$this->stRrutapdf,
			$this->strFecha_salida,
			$this->strComentarios,
			$this->strUuid,
			$this->strRfcemisor,
			$this->strSubtotal,
			$this->strTotal,
			$this->strFechafactura
		);
		$request_insert = $this->insert($query_insert, $arrData);
		$return = $request_insert;
		// }else{
		// 	$return = "exist";
		// }
		return $return;
	}

		public function insertTotalesFactura(int $concepto, int $viaticoid, string $total)
	{

		$return = 0;
		$this->intConcepto = $concepto;
		$this->intViaticoid = $viaticoid;
		$this->strTotal = $total;



		// $sql = "SELECT * FROM  viaticos_generales WHERE usuarioid = '{$this->intUsuarioid}' ";
		// $request = $this->select_all($sql);

		// if(empty($request))
		// {
		$query_insert  = "INSERT INTO totales_vgfacturas_conceptos(conceptoid,viaticoid,total,fecha_subida) VALUES(?,?,?, NOW())";
		$arrData = array(
			$this->intConcepto,
			$this->intViaticoid,
	        $this->strTotal,
		);
		$request_insert = $this->insert($query_insert, $arrData);
		$return = $request_insert;
		// }else{
		// 	$return = "exist";
		// }
		return $return;
	}
	

	







}
