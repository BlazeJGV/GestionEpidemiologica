<?php

	if($requestAjax){
		require_once "../model/personModel.php";
	}else{
		require_once "./model/personModel.php";
	}

	class personController extends personModel{

	// Funciones para manejar datos (CRUD)
		public function addPersonControllerr($dataPerson){
		 
		$doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		
		 $doc_identidad = self::ClearUserSeparatedCharacters($doc_identidad);

		 $nombres = mainModel::cleanStringSQL($dataPerson['nombres']);
		 
		 $apellidos = mainModel::cleanStringSQL($dataPerson['apellidos']);

		$nombres=self::filtterNombresApellidos($nombres);

		$apellidos=self::filtterNombresApellidos($apellidos);

		 $fecha_nacimiento = mainModel::cleanStringSQL($dataPerson['fecha_nacimiento']);		 	
		 
		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);
		 
		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);

		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);

		 $telefono = mainModel::cleanStringSQL($dataPerson['telefonoPart1'].$dataPerson['telefonoPart2'].$dataPerson['telefonoPart3']);

		 $telefono = self::ClearUserSeparatedCharacters($telefono);


		if (mainModel::isDataEmtpy(
			$doc_identidad,
			$nombres,
			$apellidos,
			$fecha_nacimiento,
			$id_nacionalidad,$id_genero,$telefono)) {

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Campos Vacios",
				"Text"=>"Todos los datos personles son obligatorios",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			$queryIsExistPerson = mainModel::connectDB()->query("SELECT doc_identidad FROM personas WHERE doc_identidad =
			'$doc_identidad' AND id_nacionalidad =
			'$id_nacionalidad'");

			if($queryIsExistPerson->rowCount()){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"Ya se encuentra una person con este documento de identidad",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if(!self::isValiddoc_identidad($doc_identidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El documento de identidad es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!mainModel::isValidSelectionTwoOptions($id_nacionalidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Nacionalidad es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if(!mainModel::isValidSelectionTwoOptions($id_genero)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Genero es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValidNombresApellidos($nombres,$apellidos)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nombre o Apellido ingresado es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if (!mainModel::checkDate($fecha_nacimiento)){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo fecha de  nacimiento es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
		}


		if (mainModel::isDateGreaterCurrentDate($fecha_nacimiento)) {
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"La Fecha de Nacimiento es mayor a la del sistema",
					"Type"=>"error"
				];

					echo json_encode($alert);

					exit();
		}

		if(!mainModel::isValidNroTlf($telefono)){
			
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nro de Telefono es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}



		 $dataPerson = array();

		 $dataPerson['doc_identidad'] = $doc_identidad;
		 
		 $dataPerson['nombres'] = $nombres;
		 
		 $dataPerson['apellidos'] = $apellidos;
		 
		 $dataPerson['fecha_nacimiento'] = $fecha_nacimiento;

		 $dataPerson['id_nacionalidad'] = $id_nacionalidad;
		 
		 $dataPerson['id_genero'] = $id_genero;
		
		return $dataPerson;
		
				 	}				


		public static function updatePersonaController($dataPerson){

		 $doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		 
		 $nombres = mainModel::cleanStringSQL($dataPerson['nombres']);
		 
		 $apellidos = mainModel::cleanStringSQL($dataPerson['apellidos']);
		 
		 $fecha_nacimiento = mainModel::cleanStringSQL($dataPerson['fecha_nacimiento']);
		 
		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);
		 
		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);
		
		 $telefono = $dataPerson['telefonoPart1'].$dataPerson['telefonoPart2'].$dataPerson['telefonoPart3'];

		 $telefono = mainModel::cleanStringSQL($telefono);

		 			if (!isset(
						 $doc_identidad,
						 $nombres,
						 $apellidos,
						 $fecha_nacimiento,
						 $id_nacionalidad,
						 $id_genero)) {

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Campos Vacios",
				"Text"=>"Faltan campos para la actualizacion",
				"Type"=>"error"
			];
			
				echo json_encode($alert);

				exit();

			}

	 		// Campos del usuario como person a comparar con la BD
	 		$personDataTocomparedWithDatabase = [
			 "fecha_nacimiento"=>$fecha_nacimiento,
			 "nombres"=>$nombres,
			 "apellidos"=>$apellidos,
			 "id_genero"=>$id_genero 			
	 		];


	 	$queryToGetPerson = self::getpersonController(array("doc_identidad"=>$doc_identidad,"id_nacionalidad"=>$id_nacionalidad));

		$ifPersonDataUpdateIsSameDatabase = mainModel::isFieldsEqualToThoseInTheDatabase($queryToGetPerson,$personDataTocomparedWithDatabase);
 


			$isExistPerson = mainModel::connectDB()->query("SELECT doc_identidad FROM personas WHERE doc_identidad =
			'$doc_identidad' AND id_nacionalidad =
			'$id_nacionalidad'");

			if(!$isExistPerson->rowCount()){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"Ya se encuentra una person con este documento de identidad",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValiddoc_identidad($doc_identidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El documento de identidad es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if(!mainModel::isValidSelectionTwoOptions($id_genero)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Genero es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValidNombresApellidos($nombres,$apellidos)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nombre o Apellido ingresado es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if (!mainModel::checkDate($fecha_nacimiento)){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo fecha de  nacimiento es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
		}


		if (mainModel::isDateGreaterCurrentDate($fecha_nacimiento)) {
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"La Fecha de Nacimiento es mayor a la del sistema",
					"Type"=>"error"
				];

					echo json_encode($alert);

					exit();
		}

		if(!mainModel::isValidNroTlf($telefono)){
			
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nro de Telefono es invalido",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}



		 $dataPerson = array();

		 $dataPerson['doc_identidad'] = $doc_identidad;
		 
		 $dataPerson['nombres'] = $nombres;
		 
		 $dataPerson['apellidos'] = $apellidos;
		 
		 $dataPerson['fecha_nacimiento'] = $fecha_nacimiento;
		 
		 $dataPerson['id_nacionalidad'] = $id_nacionalidad;
		 
		 $dataPerson['id_genero'] = $id_genero;
				 	

			// si la datos nuevos son los mismos a los de la BD, obviaremos la consulta en model

		 $dataPerson['ifPersonDataUpdateIsSameDatabase'] = $ifPersonDataUpdateIsSameDatabase;

		 return $dataPerson;

				 }


		public function deletePersonController($dataPerson){
			
		 $doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		 
		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);

		 	if (mainModel::isDataEmtpy($doc_identidad,$id_nacionalidad)) {
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Vacios",
					"Text"=>"Los datos no fueron recibidos",
					"Type"=>"error"];
					
				echo json_encode($alert);

				exit();

				 	}

			$primaryKeyperson = [

				"id_nacionalidad"=>$id_nacionalidad,
				"doc_identidad"=>$doc_identidad
			];

			$queryIsExistperson = mainModel::connectDB()->query("select doc_identidad from personas where id_nacionalidad = '$id_nacionalidad' and doc_identidad = '$doc_identidad'");
			
			if(!$queryIsExistperson->rowCount()){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos no encontrados",
				"Text"=>"No se encuentra una person con esta cedula registrada ",
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}
							 

				 }



		public static function getpersonController($dataPerson){

		$personttributesFilter = [];

 		$filterValues = [];

		if (isset($dataPerson["id_nacionalidad"]) && !mainModel::isDataEmtpy($dataPerson["id_nacionalidad"])) {

		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);

		 array_push($personttributesFilter, 'id_nacionalidad = :id_nacionalidad');
		$filterValues[':id_nacionalidad'] = [
		'value' => $id_nacionalidad,
		'type' => \PDO::PARAM_STR,
		];

		}
	
	if (isset($dataPerson["doc_identidad"]) && !mainModel::isDataEmtpy($dataPerson["doc_identidad"])) {

		 $doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		
		array_push($personttributesFilter, 'doc_identidad = :doc_identidad');
		$filterValues[':doc_identidad'] = [
		'value' => $doc_identidad,
		'type' => \PDO::PARAM_STR,
		];

	}		

		if (isset($dataPerson["nombres"]) && !mainModel::isDataEmtpy($dataPerson["nombres"])) {

		 $nombres = mainModel::cleanStringSQL($dataPerson['nombres']);

		array_push($personttributesFilter, 'nombres = :nombres');
		$filterValues[':nombres'] = [
		'value' => $nombres,
		'type' => \PDO::PARAM_STR,
		];

		}
		 
		if (isset($dataPerson["apellidos"]) && !mainModel::isDataEmtpy($dataPerson["apellidos"])) {

		 $apellidos = mainModel::cleanStringSQL($dataPerson['apellidos']);
			
		array_push($personttributesFilter, 'apellidos = :apellidos');
		$filterValues[':apellidos'] = [
		'value' => $apellidos,
		'type' => \PDO::PARAM_STR,
		];

		}


		if (isset($dataPerson["fecha_nacimiento"]) && !mainModel::isDataEmtpy($dataPerson["fecha_nacimiento"])) {

		array_push($personttributesFilter, 'fecha_nacimiento = :fecha_nacimiento');
		$filterValues[':fecha_nacimiento'] = [
		'value' => $fecha_nacimiento,
		'type' => \PDO::PARAM_STR,
		];

		 $fecha_nacimiento = mainModel::cleanStringSQL($dataPerson['fecha_nacimiento']);
		 
		 }

		if (isset($dataPerson["id_genero"]) && !mainModel::isDataEmtpy($dataPerson["id_genero"])) {
		 
		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);

		array_push($personttributesFilter, 'id_genero = :id_genero');
		$filterValues[':id_genero'] = [
		'value' => $id_genero,
		'type' => \PDO::PARAM_STR,
		];
	}


			return personModel::getPersonModel($personttributesFilter,$filterValues);

		}



// Funciones Para validar DATOS

public static function isValiddoc_identidad($doc_identidad){
    if(preg_match_all("/^[0-9]{7,8}$/",$doc_identidad)){
        return TRUE;
    }
        return FALSE;
	}


public static function isValidNombresApellidos(...$NombresApellidos){
	    foreach ($NombresApellidos as $NombreApellido) {
        
       if(!preg_match("/^(?=.{2,36}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/",$NombreApellido)){
        
        return FALSE; 
         }
     } 
        return TRUE;
    }


// Funciones Para Limpiar/filtrar DATOS

public static function filtterNombresApellidos($nombreApellido){

       $nombreApellido=trim($nombreApellido);

        $nombreApellido=ucwords(strtolower($nombreApellido));
        
        return $nombreApellido; 

}

}

 ?>