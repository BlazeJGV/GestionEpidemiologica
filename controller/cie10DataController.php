
<?php 
		$requestAjax =  TRUE;

	if($requestAjax){
		require_once "../model/cie10DataModel.php";
	}else{
		require_once "./model/cie10DataModel.php";
	}


class cie10DataController extends cie10DataModel
{



	public function updateCie10DataController($files){
	
if ($files['fileCSVCIE10']['type'] !='text/csv' || mainModel::isDataEmtpy($files['fileCSVCIE10']['size'])){
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"El archivo es invalido o esta vacio <br> debe poseer la extension .CSV",
					"Type"=>"error"
				];	

				echo json_encode($alert);

				exit();
	}


if ($files['fileCSVCIE10']['size'] >20000000){
				$alert=[
					"Alert"=>"simple",
					"Title"=>"Datos Invalidos",
					"Text"=>"El archivo no puede ser mayor a 20 Megabytes(MB)",
					"Type"=>"error"
				];	

				echo json_encode($alert);

				exit();
	}



// Se llamara la libreria para procesar e insetrar los valores del .csv


require_once "../view/inc/spout.php";

$filePath = $files['fileCSVCIE10']['tmp_name'];

$reader = $ReaderEntityFactory::createCSVReader();

$reader->open($filePath);



// se recolectan los valores del CSV
 
    $count = 0;

    $dataForQuery = array();

    foreach ($reader->getSheetIterator() as $sheet) {   
        foreach ($sheet->getRowIterator() as $row) {

			foreach($row->getCells() as $key => $cell){
			
			$ceilUTF8=utf8_encode($cell);
				
			$dataForQuery[$count][$key]=$ceilUTF8;

			        }
			  $count++;
			}   
    }


// iniciamos la transaccion sql

try {


$connectDB = mainModel::connectDB();

$connectDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$connectDB->beginTransaction();

$queryDeleteAll = $connectDB->prepare(mainModel::disableForeingDB().' DELETE FROM dataCIE10; '.mainModel::enableForeingDB());

$queryDeleteAll->execute();
$queryDeleteAll->closeCursor();

$sqlQuery = $connectDB->prepare("INSERT INTO dataCIE10(CONSECUTIVO, LETRA, CATALOG_KEY, NOMBRE, CODIGOX, LSEX, LINF, LSUP, TRIVIAL, ERRADICADO, N_INTER, NIN, NINMTOBS, COD_SIT_LESION, NO_CBD, CBD, NO_APH, AF_PRIN, DIA_SIS, CLAVE_PROGRAMA_SIS, COD_COMPLEMEN_MORBI, DEF_FETAL_CM, DEF_FETAL_CBD, CLAVE_CAPITULO, CAPITULO, LISTA1, GRUPO1, LISTA5, RUBRICA_TYPE, YEAR_MODIFI, YEAR_APLICACION, VALID, PRINMORTA, PRINMORBI, LM_MORBI, LM_MORTA, LGBD165, LOMSBECK, LGBD190, NOTDIARIA, NOTSEMANAL, SISTEMA_ESPECIAL, BIRMM, CVE_CAUSA_TYPE, CAUSA_TYPE, EPI_MORTA, EDAS_E_IRAS_EN_M5, CSVE_MATERNAS_SEED_EPID, EPI_MORTA_M5, EPI_MORBI, DEF_MATERNAS, ES_CAUSES, NUM_CAUSES, ES_SUIVE_MORTA, ES_SUIVE_MORB, EPI_CLAVE, EPI_CLAVE_DESC, ES_SUIVE_NOTIN, ES_SUIVE_EST_EPI, ES_SUIVE_EST_BROTE, SINAC, PRIN_SINAC, PRIN_SINAC_GRUPO, DESCRIPCION_SINAC_GRUPO, PRIN_SINAC_SUBGRUPO, DESCRIPCION_SINAC_SUBGRUPO, DAGA, ASTERISCO) VALUES (
:CONSECUTIVO,:LETRA,:CATALOG_KEY,:NOMBRE,:CODIGOX,:LSEX,:LINF,:LSUP,:TRIVIAL,:ERRADICADO,:N_INTER,:NIN,:NINMTOBS,:COD_SIT_LESION,:NO_CBD,:CBD,:NO_APH,:AF_PRIN,:DIA_SIS,:CLAVE_PROGRAMA_SIS,:COD_COMPLEMEN_MORBI,:DEF_FETAL_CM,:DEF_FETAL_CBD,:CLAVE_CAPITULO,:CAPITULO,:LISTA1,:GRUPO1,:LISTA5,:RUBRICA_TYPE,:YEAR_MODIFI,:YEAR_APLICACION,:VALID,:PRINMORTA,:PRINMORBI,:LM_MORBI,:LM_MORTA,:LGBD165,:LOMSBECK,:LGBD190,:NOTDIARIA,:NOTSEMANAL,:SISTEMA_ESPECIAL,:BIRMM,:CVE_CAUSA_TYPE,:CAUSA_TYPE,:EPI_MORTA,:EDAS_E_IRAS_EN_M5,:CSVE_MATERNAS_SEED_EPID,:EPI_MORTA_M5,:EPI_MORBI,:DEF_MATERNAS,:ES_CAUSES,:NUM_CAUSES,:ES_SUIVE_MORTA,:ES_SUIVE_MORB,:EPI_CLAVE,:EPI_CLAVE_DESC,:ES_SUIVE_NOTIN,:ES_SUIVE_EST_EPI,:ES_SUIVE_EST_BROTE,:SINAC,:PRIN_SINAC,:PRIN_SINAC_GRUPO,:DESCRIPCION_SINAC_GRUPO,:PRIN_SINAC_SUBGRUPO,:DESCRIPCION_SINAC_SUBGRUPO,:DAGA,:ASTERISCO);");


for ($indiceFila = 1; $indiceFila < count($dataForQuery); $indiceFila++) {
$CONSECUTIVO = $dataForQuery[$indiceFila][0]; 
$LETRA = $dataForQuery[$indiceFila][1]; 
$CATALOG_KEY = $dataForQuery[$indiceFila][2]; 
$NOMBRE = $dataForQuery[$indiceFila][3]; 
$CODIGOX = $dataForQuery[$indiceFila][4]; 
$LSEX = $dataForQuery[$indiceFila][5]; 
$LINF = $dataForQuery[$indiceFila][6]; 
$LSUP = $dataForQuery[$indiceFila][7]; 
$TRIVIAL = $dataForQuery[$indiceFila][8]; 
$ERRADICADO = $dataForQuery[$indiceFila][9]; 
$N_INTER = $dataForQuery[$indiceFila][10]; 
$NIN = $dataForQuery[$indiceFila][11]; 
$NINMTOBS = $dataForQuery[$indiceFila][12]; 
$COD_SIT_LESION = $dataForQuery[$indiceFila][13]; 
$NO_CBD = $dataForQuery[$indiceFila][14]; 
$CBD = $dataForQuery[$indiceFila][15]; 
$NO_APH = $dataForQuery[$indiceFila][16]; 
$AF_PRIN = $dataForQuery[$indiceFila][17]; 
$DIA_SIS = $dataForQuery[$indiceFila][18]; 
$CLAVE_PROGRAMA_SIS = $dataForQuery[$indiceFila][19]; 
$COD_COMPLEMEN_MORBI = $dataForQuery[$indiceFila][20]; 
$DEF_FETAL_CM = $dataForQuery[$indiceFila][21]; 
$DEF_FETAL_CBD = $dataForQuery[$indiceFila][22]; 
$CLAVE_CAPITULO = $dataForQuery[$indiceFila][23]; 
$CAPITULO = $dataForQuery[$indiceFila][24]; 
$LISTA1 = $dataForQuery[$indiceFila][25]; 
$GRUPO1 = $dataForQuery[$indiceFila][26]; 
$LISTA5 = $dataForQuery[$indiceFila][27]; 
$RUBRICA_TYPE = $dataForQuery[$indiceFila][28]; 
$YEAR_MODIFI = $dataForQuery[$indiceFila][29]; 
$YEAR_APLICACION = $dataForQuery[$indiceFila][30]; 
$VALID = $dataForQuery[$indiceFila][31]; 
$PRINMORTA = $dataForQuery[$indiceFila][32]; 
$PRINMORBI = $dataForQuery[$indiceFila][33]; 
$LM_MORBI = $dataForQuery[$indiceFila][34]; 
$LM_MORTA = $dataForQuery[$indiceFila][35]; 
$LGBD165 = $dataForQuery[$indiceFila][36]; 
$LOMSBECK = $dataForQuery[$indiceFila][37]; 
$LGBD190 = $dataForQuery[$indiceFila][38]; 
$NOTDIARIA = $dataForQuery[$indiceFila][39]; 
$NOTSEMANAL = $dataForQuery[$indiceFila][40]; 
$SISTEMA_ESPECIAL = $dataForQuery[$indiceFila][41]; 
$BIRMM = $dataForQuery[$indiceFila][42]; 
$CVE_CAUSA_TYPE = $dataForQuery[$indiceFila][43]; 
$CAUSA_TYPE = $dataForQuery[$indiceFila][44]; 
$EPI_MORTA = $dataForQuery[$indiceFila][45]; 
$EDAS_E_IRAS_EN_M5 = $dataForQuery[$indiceFila][46]; 
$CSVE_MATERNAS_SEED_EPID = $dataForQuery[$indiceFila][47]; 
$EPI_MORTA_M5 = $dataForQuery[$indiceFila][48]; 
$EPI_MORBI = $dataForQuery[$indiceFila][49]; 
$DEF_MATERNAS = $dataForQuery[$indiceFila][50]; 
$ES_CAUSES = $dataForQuery[$indiceFila][51]; 
$NUM_CAUSES = $dataForQuery[$indiceFila][52]; 
$ES_SUIVE_MORTA = $dataForQuery[$indiceFila][53]; 
$ES_SUIVE_MORB = $dataForQuery[$indiceFila][54]; 
$EPI_CLAVE = $dataForQuery[$indiceFila][55]; 
$EPI_CLAVE_DESC = $dataForQuery[$indiceFila][56]; 
$ES_SUIVE_NOTIN = $dataForQuery[$indiceFila][57]; 
$ES_SUIVE_EST_EPI = $dataForQuery[$indiceFila][58]; 
$ES_SUIVE_EST_BROTE = $dataForQuery[$indiceFila][59]; 
$SINAC = $dataForQuery[$indiceFila][60]; 
$PRIN_SINAC = $dataForQuery[$indiceFila][61]; 
$PRIN_SINAC_GRUPO = $dataForQuery[$indiceFila][62]; 
$DESCRIPCION_SINAC_GRUPO = $dataForQuery[$indiceFila][63]; 
$PRIN_SINAC_SUBGRUPO = $dataForQuery[$indiceFila][64]; 
$DESCRIPCION_SINAC_SUBGRUPO = $dataForQuery[$indiceFila][65]; 
$DAGA = $dataForQuery[$indiceFila][66]; 
$ASTERISCO = $dataForQuery[$indiceFila][67];


$sqlQuery->execute(array("CONSECUTIVO"=>$CONSECUTIVO,
"LETRA"=>$LETRA, 
"CATALOG_KEY"=>$CATALOG_KEY, 
"NOMBRE"=>$NOMBRE, 
"CODIGOX"=>$CODIGOX, 
"LSEX"=>$LSEX, 
"LINF"=>$LINF, 
"LSUP"=>$LSUP, 
"TRIVIAL"=>$TRIVIAL, 
"ERRADICADO"=>$ERRADICADO, 
"N_INTER"=>$N_INTER, 
"NIN"=>$NIN, 
"NINMTOBS"=>$NINMTOBS, 
"COD_SIT_LESION"=>$COD_SIT_LESION, 
"NO_CBD"=>$NO_CBD, 
"CBD"=>$CBD, 
"NO_APH"=>$NO_APH, 
"AF_PRIN"=>$AF_PRIN, 
"DIA_SIS"=>$DIA_SIS, 
"CLAVE_PROGRAMA_SIS"=>$CLAVE_PROGRAMA_SIS, 
"COD_COMPLEMEN_MORBI"=>$COD_COMPLEMEN_MORBI, 
"DEF_FETAL_CM"=>$DEF_FETAL_CM, 
"DEF_FETAL_CBD"=>$DEF_FETAL_CBD, 
"CLAVE_CAPITULO"=>$CLAVE_CAPITULO, 
"CAPITULO"=>$CAPITULO, 
"LISTA1"=>$LISTA1, 
"GRUPO1"=>$GRUPO1, 
"LISTA5"=>$LISTA5, 
"RUBRICA_TYPE"=>$RUBRICA_TYPE, 
"YEAR_MODIFI"=>$YEAR_MODIFI, 
"YEAR_APLICACION"=>$YEAR_APLICACION, 
"VALID"=>$VALID, 
"PRINMORTA"=>$PRINMORTA, 
"PRINMORBI"=>$PRINMORBI, 
"LM_MORBI"=>$LM_MORBI, 
"LM_MORTA"=>$LM_MORTA, 
"LGBD165"=>$LGBD165, 
"LOMSBECK"=>$LOMSBECK, 
"LGBD190"=>$LGBD190, 
"NOTDIARIA"=>$NOTDIARIA, 
"NOTSEMANAL"=>$NOTSEMANAL, 
"SISTEMA_ESPECIAL"=>$SISTEMA_ESPECIAL, 
"BIRMM"=>$BIRMM, 
"CVE_CAUSA_TYPE"=>$CVE_CAUSA_TYPE, 
"CAUSA_TYPE"=>$CAUSA_TYPE, 
"EPI_MORTA"=>$EPI_MORTA, 
"EDAS_E_IRAS_EN_M5"=>$EDAS_E_IRAS_EN_M5, 
"CSVE_MATERNAS_SEED_EPID"=>$CSVE_MATERNAS_SEED_EPID, 
"EPI_MORTA_M5"=>$EPI_MORTA_M5, 
"EPI_MORBI"=>$EPI_MORBI, 
"DEF_MATERNAS"=>$DEF_MATERNAS, 
"ES_CAUSES"=>$ES_CAUSES, 
"NUM_CAUSES"=>$NUM_CAUSES, 
"ES_SUIVE_MORTA"=>$ES_SUIVE_MORTA, 
"ES_SUIVE_MORB"=>$ES_SUIVE_MORB, 
"EPI_CLAVE"=>$EPI_CLAVE, 
"EPI_CLAVE_DESC"=>$EPI_CLAVE_DESC, 
"ES_SUIVE_NOTIN"=>$ES_SUIVE_NOTIN, 
"ES_SUIVE_EST_EPI"=>$ES_SUIVE_EST_EPI, 
"ES_SUIVE_EST_BROTE"=>$ES_SUIVE_EST_BROTE, 
"SINAC"=>$SINAC, 
"PRIN_SINAC"=>$PRIN_SINAC, 
"PRIN_SINAC_GRUPO"=>$PRIN_SINAC_GRUPO, 
"DESCRIPCION_SINAC_GRUPO"=>$DESCRIPCION_SINAC_GRUPO, 
"PRIN_SINAC_SUBGRUPO"=>$PRIN_SINAC_SUBGRUPO, 
"DESCRIPCION_SINAC_SUBGRUPO"=>$DESCRIPCION_SINAC_SUBGRUPO, 
"DAGA"=>$DAGA, 
"ASTERISCO"=>$ASTERISCO));

}

    $connectDB->commit();

			$alert=[
				"Alert"=>"simple",
				"Title"=>"Operacion Exitosa",
				"Text"=>"Catalogo CIE-10 Actualizado",
				"Type"=>"success"
			];

				echo json_encode($alert);
    		exit();
}catch (Exception $e) {
    $connectDB->rollback();
    


			$alert=[
				"Alert"=>"simple",
				"Title"=>"Erro en la Base de Datos",
				"Text"=>"
				Error: ". $e->getMessage()."
				",
				"Type"=>"success"
			];

			echo json_encode($alert);
    		exit();

}
}


	public static function paginatecie10DataController($parametersQuery){

	$sqlForGetCie10Catalog = 'SELECT * FROM dataCIE10 ';

	$idCapitulo = mainModel::cleanStringSQL($parametersQuery['idCapitulo']);

 	if (!mainModel::isDataEmtpy($idCapitulo)) {

 		$sqlForGetCie10Catalog = $sqlForGetCie10Catalog." WHERE CLAVE_CAPITULO = '$idCapitulo'";
}

 	$queryForGetCie10Catalog = mainModel::runSimpleQuery($sqlForGetCie10Catalog);


			$dataJsonRocords = array();

			while($rows = $queryForGetCie10Catalog->fetch(PDO::FETCH_ASSOC)) {

                   $dataFields=array();				

					$dataJsonRocords[] = $rows;
			}

//			echo json_encode($dataJsonRocords, JSON_UNESCAPED_UNICODE);

			echo json_encode($dataJsonRocords);

			exit();

	}


}
 ?>