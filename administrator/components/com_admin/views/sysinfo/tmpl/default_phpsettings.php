<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código genera una tabla en la administración de Joomla que muestra información relevante sobre la configuración de PHP.
 * Se encarga de listar varias directivas y extensiones clave de PHP, indicando su estado actual.
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

  //se evita el acceso al archivo en un entorno fuera de joomla, Evita que el archivo se ejecute directamente desde el navegador., Protege el sistema de accesos no autorizados.
defined('_JEXEC') or die;
?>

<!--Crea un <fieldset> con la clase adminform, usada para aplicar estilos en la administración de Joomla.
	Define un título (legend) con JText::_('COM_ADMIN_RELEVANT_PHP_SETTINGS'), permitiendo traducción automática.-->
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_RELEVANT_PHP_SETTINGS'); ?></legend>

	<!--Define una tabla con la clase table table-striped, lo que aplica estilos predeterminados de Joomla.-->
	<table class="table table-striped">
		<thead>
			<!--Crea dos columnas:
				"COM_ADMIN_SETTING": Nombre de la configuración de PHP.
				"COM_ADMIN_VALUE": Valor actual de la configuración.
			.-->
			<tr>
				<th width="250">
					<?php echo JText::_('COM_ADMIN_SETTING'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_VALUE'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">&#160;
				</td>
			</tr>
		</tfoot>

		<!--Cada fila muestra una configuración de PHP almacenada en $this->php_settings.
			Los valores se renderizan con JHtml::_('phpsetting.tipo', $valor), lo que formatea los valores correctamente.
			Muestra la configuración safe_mode.
			Usa JHtml::_('phpsetting.boolean', $valor), que convierte el valor en "Sí" (true) o "No" (false).
			Muestra el valor de open_basedir, que limita el acceso a directorios en PHP.
			Se usa phpsetting.string para mostrar texto de manera segura.
			Muestra si la extensión zlib está activada.
			Usa phpsetting.set, que determina si está instalada o no.
			-->

		<tbody>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_SAFE_MODE'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['safe_mode']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_OPEN_BASEDIR'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.string', $this->php_settings['open_basedir']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_DISPLAY_ERRORS'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['display_errors']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_SHORT_OPEN_TAGS'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['short_open_tag']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_FILE_UPLOADS'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['file_uploads']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_MAGIC_QUOTES'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['magic_quotes_gpc']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_REGISTER_GLOBALS'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['register_globals']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_OUTPUT_BUFFERING'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.boolean', $this->php_settings['output_buffering']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_SESSION_SAVE_PATH'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.string', $this->php_settings['session.save_path']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_SESSION_AUTO_START'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.integer', $this->php_settings['session.auto_start']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_XML_ENABLED'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.set', $this->php_settings['xml']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_ZLIB_ENABLED'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.set', $this->php_settings['zlib']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_ZIP_ENABLED'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.set', $this->php_settings['zip']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_DISABLED_FUNCTIONS'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.string', $this->php_settings['disable_functions']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_MBSTRING_ENABLED'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.set', $this->php_settings['mbstring']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_ADMIN_ICONV_AVAILABLE'); ?>
				</td>
				<td>
					<?php echo JHtml::_('phpsetting.set', $this->php_settings['iconv']); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>
