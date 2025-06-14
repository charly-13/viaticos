<?php

class CcostosModel extends Mysql
{
	public $intIdccosto;
	public $intIdarea;
	public $strNombre;
	public $intEmpresa;
	public $intDireccion;
	public $intArea;
	public $strResposable;
	public $strPresupuestoAnual;
	public $strPresupuestoMensual;

	public $strFechacreacion;
	public $intEstado;
	public $intCreado_por;
	public $intActualizado_por;
	public $intIdempresa;
	public $intIddireccion;

	public function __construct()
	{
		parent::__construct();
	}

	public function inserCentroCosto(string $nombre, int $idempresa, int $iddirecion, int $idarea, string $responsable, string $presupuestoanual, string $presupuestomensual, string $fechacreacion, int $estado, int $creado_por, int $actualizado_por)
	{

		$return = 0;
		$this->strNombre = $nombre;
		$this->intEmpresa = $idempresa;
		$this->intDireccion = $iddirecion;
		$this->intArea = $idarea;

		$this->strResposable = $responsable;
		$this->strPresupuestoAnual = $presupuestoanual;
		$this->strPresupuestoMensual = $presupuestomensual;
		$this->strFechacreacion = $fechacreacion;
		$this->intEstado = $estado;
		$this->intCreado_por = $creado_por;
		$this->intActualizado_por = $actualizado_por;



		$sql = "SELECT * FROM centros_costo WHERE nombre = '{$this->strNombre}' ";
		$request = $this->select_all($sql);

		if (empty($request)) {
			$query_insert = "INSERT INTO centros_costo(nombre,idempresa,iddireccion,idarea,responsable,presupuestoanual,presupuestomensual,fechacreacion,estado,creado_por,actualizado_por) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
			$arrData = array(
				$this->strNombre,
				$this->intEmpresa,
				$this->intDireccion,
				$this->intArea,
				$this->strResposable,
				$this->strPresupuestoAnual,
				$this->strPresupuestoMensual,
				$this->strFechacreacion,
				$this->intEstado,
				$this->intCreado_por,
				$this->intActualizado_por
			);
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function selectCcostos()
	{
		$sql = " SELECT 
    cc.idcentro,
    cc.nombre AS nombre_centro,
    cc.idarea,
    a.nombre_area,
    a.id_direccion,
    cc.responsable,
    cc.presupuestoanual,
    cc.presupuestomensual,
    cc.fechacreacion,
    cc.estado,
    cc.creado_por,
    cc.actualizado_por,
    cc.fechaactualizacion,

    col.id_colaborador,
	col.nombre_1,
	col.apellido_paterno,
	col.apellido_materno,
	col.email_corporativo

FROM 
    centros_costo cc
INNER JOIN 
    areas a ON cc.idarea = a.id_area
	INNER JOIN colaboradores as col
	ON cc.actualizado_por = col.id_colaborador
    WHERE cc.estado != 0";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectCcosto(int $idcosto)
	{
		$this->intIdccosto = $idcosto;
		$sql = "SELECT 
    cc.idcentro,
    cc.nombre AS nombre_centro,
	cc.idempresa,
	cc.iddireccion,
    cc.idarea,
    cc.responsable,
    cc.presupuestoanual,
    cc.presupuestomensual,
    cc.fechacreacion,
    cc.estado,
    cc.creado_por,
    cc.actualizado_por,
    cc.fechaactualizacion,

	em.nombre_empresa,
	dir.nombre_direccion,

	a.nombre_area,
    a.id_direccion,

	col.id_colaborador,
	col.nombre_1,
	col.apellido_paterno,
	col.apellido_materno,
	col.email_corporativo as actualizado_por_email_corporativo,

	colcreate.email_corporativo as creado_por_email_corporativo

FROM 
    centros_costo cc

	INNER JOIN empresas as em
	ON cc.idempresa = em.id_empresa

		INNER JOIN direcciones as dir
	ON cc.iddireccion = dir.id_direccion
	
INNER JOIN  areas as a 
ON cc.idarea = a.id_area

INNER JOIN colaboradores as col
	ON cc.actualizado_por = col.id_colaborador

	INNER JOIN colaboradores as colcreate
	ON cc.creado_por = colcreate.id_colaborador


                    WHERE cc.idcentro = $this->intIdccosto";
		$request = $this->select($sql);
		return $request;
	}

	public function selectCcostosArea(int $idarea)
	{
		$this->intIdarea = $idarea;
		$sql = "SELECT 
    cc.idcentro,
    cc.nombre AS nombre_centro,
    cc.idempresa,
	 cc.iddireccion,
	  cc.idarea,
    a.nombre_area,
    a.id_direccion,
    cc.responsable,
    cc.presupuestoanual,
    cc.presupuestomensual,
    cc.fechacreacion,
    cc.estado,
    cc.creado_por,
    cc.actualizado_por,
    cc.fechaactualizacion
FROM 
    centros_costo cc
INNER JOIN 
    areas a ON cc.idarea = a.id_area
                    WHERE cc.idarea = $this->intIdarea AND cc.estado !=0";
		$request = $this->select_all($sql);
		return $request;
	}





	public function updateCentroCosto(int $idcentro, string $nombre, int $idempresa, int $iddirecion, int $idarea, string $responsable, string $presupuestoanual, string $presupuestomensual, string $fechacreacion, int $estado, int $creado_por, int $actualizado_por)
	{
		$this->intIdccosto = $idcentro;

		$this->strNombre = $nombre;
		$this->intEmpresa = $idempresa;
		$this->intDireccion = $iddirecion;
		$this->intArea = $idarea;

		$this->strResposable = $responsable;
		$this->strPresupuestoAnual = $presupuestoanual;
		$this->strPresupuestoMensual = $presupuestomensual;
		$this->strFechacreacion = $fechacreacion;
		$this->intEstado = $estado;
		$this->intCreado_por = $creado_por;
		$this->intActualizado_por = $actualizado_por;


		$sql = "UPDATE centros_costo SET nombre = ?, idempresa = ?, iddireccion = ?, idarea = ?, responsable = ?, presupuestoanual = ?, presupuestomensual = ?, actualizado_por = ? WHERE idcentro = $this->intIdccosto ";
		$arrData = array(
			$this->strNombre,
			$this->intEmpresa,
			$this->intDireccion,
			$this->intArea,
			$this->strResposable,
			$this->strPresupuestoAnual,
			$this->strPresupuestoMensual,
			$this->intActualizado_por
		);
		$request = $this->update($sql, $arrData);

		return $request;
	}

	public function deleteCcosto(int $idccosto)
	{
		$this->intIdccosto = $idccosto;
		// $sql = "SELECT * FROM producto WHERE categoriaid = $this->intIdccosto";
		// $request = $this->select_all($sql);
		// if(empty($request))
		// {
		$sql = "UPDATE centros_costo SET estado = ? WHERE idcentro = $this->intIdccosto ";
		$arrData = array(0);
		$request = $this->update($sql, $arrData);
		if ($request) {
			$request = 'ok';
		} else {
			$request = 'error';
		}
		// }else{
		// 	$request = 'exist';
		// }
		return $request;
	}



	public function selectEmpresas()
	{

		$sql = "SELECT * FROM empresas WHERE id_empresa !=1 AND id_empresa !=5 AND id_empresa !=9 ";
		$request = $this->select_all($sql);
		return $request;
	}


	public function selectDirecciones(int $idEmpresa)
	{
		$this->intIdempresa = $idEmpresa;
		$sql = "SELECT * FROM direcciones
					WHERE id_colaborador !=0  AND   id_empresa = $this->intIdempresa";
		$request = $this->select_all($sql);
		return $request;
	}


	public function selectAreas(int $idDireccion)
	{
		$this->intIddireccion = $idDireccion;
		$sql = "SELECT * FROM areas
					WHERE nombre_area !='SIN ASIGNAR'  AND  id_direccion = $this->intIddireccion";
		$request = $this->select_all($sql);
		return $request;
	}
}
