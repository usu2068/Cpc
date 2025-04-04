<?php
include_once('/home/aplicati/public_html/utlr/templates/class/consultas.php');
include_once('/home/aplicati/public_html/utlr/templates/class/list.php');
//date_default_timezone_set('America/Bogota');

$asig = asignacion_tit();
echo $asig;

function asignacion_tit(){

	$consult = new mysql();
	$lista = new listado();
	
	$tip = $_POST['tip'];
	$id_tit = $_POST['id_tit'];
	$id_grptit = $_POST['id_grptit'];

	if($tip == 0){
		$table = array('jo33_FIC_UTLR_titulos_X_titulos_pol_tip');
		$valC = array('id_titulos', 'id_titulos_pol_tip', 'active');
		
		$valC_tit_asig = array('id_titulos_pol_tip'); 
	/*/**/
	}elseif($tip == 1){
		$table = array('jo33_FIC_UTLR_titulos_has_jo33_FIC_UTLR_titulos_pol_fon');
		$valC = array('jo33_FIC_UTLR_titulos_id', 'jo33_FIC_UTLR_titulos_pol_fon_id', 'active');
		
		$valC_tit_asig = array('jo33_FIC_UTLR_titulos_pol_fon_id');
	}

	$val = array($id_tit, $id_grptit, '1');
	$sql = $consult -> sql('I', $table, $val, $valC, $valU);

	$val_tit_asig = array($id_grptit);
	$sql_tit_asig = $consult -> sql('S', $table, $val_tit_asig, $valC_tit_asig, $valU);
	$contenido_asig_tit = $lista -> paginacion($sql_tit_asig, $tip);

	return $contenido_asig_tit;
	//echo 'hola';
}
?>

<!--
Lógica según el tipo (tip):
Si tip == 0 (asignación a política):
Tabla: jo33_FIC_UTLR_titulos_X_titulos_pol_tip

Columnas: id_titulos, id_titulos_pol_tip, active

Si tip == 1 (asignación a fondo):
Tabla: jo33_FIC_UTLR_titulos_has_jo33_FIC_UTLR_titulos_pol_fon

Columnas: jo33_FIC_UTLR_titulos_id, jo33_FIC_UTLR_titulos_pol_fon_id, active

Este archivo permite:

Asignar un título a un tipo de grupo (tipo de política o fondo).

Guardar esa relación en la base de datos.

Consultar y mostrar la lista de títulos ya asignados al grupo.
-->