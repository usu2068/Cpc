<?php
// Incluye la clase de consultas a la base de datos
include_once('/home/aplicati/public_html/utlr/templates/class/consultas.php');
// Incluye la clase para manejar listados y paginación
include_once('/home/aplicati/public_html/utlr/templates/class/list.php');

// Instancia de la clase mysql para ejecutar consultas
$consult = new mysql();
// Instancia de la clase listado para manejar resultados paginados
$lista = new listado();

// Recupera los valores enviados por POST
$tip = $_POST['tip']; // Tipo de asignación (0: política tipo, 1: fondo)
$est = $_POST['est']; // Estado actual (0: inactivo, 1: activo)
$id_tit = $_POST['id_tit']; // ID del título
$id_grptit = $_POST['id_grptit']; // ID del grupo del título (política tipo o fondo)

// Determina la tabla y condiciones según el tipo de asignación
if($tip == 0){
	$table = array('jo33_FIC_UTLR_titulos_X_titulos_pol_tip');
	$valU = array('id_titulos ='.$id_tit, 'id_titulos_pol_tip ='.$id_grptit);
	$valC_tit_asig = array('id_titulos_pol_tip');
	
}elseif($tip == 1){
	/**/
	$table = array('jo33_FIC_UTLR_titulos_has_jo33_FIC_UTLR_titulos_pol_fon');
	$valU = array('	id_titulos ='.$id_tit, 'id_titulos_pol_fon ='.$id_grptit);
	$valC_tit_asig = array('id_titulos_pol_fon');
	
}

// Cambia el estado (activo ↔ inactivo)
if($est == 0)$est = 1;
elseif($est == 1)$est = 0;

// Datos para actualizar el estado
$valC = array('active');
$val = array($est);

// Ejecuta la consulta de actualización
$sql = $consult -> sql('U', $table, $val, $valC, $valU);

// Prepara los valores para consultar los datos actualizados
$val_tit_asig = array($id_grptit);

// Ejecuta la consulta para obtener los datos actualizados
$sql_tit_asig = $consult -> sql('S', $table, $val_tit_asig, $valC_tit_asig, $valU_tit_asig);

// Aplica paginación al resultado obtenido
$contenido_asig_tit = $lista -> paginacion($sql_tit_asig, $tip);

// Devuelve el contenido actualizado
echo $contenido_asig_tit;
?>