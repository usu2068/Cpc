<?php
/**
 * Este código es parte del componente de administración de Joomla (com_admin).
 * Su función es mostrar una tabla con el estado de los permisos de los directorios del sistema.
 * Utiliza HTML y PHP para generar dinámicamente la tabla con los datos almacenados en la variable $this->directory.
 * 
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

  //se evita el acceso al archivo en un entorno fuera de joomla
defined('_JEXEC') or die;
?>

<!--
Crea un conjunto de campos (fieldset) con la clase adminform, usada en la administración de Joomla para aplicar estilos.
El título (legend) se traduce dinámicamente con JText::_('COM_ADMIN_DIRECTORY_PERMISSIONS'), permitiendo la compatibilidad multilingüe.
-->
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_DIRECTORY_PERMISSIONS'); ?></legend>

	<!--
	Crea una tabla con la clase table table-striped, que agrega un estilo visual predeterminado.
	-->
	<table class="table table-striped">

	<!--
	Define los encabezados de la tabla:
	primera columna: Nombre del directorio (COM_ADMIN_DIRECTORY).
	Segunda columna: Estado del permiso (COM_ADMIN_STATUS).
	JText::_('COM_ADMIN_DIRECTORY') y JText::_('COM_ADMIN_STATUS') permiten traducir dinámicamente los encabezados.
	-->
		<thead>
			<tr>
				<th width="650">
					<?php echo JText::_('COM_ADMIN_DIRECTORY'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_STATUS'); ?>
				</th>
			</tr>
		</thead>

	<!--
	Agrega un pie de tabla con una celda vacía (&#160;) que mantiene la estructura visual.
	-->
		<tfoot>
			<tr>
				<td colspan="2">&#160;</td>
			</tr>
		</tfoot>

	<!--
	Recorre la variable $this->directory, que contiene información sobre los directorios y sus permisos.
	Cada directorio tiene dos elementos clave:
	$dir → Nombre del directorio.
	$info['message'] → Mensaje de estado sobre el directorio.
	$info['writable'] → Indica si el directorio es escribible o no.

	Uso de JHtml::_('directory.message', $dir, $info['message']):
	Muestra el nombre del directorio con un mensaje de estado.

	Uso de JHtml::_('directory.writable', $info['writable']):
	Muestra si el directorio es escribible o no, aplicando el formato adecuado.
	-->
		<tbody>
			<?php foreach ($this->directory as $dir => $info) : ?>
				<tr>
					<td>
						<?php echo JHtml::_('directory.message', $dir, $info['message']); ?>
					</td>
					<td>
						<?php echo JHtml::_('directory.writable', $info['writable']); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</fieldset>
