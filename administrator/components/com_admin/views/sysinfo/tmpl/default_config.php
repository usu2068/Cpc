<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código forma parte del componente de administración de Joomla (com_admin).
 * Su función es mostrar una tabla con la configuración del sistema, donde se listan los ajustes y sus respectivos valores.
 * El código se basa en HTML y PHP para generar dinámicamente una tabla con los datos almacenados en la variable $this->config.
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
Define un conjunto de campos (fieldset) con la clase adminform, usada para aplicar estilos en la administración de Joomla.
El título (legend) se traduce dinámicamente con JText::_('COM_ADMIN_CONFIGURATION_FILE'), lo que permite que la interfaz sea multilingüe.
-->
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_CONFIGURATION_FILE'); ?></legend>

	<!--Crea una tabla con la clase table table-striped, que proporciona estilos visuales en Joomla.-->
	<table class="table table-striped">

		<!--Define los encabezados de la tabla:
				Primera columna: Nombre del ajuste (COM_ADMIN_SETTING).
				Segunda columna: Valor del ajuste (COM_ADMIN_VALUE).
				JText::_('COM_ADMIN_SETTING') y JText::_('COM_ADMIN_VALUE') permiten traducir los encabezados según el idioma del usuario.
		-->
		<thead>
			<tr>
				<th width="300">
					<?php echo JText::_('COM_ADMIN_SETTING'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_VALUE'); ?>
				</th>
			</tr>
		</thead>

		<!--Agrega un pie de tabla con una celda vacía (&#160;) que sirve para mantener la estructura visual.-->
		<tfoot>
			<tr>
				<td colspan="2">&#160;</td>
			</tr>
		</tfoot>

		<!--Recorre la configuración del sistema ($this->config) y genera una fila por cada ajuste.
			$key → Nombre del ajuste.
			$value → Valor asociado.

			Protección contra XSS (htmlspecialchars($value, ENT_QUOTES)):
			Convierte caracteres especiales en entidades HTML para evitar ataques de inyección de código
		-->
		<tbody>
			<?php foreach ($this->config as $key => $value): ?>
				<tr>
					<td>
						<?php echo $key; ?> 
					</td>
					<td>
						<?php echo htmlspecialchars($value, ENT_QUOTES); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</fieldset>
