<?php 	

	if($requestAjax){
		require_once "../model/mainModel.php";
	}else{
		require_once "./model/mainModel.php";
	}

	class loginController extends mainModel{

public function loginUserController($dataUser){
		$aliasUser = mainModel::cleanStringSQL($dataUser["aliasUser"]);
		$passRequest = mainModel::cleanStringSQL($dataUser["password"]);


		if (mainModel::isDataEmtpy($aliasUser,
		 	$passRequest)){

				$alert=[
					"Alert"=>"simple",
					"Title"=>"Campos Vacios",
					"Text"=>"Todos los campos del usuario son obligatorios",
					"Type"=>"error"
				];

				echo json_encode($alert);

				exit();

			}

			// para usar los metodos de user como si heredaramos
			require_once "../controller/userController.php";

			$userController = new userController();

			$recordsUserSQL = userController::getUserController(array("aliasUser"=>$aliasUser));
					
			$recordsUserSQL->execute();


		if(!$recordsUserSQL->rowCount()){
				
				$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos no encontrados",
				"Text"=>"No existe un usuario con este alias registrado",
				"Type"=>"error"
			];
				echo json_encode($alert);

				exit();

			}


		// Obtener datos de usuario
		while($valuesDataUser=$recordsUserSQL->fetch(PDO
			::FETCH_ASSOC)){ 

			$passEncryptDB = $valuesDataUser["passEncrypt"]; 

			$aliasUser = $valuesDataUser["alias"]; 
			
			$docIdentidad = $valuesDataUser["docIdentidad"];

			$idNivelPermiso = $valuesDataUser["idNivelPermiso"];

			$idEstado = $valuesDataUser["idEstado"];			
		}

			// Comprobar contrasenas

			$passRequest = mainModel::encryption($passRequest);

		    if (strcmp($passEncryptDB, $passRequest) != 0){
				$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"La contraseña es incorrecta",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
    	}


		// Obtener datos de persona
			require_once "../controller/personaController.php";

			$personController = new personaController();

			$recordsPersonSQL = personaController::getPersonaController(array("docIdentidad"=>$docIdentidad));
					
			$recordsPersonSQL->execute();
	
			while($valuesDataPerson=$recordsPersonSQL->fetch(PDO
			::FETCH_ASSOC)){ 

			$nameUser = $valuesDataPerson["idGenero"]; 
			$nameUser = $valuesDataPerson["nombres"]; 
			$lastNamesUser = $valuesDataPerson["apellidos"]; 
			$idGeneroUser = $valuesDataPerson["idGenero"]; 

		}


		// Comprobar que el estado del usuario se ha valido
		
		if(!$idEstado){
				
				$alert=[
				"Alert"=>"simple",
				"Title"=>"Permiso Denegado",
				"Text"=>"El usuario se encuentra inactivo por favor contactar un administrador",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();

			}

				$arrayNamesUser = explode (" ", $nameUser);
				$arrayLastNamesUser = explode (" ", $lastNamesUser);

				session_start(['name'=>'dptoEpidemi']);
			     

				$_SESSION['docIdentidad']=$docIdentidad;
				$_SESSION['aliasUser']=$aliasUser;
				$_SESSION['nameUser']=$arrayNamesUser[0];
				$_SESSION['lastNameUser']=$arrayLastNamesUser[0];
				$_SESSION['idNivelPermiso']=$idNivelPermiso;
				$_SESSION['idEstado']=$idEstado;
				$_SESSION['idGeneroUser']=$idGeneroUser;
				$_SESSION['token_dptoEpidemi']=md5(uniqid(mt_rand(),true));
			
				if ($idGeneroUser == "1"){
                  $_SESSION['iconUser'] = "male-user.png"; 
                }elseif ($idGeneroUser == "2") {
                  $_SESSION['iconUser'] = "fermale-user.png"; 
                }


				$alert=[
				"Alert"=>"redirecting",
				"URL"=>SERVERURL."dashboard/"
				];

				echo json_encode($alert);

				 //header("Location: ".SERVERURL."dashboard/");
		}
 
		public function forceClosureController(){
			session_unset();
			session_destroy();
			if(headers_sent()){
				return "<script> window.location.href='".SERVERURL."login/';</script>";
			}else{
				return header("Location: ".SERVERURL);
			}

		}

		protected function closeControllerSession($dataLogin){
			session_start(['name'=>'dptoEpidemi']);
			$tokenUser=mainModel::decryption($dataLogin['tokenUser']);
			$aliasUser=mainModel::decryption($dataLogin['aliasUser']);

			if($tokenUser==$_SESSION['token_dptoEpidemi'] && $aliasUser==$_SESSION['aliasUser']){
				session_unset();
				session_destroy();
				$alert=[
					"Alert"=>"redireccionar",
					"URL"=>SERVERURL
				];

			}else{
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Error al cerrar la sesión",
					"Text"=>"No se pudo cerrar la sesion en el sistema",
					"Type"=>"error"
				];
			}
			echo json_encode($alert);
		}


	public static function randIconUserIMG(){

	$idGeneroUser =mt_rand(1,2);
	if ($idGeneroUser == "1"){
		 return $iconUser = "male-user.png"; 
			}elseif ($idGeneroUser == "2") {
				 return $iconUser = "fermale-user.png"; 
           	}

       }

 }
 ?>