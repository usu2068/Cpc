<?php
// Script para subir un archivo CSV de depósitos, validar su tamaño y moverlo a una ruta específica en el servidor

//$upload_351="true";
// Bandera que indica si el archivo de depósitos está autorizado para subir
$upload_depo="true";

// ID de la entidad que se recibe desde un formulario por método POST
$id_ent = $_POST['ent'];

// Nombre que se usará para guardar el archivo (en este caso fijo: formato_anna)
$file_name = 'formato_anna';

// Tamaño del archivo cargado en el input 'arch_351' (aunque este archivo no se usa más adelante)
$uploadedfile_size=$_FILES['arch_351'][size];

// Fecha del fondo o depósito, recibida desde el formulario
$fecha_fonds = $_POST['fech_dep'];

// Validación: si el archivo 'arch_dep' pesa más de 25MB, se rechaza
if ($_FILES[arch_dep][size]>25000000){
	$msg=$msg."Uno de los archivos es mayor que 25Mg, debes reduzcirlo antes de subirlo";
	//$upload_351="false";
	$upload_depo="false";
}

// Validaciones adicionales comentadas (podrían usarse si quieres limitar el tipo de archivo cargado)
/*
// Validación de tipo de archivo para 'arch_351' (debería ser archivo de texto plano)
// if (!($_FILES['arch_351']['type'] == "text/plain")) {
//     $msg = $msg . " El archivo de 351 tiene que ser txt. Otros archivos no son permitidos";
//     $upload_351 = "false";
// }

// Validación de tipo de archivo para 'arch_dep' (debería ser archivo Excel CSV)
// if (!($_FILES['arch_dep']['type'] == "application/vnd.ms-excel")) {
//     $msg = $msg . " El archivo de los depósitos tiene que ser csv. Otros archivos no son permitidos";
//     $upload_depo = "false";
// }
*/

/*if (!($_FILES[arch_351][type] =="text/plain")){
	$msg=$msg." El archivo de 351 tiene que ser txt. Otros archivos No son permitidos";
	$upload_351="false";
}*/

/*if (!($_FILES[arch_dep][type] =="application/vnd.ms-excel")){
	$msg=$msg." El archivo de los depositos tiene que ser csv. Otros archivos No son permitidos";
	$upload_depo="false";
}*/

// Ruta donde se almacenará el archivo una vez subido
$add_depo="../../planos/anna/".$file_name.".csv";

// Verifica si está autorizado subir el archivo de depósitos
if($upload_depo=="true"){
	// Intenta mover el archivo temporal cargado al destino definido
	if(move_uploaded_file ($_FILES[arch_dep][tmp_name], $add_depo)){
		echo "Archivo cargado con exito";
	}else{echo "Error al subir el archivo";}
	// Muestra mensaje de error si no se pudo subir el archivo
}else{echo $msg;}
?>