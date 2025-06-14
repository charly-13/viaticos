<?php 

	class Login extends Controllers{
		public function __construct()
		{
			session_start();
			if(isset($_SESSION['login']))
			{
				header('Location: '.base_url().'/dashboard');
				die();
			}
			parent::__construct();
		}

		public function login()
		{
			if (isset($_GET['idusuario'])) {
            $idUsuario = $_GET['idusuario'];
	
            // Lógica: Verificamos si existe es ID en base

				$arrData = $this->model->sessionLoginViaticos($idUsuario);

			if (!empty($arrData)) {
            // Si encontró al Usuario, creamos la sesión
            $_SESSION['idUser'] = $arrData['id_colaborador']; 
            $_SESSION['login'] = true;
            $_SESSION['userData'] = $arrData;

            // Redirige al dashboard
            header("Location: " . base_url() . "/dashboard");
		
            exit();
        } else {

            // Redirige a la plataforma original si no se encuentra el Usuario
            header("Location: https://ldrhsys.ldrhumanresources.com/Cliente/interfaces/Inicio.php");
            exit();
        }

        } else {
             header("Location: " . base_url() . "/logout");
            exit();
        }
    }
	}
 ?>