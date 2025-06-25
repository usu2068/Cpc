<?php 
//actualiza en la base de datos el valor del salario mínimo legal vigente (SMLV) para el año actual. Utiliza una clase personalizada de consultas para ejecutar la operación UPDATE y muestra un mensaje de confirmación al finalizar.

include('/home/aplicati/public_html/utlr/templates/class/consultas.php');
date_default_timezone_set('America/Bogota');

$valor_smlv = $_POST['smlv'];

$consult = new mysql();

$table_smlv = array('jo33_FIC_UTLR_SMLV');
$valC_smlv = array('valor', 'anio');
$val_smlv = array($valor_smlv, date('Y'));
$valU_smlv = array('id = 1');

$sql_smlv = $consult -> sql('U', $table_smlv, $val_smlv, $valC_smlv, $valU_smlv);

echo'Valor Guardado con exito';
?>