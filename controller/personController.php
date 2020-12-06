<?php

	if($requestAjax){
		require_once "../model/personModel.php";
	}else{
		require_once "./model/personModel.php";
	}

	class personController extends personModel{

	// Funciones para manejar datos (CRUD)
		public function addPersonControllerr($dataPerson){
		 
		 $indicatorPersonError = '';
		 if (isset($dataPerson['indicatorPersonError'])) {
		 	$indicatorPersonError = $dataPerson['indicatorPersonError'];
		 }

		$doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		
		 $doc_identidad = self::ClearUserSeparatedCharacters($doc_identidad);		
		 $doc_identidad = ltrim($doc_identidad, '0');

		 $nombres = mainModel::cleanStringSQL($dataPerson['nombres']);
		 
		 $apellidos = mainModel::cleanStringSQL($dataPerson['apellidos']);

		$nombres=self::filtterNombresApellidos($nombres);

		$apellidos=self::filtterNombresApellidos($apellidos);

		 $fecha_nacimiento = mainModel::cleanStringSQL($dataPerson['fecha_nacimiento']);		 	
		 

		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);
		 
		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);

		 // en las importaciones de datos no tendran los nros separados
if (!isset($dataPerson['operationImportCaseEpidemi'])) {
		 $telefono = mainModel::cleanStringSQL($dataPerson['telefonoPart1'].$dataPerson['telefonoPart2'].$dataPerson['telefonoPart3']);
		}else{
		 $telefono = mainModel::cleanStringSQL($dataPerson['telefono']);
		}

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
			
			if(!self::isValidDocIdentidad($doc_identidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El documento de identidad es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!mainModel::isValidSelectionTwoOptions($id_nacionalidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Nacionalidad es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if(!mainModel::isValidSelectionTwoOptions($id_genero)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Genero es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValidNombresApellidos($nombres,$apellidos)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nombre o Apellido ingresado es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if (!mainModel::checkDate($fecha_nacimiento)){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo fecha de  nacimiento es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
		}


		if (mainModel::isDateGreaterCurrentDate($fecha_nacimiento)) {
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"La Fecha de Nacimiento es mayor a la del sistema".$indicatorPersonError,
					"Type"=>"error"
				];

					echo json_encode($alert);

					exit();
		}

		if(!mainModel::isValidNroTlf($telefono)){
			
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nro de Telefono es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}



		 $dataPerson = array();

				$dataPerson = 
		["id_nacionalidad"=>$id_nacionalidad,
		"doc_identidad"=>$doc_identidad,		
		"nombres"=>$nombres,
		"apellidos"=>$apellidos,
		"fecha_nacimiento"=>$fecha_nacimiento,
		"id_genero"=>$id_genero,
		"telefono"=>$telefono,
		];

		return $dataPerson;
		
				 	}				


		public static function updatePersonaController($dataPerson){

		 $indicatorPersonError = '';
		 if (isset($dataPerson['indicatorPersonError'])) {
		 	$indicatorPersonError = $dataPerson['indicatorPersonError'];
		 }

		 $doc_identidad = mainModel::cleanStringSQL($dataPerson['doc_identidad']);
		 
		 $nombres = mainModel::cleanStringSQL($dataPerson['nombres']);
		 
		 $apellidos = mainModel::cleanStringSQL($dataPerson['apellidos']);
		 
		 $fecha_nacimiento = mainModel::cleanStringSQL($dataPerson['fecha_nacimiento']);
		 
		 $id_nacionalidad = mainModel::cleanStringSQL($dataPerson['id_nacionalidad']);
		 
		 $id_genero = mainModel::cleanStringSQL($dataPerson['id_genero']);
		
if (!isset($dataPerson['operationImportCaseEpidemi'])) {
		 $telefono = mainModel::cleanStringSQL($dataPerson['telefonoPart1'].$dataPerson['telefonoPart2'].$dataPerson['telefonoPart3']);
		}else{
		 $telefono = mainModel::cleanStringSQL($dataPerson['telefono']);
		}

		 $telefono = self::ClearUserSeparatedCharacters($telefono);


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



			$queryIsExistPerson = mainModel::connectDB()->query("select doc_identidad from personas where id_nacionalidad = '$id_nacionalidad' and doc_identidad = '$doc_identidad'");
			
			if(!$queryIsExistPerson->rowCount()){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos no encontrados",
				"Text"=>"No se encuentra una person con esta cedula registrada ".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValidDocIdentidad($doc_identidad)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El documento de identidad es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if(!mainModel::isValidSelectionTwoOptions($id_genero)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo de Genero es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}

			if(!self::isValidNombresApellidos($nombres,$apellidos)){

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nombre o Apellido ingresado es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


			if (!mainModel::checkDate($fecha_nacimiento)){
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El campo fecha de  nacimiento es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
		}


		if (mainModel::isDateGreaterCurrentDate($fecha_nacimiento)) {
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"La Fecha de Nacimiento es mayor a la del sistema".$indicatorPersonError,
					"Type"=>"error"
				];

					echo json_encode($alert);

					exit();
		}

		if(!mainModel::isValidNroTlf($telefono)){
			
			$alert=[
				"Alert"=>"simple",
				"Title"=>"Datos Invalidos",
				"Text"=>"El Nro de Telefono es invalido".$indicatorPersonError,
				"Type"=>"error"
			];

				echo json_encode($alert);

				exit();
			}


	 		// Campos del usuario como person a comparar con la BD

		$dataPerson = 
		[
		"nombres"=>$nombres,
		"apellidos"=>$apellidos,
		"fecha_nacimiento"=>$fecha_nacimiento,
		"id_genero"=>$id_genero,
		];

		$columnsTableToCompare = 
		[
		"nombres",
		"apellidos",
		"fecha_nacimiento",
		"id_genero",
		];
				 	

	 	$queryToGetPerson = self::getpersonController($columnsTableToCompare,array("doc_identidad"=>$doc_identidad,"id_nacionalidad"=>$id_nacionalidad),);


		$ifPersonDataUpdateIsSameDatabase = mainModel::isFieldsEqualToThoseInTheDatabase($queryToGetPerson,$dataPerson);

			// si la datos nuevos son los mismos a los de la BD, 
			// inclueremos un elemento que indique que se proceda actualizar			
		 $dataPerson['ifUpdatePerson']  = true;

		 // si los datos son lo mismos no actualice
		if ($ifPersonDataUpdateIsSameDatabase){
		 $dataPerson['ifUpdatePerson']  = false;
		}

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

			$queryIsExistPerson = mainModel::connectDB()->query("select doc_identidad from personas where id_nacionalidad = '$id_nacionalidad' and doc_identidad = '$doc_identidad'");
			
			if(!$queryIsExistPerson->rowCount()){
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



		public static function getpersonController($columnsTable,$fieldsForFilter){

		$personAttributesFilter = [];

 		$filterValues = [];

		if (isset($fieldsForFilter["id_nacionalidad"]) && !mainModel::isDataEmtpy($fieldsForFilter["id_nacionalidad"])) {

		 $id_nacionalidad = mainModel::cleanStringSQL($fieldsForFilter['id_nacionalidad']);

		 array_push($personAttributesFilter, 'id_nacionalidad = :id_nacionalidad');
		$filterValues[':id_nacionalidad'] = [
		'value' => $id_nacionalidad,
		'type' => \PDO::PARAM_STR,
		];

		}
	
	if (isset($fieldsForFilter["doc_identidad"]) && !mainModel::isDataEmtpy($fieldsForFilter["doc_identidad"])) {

		 $doc_identidad = mainModel::cleanStringSQL($fieldsForFilter['doc_identidad']);
		
		array_push($personAttributesFilter, 'doc_identidad = :doc_identidad');
		$filterValues[':doc_identidad'] = [
		'value' => $doc_identidad,
		'type' => \PDO::PARAM_STR,
		];

	}		

		if (isset($fieldsForFilter["nombres"]) && !mainModel::isDataEmtpy($fieldsForFilter["nombres"])) {

		 $nombres = mainModel::cleanStringSQL($fieldsForFilter['nombres']);

		array_push($personAttributesFilter, 'nombres = :nombres');
		$filterValues[':nombres'] = [
		'value' => $nombres,
		'type' => \PDO::PARAM_STR,
		];

		}
		 
		if (isset($fieldsForFilter["apellidos"]) && !mainModel::isDataEmtpy($fieldsForFilter["apellidos"])) {

		 $apellidos = mainModel::cleanStringSQL($fieldsForFilter['apellidos']);
			
		array_push($personAttributesFilter, 'apellidos = :apellidos');
		$filterValues[':apellidos'] = [
		'value' => $apellidos,
		'type' => \PDO::PARAM_STR,
		];

		}


		if (isset($fieldsForFilter["fecha_nacimiento"]) && !mainModel::isDataEmtpy($fieldsForFilter["fecha_nacimiento"])) {

		array_push($personAttributesFilter, 'fecha_nacimiento = :fecha_nacimiento');
		$filterValues[':fecha_nacimiento'] = [
		'value' => $fecha_nacimiento,
		'type' => \PDO::PARAM_STR,
		];

		 $fecha_nacimiento = mainModel::cleanStringSQL($fieldsForFilter['fecha_nacimiento']);
		 
		 }

		if (isset($fieldsForFilter["id_genero"]) && !mainModel::isDataEmtpy($fieldsForFilter["id_genero"])) {
		 
		 $id_genero = mainModel::cleanStringSQL($fieldsForFilter['id_genero']);

		array_push($personAttributesFilter, 'id_genero = :id_genero');
		$filterValues[':id_genero'] = [
		'value' => $id_genero,
		'type' => \PDO::PARAM_STR,
		];
	}


			return mainModel::querySelectsCreator('personas',$columnsTable,$personAttributesFilter,$filterValues);

		}



// Funciones Para validar DATOS

public static function isValidDocIdentidad($doc_identidad){
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