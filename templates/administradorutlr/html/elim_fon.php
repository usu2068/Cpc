<?php
//desactiva un registro especifico de la tabla jo33_FIC_UTLR_fondos en la base de datos, estableciendo los campos active y trash en 0. la accion es ejecutada por medio de una clase personalizada de consultas SQL (mysql).

// Se incluye la clase personalizada de consultas a base de datos
include('/home/aplicati/public_html/utlr/templates/class/consultas.php');

// Se establece la zona horaria a Bogotá, Colombia
date_default_timezone_set('America/Bogota');

// Instancia del objeto para manejar consultas SQL
$consult = new mysql();

// Tabla donde se hará la actualización
$table = array('jo33_FIC_UTLR_fondos');

// Valores a actualizar: se establece active = 0 y trash = 0
$val = array('0', '0');

// Nombres de las columnas a actualizar
$valC = array('active', 'trash');

// Condición WHERE para especificar qué registro actualizar (id recibido por POST)
$valU = array('id ='.$_POST['id_tip']);

// Se ejecuta la consulta tipo UPDATE ('U') con los parámetros definidos
$sql_elim = $consult -> sql('U', $table, $val, $valC, $valU);
?>