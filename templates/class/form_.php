<?php 
// Incluye el archivo donde están las consultas SQL (clase mysql)
include_once('consultas.php');


/**
 * Clase form
 * Genera formularios HTML dinámicamente, especialmente para crear entidades y usuarios.
 */
class form{

	/**
	 * Genera un formulario HTML para crear una nueva entidad.
	 * 
	 * @param string $id     - ID que se pasará a la función JS.
	 * @param string $class  - Nombre de la función JavaScript que se invocará al presionar "Crear".
	 * @return string        - HTML del formulario.
	 */

	function fmr_entidad($id, $class){
		
		// Genera la llamada JavaScript en el botón "Crear"
		$js="javascript:'".$class."'('".$id."');";
		
		// HTML del formulario
		$form='		
			<div class="panel panel-primary">
				<div class="panel-heading">Creación de Entidad</div>
				<div style="margin: 10px;" id = "mjs_ent"></div>
				<div class="panel-body">
					<form role="form" method="post" action="">
					  <div class="form-group">
						<label for="nombre_ent">Nombre Entidad</label>
						<input type="text" class="form-control" id="nombre_ent" placeholder="Nombre Completo">
					  </div>
					  <div class="form-group">
						<label for="nit_ent">Nit</label>
						<input type="text" class="form-control" id="nit_ent" placeholder="Nit">
					  </div>
					  <div class="form-group">
						<label for="logo_ent">Logo</label>
						<input type="text" class="form-control" id="logo_ent" placeholder="Logo">
					  </div>
					  <a href="'.$js.'" class="btn btn-ar btn-primary">Crear</a>
					</form>
				</div>
			</div>'
		;
		
		return $form;
	}
	
	/**
	 * Genera un formulario HTML para crear o editar un usuario.
	 * 
	 * @param string  $id      - Identificador del usuario o entidad.
	 * @param array   $tits    - Títulos de los campos (etiquetas <label>).
	 * @param array   $cont    - Contenido por defecto para los campos.
	 * @param array   $input   - Tipos de input (text, email, select, etc).
	 * @param array   $ids     - IDs y nombres de los campos (atributo id).
	 * @param string  $js      - Nombre de la función JavaScript para el botón.
	 * @param string  $tip     - Modo del formulario: "value" para editar, otro para crear.
	 * @return string          - HTML generado del formulario.
	 */

	function fmr_usuario($id,$tits,$cont, $input,$ids,$js,$tip){
		
		// Codifica el arreglo de IDs como JSON para pasarlo a JS
		$ar_js = json_encode($ids);		
		$cons = new mysql(); // Instancia para ejecutar consultas
		$sty = 'col-md-6'; // Clases Bootstrap para estilos
		
		// Configuración según tipo de formulario (crear o editar)
		if($tip != 'value'){
			$txt_btn = 'Crear';
			$div_ms = 'mjs_usu_new';
			$table = array('jo33_FIC_categories','jo33_FIC_content');
			$val = array($id,'jo33_FIC_content.catid');
			$valC = array('jo33_FIC_content.alias','jo33_FIC_categories.parent_id');
		}else{	
			$txt_btn = 'Editar';
			$div_ms = 'mjs_usu_edi_'.$id;
			$table = array('jo33_FIC_categories','jo33_FIC_content');
			$val = array($id,'jo33_FIC_content.catid','jo33_FIC_categories.parent_id');
			$valC = array('jo33_FIC_content.alias','jo33_FIC_categories.id','jo33_FIC_categories.parent_id');
			
			// Consulta los valores actuales para edición
			$sql_prins = $cons -> sql('S',$table,$val,$valC,$valU);
			$row_prins = mysql_fetch_array($sql_prins);
			
			$table = array('jo33_FIC_categories');
			$val = array($row_prins[2]);
			$valC = array('parent_id');
		}
		
		// Genera botón de envío con llamada JS
		$id = '"'.$id.'"';
		$txt_btn = '"'.$txt_btn.'"';
		
		$js="<a href='javascript:".$js."(".$id.",".$ar_js.", ".$txt_btn.");' class='btn btn-ar btn-primary'>".$txt_btn."</a>";
		
		// Realiza una consulta para llenar select (si hay)
		$sql_prins = $cons -> sql('S',$table,$val,$valC,$valU);
		
		$form='	<div class="panel panel-primary"><div class="panel-heading">Creación de Usuario</div><div style="margin: 10px;" id = "'.$div_ms.'"></div><div class="panel-body"><form role="form" method="post" action="">';
		
		// Recorre todos los campos para construir inputs/selects
		for($i = 0; $i<count($tits); ++$i){
			$texto = $cont[$i];
			
			for($k=0;$k<3;++$k) 
				$p = $p.$texto[$k];
			
			if($p == '<p>'){
				$cont[$i] = '';
				$char = strlen($texto);
				$char = $char - 4;
				for($j=3;$j<$char;++$j)
						$cont[$i]=$cont[$i].$texto[$j];
			}else $p = '';

			// Si es input normal
			if($input[$i] != 'select')
				$form = $form.'<div class="form-group '.$sty.'"><label for="'.$ids[$i].'">'.$tits[$i].'</label><input type="'.$input[$i].'" class="form-control" id="'.$ids[$i].'" '.$tip.'="'.$cont[$i].'"></div>';
			// Si es un select
			else {
				$form = $form.'	<div class="form-group '.$sty.'"><label for="'.$ids[$i].'">'.$tits[$i].'</label><select class="form-control" id="'.$ids[$i].'"><option value="0">Seleccione una Entidad</option>';
				
				// Agrega opciones del select desde BD
				while($row_prins = mysql_fetch_array($sql_prins)){
					$form = $form.'<option value='.$row_prins[0].'>'.$row_prins[8].'</option>';
				}
				
				$form = $form.'</select></div>';
			}
		}
		
		// Agrega botón final
		$form = $form.$js.'</form></div></div>';
		return $form;
	}
	
}
?>

<!--
Genera dos formulario
Uno para crear entidades (fmr_entidad).
Otro para crear o editar usuarios (fmr_usuario).

Llama funciones JavaScript al hacer clic en los botones.
Hace consultas SQL a través de la clase mysql para obtener datos y rellenar selectores.
-->