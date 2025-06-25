<?php 
// Incluye el archivo que contiene la clase para las consultas a la base de datos
include_once('consultas.php');


/**
 * Clase que contiene métodos para generar formularios HTML dinámicamente.
 */
class form{

	/**
     * Genera el formulario HTML para la creación de una entidad.
     * 
     * @param string $id    - ID que será pasado a la función JS.
     * @param string $class - Nombre de la función JavaScript que se ejecutará al hacer clic en "Crear".
     * @return string       - Formulario HTML como cadena.
     */
	function fmr_entidad($id, $class){
		
		// Construcción del evento JavaScript con los parámetros
		$js="javascript:".$class."('".$id."');";
		
		 // Formulario HTML de creación de entidad
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
     * Genera el formulario HTML para creación o edición de usuarios.
     * 
     * @param string  $id     - ID relacionado con el usuario.
     * @param array   $tits   - Títulos/etiquetas para los campos.
     * @param array   $cont   - Contenidos por defecto para los inputs.
     * @param array   $input  - Tipos de input HTML (e.g., text, select).
     * @param array   $ids    - IDs de los inputs.
     * @param string  $js     - Nombre de la función JavaScript a ejecutar.
     * @param string  $tip    - Tipo de operación ('value' para editar, otro para crear).
     * @return string         - Formulario HTML generado como cadena.
     */
	function fmr_usuario($id,$tits,$cont, $input,$ids,$js,$tip){
		
		// Codifica en JSON los IDs de los inputs para pasarlos a JS
		$ar_js = json_encode($ids);		
		$cons = new mysql();
		$sty = 'col-md-6';
		
		// Configura variables dependiendo de si es creación o edición
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
			
			// Realiza consulta inicial para obtener datos del usuario a editar
			$sql_prins = $cons -> sql('S',$table,$val,$valC,$valU);
			$row_prins = mysqli_fetch_array($sql_prins);
			
			// Nueva consulta para llenar opciones del <select> con entidades relacionadas
			$table = array('jo33_FIC_categories');
			$val = array($row_prins[2]);
			$valC = array('parent_id');
		}
		
		// Prepara los valores para insertar en HTML
		$id = '"'.$id.'"';
		$txt_btn = '"'.$txt_btn.'"';
		
		// Crea el botón que ejecutará la función JS con los parámetros
		$js="<a href='javascript:".$js."(".$id.",".$ar_js.", ".$txt_btn.");' class='btn btn-ar btn-primary'>".$txt_btn."</a>";
		
		// Ejecuta la consulta de nuevo para obtener datos para selects (si aplica)
		$sql_prins = $cons -> sql('S',$table,$val,$valC,$valU);
		
		// Inicia construcción del formulario
		$form='	<div class="panel panel-primary"><div class="panel-heading">Creación de Usuario</div><div style="margin: 10px;" id = "'.$div_ms.'"></div><div class="panel-body"><form role="form" method="post" action="">';
		
		// Construcción dinámica de cada campo del formulario
		for($i = 0; $i<count($tits); ++$i){
			$texto = $cont[$i];
			
			// Limpia contenido si viene con etiquetas <p>
			for($k=0;$k<3;++$k) 
				$p = $p.$texto[$k];
			
			if($p == '<p>'){
				$cont[$i] = '';
				$char = strlen($texto);
				$char = $char - 4;
				for($j=3;$j<$char;++$j)
						$cont[$i]=$cont[$i].$texto[$j];
			}else $p = '';

			// Si el campo no es un select, crea input
			if($input[$i] != 'select')
				$form = $form.'<div class="form-group '.$sty.'"><label for="'.$ids[$i].'">'.$tits[$i].'</label><input type="'.$input[$i].'" class="form-control" id="'.$ids[$i].'" '.$tip.'="'.$cont[$i].'"></div>';
			else {
				// Si el campo es un select, crea un dropdown con opciones desde la DB
				$form = $form.'	<div class="form-group '.$sty.'"><label for="'.$ids[$i].'">'.$tits[$i].'</label><select class="form-control" id="'.$ids[$i].'"><option value="0">Seleccione una Entidad</option>';
				
				while($row_prins = mysqli_fetch_array($sql_prins)){
					$form = $form.'<option value='.$row_prins[0].'>'.$row_prins[8].'</option>';
				}
				
				$form = $form.'</select></div>';
			}
		}
		
		// Cierra el formulario y agrega el botón con JS
		$form = $form.$js.'</form></div></div>';
		return $form;
	}
	
}
?>