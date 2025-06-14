<?php
	class Logout
	{
		public function __construct()
		{
			session_start();
			session_unset();
			session_destroy();
			header("Location: https://ldrhsys.ldrhumanresources.com/");
			die();
		}
	}
 ?>