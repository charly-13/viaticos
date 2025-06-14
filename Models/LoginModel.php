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

    c.numero_colaborador,
    c.nombre_1,
    c.nombre_2,
    c.nombre_fav,
    c.apellido_paterno,
    c.apellido_materno,
    c.fecha_nacimiento,
	c.email_corporativo As email_usuario,
    c.genero,
    c.curp,
    c.id_area,
    c.id_jefe_directo,

    -- Datos del jefe directo
    jd.id_jefe_directo,
    jefe.id_colaborador AS id_colaborador_jefe,
    jefe.nombre_1 AS nombre_jefe,
    jefe.apellido_paterno AS apellido_paterno_jefe,
    jefe.apellido_materno AS apellido_materno_jefe,
    jefe.email_corporativo AS email_jefe,  -- email del jefe directo

    -- Datos del jefe del jefe
    jd_superior.id_jefe_directo AS id_jefe_superior,
    jefe_superior.id_colaborador AS id_colaborador_jefe_superior,
    jefe_superior.nombre_1 AS nombre_jefe_superior,
    jefe_superior.apellido_paterno AS apellido_paterno_jefe_superior,
    jefe_superior.apellido_materno AS apellido_materno_jefe_superior,
    jefe_superior.email_corporativo AS email_jefe_superior,  -- email del jefe del jefe

    a.nombre_area  

FROM 
    usuarios AS u
INNER JOIN 
    colaboradores AS c ON u.id_colaborador = c.id_colaborador
INNER JOIN 
    areas AS a ON c.id_area = a.id_area 
INNER JOIN 
    jefes_directos AS jd ON c.id_jefe_directo = jd.id_jefe_directo
INNER JOIN 
    colaboradores AS jefe ON jd.id_colaborador = jefe.id_colaborador  -- colaborador que es jefe directo

-- Relación con el jefe del jefe
LEFT JOIN 
    jefes_directos AS jd_superior ON jefe.id_jefe_directo = jd_superior.id_jefe_directo
LEFT JOIN 
    colaboradores AS jefe_superior ON jd_superior.id_colaborador = jefe_superior.id_colaborador  -- colaborador que es jefe del jefe

WHERE  
    u.id_usuario = $this->intIdUsuario";
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;
		}

		public function getUserEmail(string $strEmail){
			$this->strUsuario = $strEmail;
			$sql = "SELECT idpersona,nombres,apellidos,status FROM persona WHERE 
					email_user = '$this->strUsuario' and  
					status = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function setTokenUser(int $idpersona, string $token){
			$this->intIdUsuario = $idpersona;
			$this->strToken = $token;
			$sql = "UPDATE persona SET token = ? WHERE idpersona = $this->intIdUsuario ";
			$arrData = array($this->strToken);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function getUsuario(string $email, string $token){
			$this->strUsuario = $email;
			$this->strToken = $token;
			$sql = "SELECT idpersona FROM persona WHERE 
					email_user = '$this->strUsuario' and 
					token = '$this->strToken' and 					
					status = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertPassword(int $idPersona, string $password){
			$this->intIdUsuario = $idPersona;
			$this->strPassword = $password;
			$sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = $this->intIdUsuario ";
			$arrData = array($this->strPassword,"");
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>