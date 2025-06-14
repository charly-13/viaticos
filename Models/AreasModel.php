<?php 

	class AreasModel extends Mysql
	{


		public function __construct()
		{
			parent::__construct();
		}





        		public function selectAreas()
		{
			$sql = "SELECT * FROM areas  ";
			$request = $this->select_all($sql);
			return $request;
		}







	}
 ?>