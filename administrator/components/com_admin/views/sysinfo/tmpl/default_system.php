<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código genera un fieldset con información del sistema en Joomla.
 * Se muestra en una tabla con datos clave como la versión de PHP, base de datos, servidor web, versión de Joomla, entre otros.
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 ////se evita el acceso al archivo en un entorno fuera de Joomla
// Evita que el archivo se ejecute directamente desde el navegador.
// Protege el sistema de accesos no autorizados.
defined('_JEXEC') or die;
?>

<!--Genera una tabla con clases de Bootstrap (table table-striped) para mejorar el diseño.-->
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_SYSTEM_INFORMATION'); ?></legend>

	<!--
Crea un grupo de elementos (fieldset) en el formulario de administración.
Establece un título (legend) traducido con JText::_('COM_ADMIN_SYSTEM_INFORMATION').
-->
	<table class="table table-striped">

<!--
define dos columnas, la primera con el nombre de la configuracion y la segunda con el valor de la configuración 
-->
		<thead>
			<tr>
				<th width="25%">
					<?php echo JText::_('COM_ADMIN_SETTING'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_VALUE'); ?>
				</th>
			</tr>
		</thead>

		<!--agrega un pie de tabla vacio con &#160; (espacio en blanco)-->
		<tfoot>
			<tr>
				<td colspan="2">&#160;</td>
			</tr>
		</tfoot>

		<!--cada fila de la tabla muestra una configuracion con su respectivo valor-->
		<tbody>

		<!--Muestra el sistema operativo en el que corre PHP ($this->info['php']).-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PHP_BUILT_ON'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['php']; ?>
				</td>
			</tr>

			<!--Muestra la versión del sistema de base de datos ($this->info['dbversion']).-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_DATABASE_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['dbversion']; ?>
				</td>
			</tr>

			<!--Muestra la versión de Joomla instalada ($this->info['version']).-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_DATABASE_COLLATION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['dbcollation']; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PHP_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['phpversion']; ?>
				</td>
			</tr>

			<!--Obtiene información del servidor web ($this->info['server']) con ayuda de JHtml::_('system.server', ...).-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_WEB_SERVER'); ?></strong>
				</td>
				<td>
					<?php echo JHtml::_('system.server', $this->info['server']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_WEBSERVER_TO_PHP_INTERFACE'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['sapi_name']; ?>
				</td>
			</tr>
			<!--Muestra la versión de Joomla instalada ($this->info['version']).-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_JOOMLA_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['version']; ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PLATFORM_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['platform']; ?>
				</td>
			</tr>

			<!--Muestra el User-Agent del navegador en uso ($this->info['useragent']). Se usa htmlspecialchars() para prevenir ataques XSS.-->
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_USER_AGENT'); ?></strong>
				</td>
				<td>
					<?php echo htmlspecialchars($this->info['useragent']); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>
