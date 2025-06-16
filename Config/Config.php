<?php 
	const BASE_URL = "http://localhost/viaticos";
	//const BASE_URL = "https://abelosh.com/tiendavirtual";

	//Zona horaria
	date_default_timezone_set('America/Guatemala');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "desarrollo_rhldrsolutions";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//Para envío de correo
	const ENVIRONMENT = 1; // Local: 0, Produccón: 1;

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "$";
	const CURRENCY = "USD";

	//Api PayPal
	//SANDBOX PAYPAL
	const URLPAYPAL = "https://api-m.sandbox.paypal.com";
	const IDCLIENTE = "";
	const SECRET = "";


	//Datos envio de correo
	const NOMBRE_REMITENTE = "LDR Solutions";
	const EMAIL_REMITENTE = "carloscc_1997@outlook.com";
	const NOMBRE_EMPESA = "LDR Solutions";
	const WEB_EMPRESA = "https://www.ldrsolutions.mx/";



	//Datos Empresa
	const DIRECCION = "AProl. P.º de la Reforma 1015-piso 24, Santa Fe, Contadero, Cuajimalpa de Morelos, 05348 Ciudad de México, CDMX";

	const STATUS = array('Completo','Aprobado','Cancelado','Reembolsado','Pendiente','Entregado');

	//DATOS FISCALES EMPRESA

    const RFC = "BCD161025T59";
	

 ?>