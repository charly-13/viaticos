<?php 

	class LoginModel extends Mysql
	{
		private $intIdUsuario;
		private $strUsuario;
		private $strPassword;
		private $strToken;

		public function __construct()
		{
			parent::__construct();
		}	

		public function loginUser(string $usuario, string $password)
		{
			$this->strUsuario = $usuario;
			$this->strPassword = $password;
			$sql = "SELECT idpersona,status FROM persona WHERE 
					email_user = '$this->strUsuario' and 
					password = '$this->strPassword' and 
					status != 0 ";
			$request = $this->select($sql);
			return $request;
		}

		public function sessionLoginViaticos(int $iduser){
			$this->intIdUsuario = $iduser;
			//BUSCAR ROLE 
			$sql = "SELECT 
    u.id_usuario,
    u.id_colaborador,
    u.avatar,
    u.id_tipo_usuario,

    c.id_colaborador,
    c.numero_colaborador,
    c.nombre_1,
    c.nombre_2,
    c.nombre_fav,
    c.apellido_paterno,
    c.apellido_materno,
    c.fecha_nacimiento,
    c.email_corporativo AS email_usuario,
    c.genero,
    c.curp,
    c.id_area,
    c.id_jefe_directo,
    c.id_rol,
    c.id_banco,
    c.cuenta_bancaria,
    c.clabe_interbancaria,
    c.biaticos_id_banco,
    c.biaticos_nombre_titular,
    c.biaticos_numero_cuenta,
    c.biaticos_clabe_interbancaria,

    r.idrol AS rol_id,
    r.nombre AS nombre_rol,

    -- Datos del jefe directo (condicional)
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jd.id_jefe_directo END AS id_jefe_directo,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.id_colaborador END AS id_colaborador_jefe,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.nombre_1 END AS nombre_jefe,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.apellido_paterno END AS apellido_paterno_jefe,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.apellido_materno END AS apellido_materno_jefe,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.email_corporativo END AS email_jefe,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe.id_jefe_directo END AS id_jefe_directo_jefe,

    -- Datos del jefe del jefe (condicional)
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jd_superior.id_jefe_directo END AS id_jefe_superior,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe_superior.id_colaborador END AS id_colaborador_jefe_superior,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe_superior.nombre_1 END AS nombre_jefe_superior,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe_superior.apellido_paterno END AS apellido_paterno_jefe_superior,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe_superior.apellido_materno END AS apellido_materno_jefe_superior,
    CASE WHEN c.id_jefe_directo = 0 THEN NULL ELSE jefe_superior.email_corporativo END AS email_jefe_superior,

    a.nombre_area,

    -- ¿Es jefe directo?
    CASE 
        WHEN jd_check.id_jefe_directo IS NOT NULL THEN 'SI'
        ELSE 'NO'
    END AS es_jefe_directo,

    -- ¿Tiene CEO?
    CASE 
        WHEN c.id_jefe_directo = 0 THEN 'NO'
        WHEN jefe.id_jefe_directo = 0 THEN 'NO'
        ELSE 'SI'
    END AS tiene_CEO

FROM 
    usuarios AS u
INNER JOIN 
    colaboradores AS c ON u.id_colaborador = c.id_colaborador
INNER JOIN 
    areas AS a ON c.id_area = a.id_area
INNER JOIN 
    roles AS r ON c.id_rol = r.idrol -- <<<<<< AQUI SOLO AGREGAMOS ESTO

LEFT JOIN 
    jefes_directos AS jd ON c.id_jefe_directo = jd.id_jefe_directo
LEFT JOIN 
    colaboradores AS jefe ON jd.id_colaborador = jefe.id_colaborador
LEFT JOIN 
    jefes_directos AS jd_superior ON jefe.id_jefe_directo = jd_superior.id_jefe_directo
LEFT JOIN 
    colaboradores AS jefe_superior ON jd_superior.id_colaborador = jefe_superior.id_colaborador
LEFT JOIN 
    jefes_directos AS jd_check ON u.id_colaborador = jd_check.id_colaborador

WHERE  
    u.id_usuario = $this->intIdUsuario
    
";
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;
		}








	}
 ?>