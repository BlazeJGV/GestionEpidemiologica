<?php 

	$requestAjax = TRUE;

	require_once "../config/app.php";

		require_once "../controller/casosEpidemiController.php";

		$casosEpidemiController = new casosEpidemiController();
	
if (isset($_POST['operationType']) && $_POST['operationType'] == "register"){

	 	$casosEpidemiController->addcasosEpidemiController($_POST);

}elseif (isset($_POST['operationType']) && $_POST['operationType'] === "delete") {
	 	$casosEpidemiController->deletecasosEpidemiController($_POST);

}elseif (isset($_POST['getParroquias'])) {
	 	$casosEpidemiController->getParroquias();

}elseif (isset($_GET['viewCasosEpidemi'])) {
	 	$casosEpidemiController->getDataTablesCasosEpidemiController();
	
		} else { 
		/*
		session_start(["name"=> "dptoEpidemi"]);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		*/
	}
	
 ?>   
