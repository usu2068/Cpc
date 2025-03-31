<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * Este código genera una interfaz en la administración de Joomla que muestra información del sistema.
 * Utiliza el sistema de pestañas de Bootstrap para organizar diferentes secciones de información.
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // se evita el acceso al archivo en un entorno fuera de Joomla
// Evita que el archivo se ejecute directamente desde el navegador.
// Protege el sistema de accesos no autorizados.
defined('_JEXEC') or die;

// Add specific helper files for html generation
// Agrega archivos de ayuda (helpers/html) al include_path, permitiendo el uso de funciones auxiliares de Joomla.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
?>

<!--
Crea un formulario en Joomla con adminForm.
La acción (action) usa JRoute::_('index.php'), lo que garantiza URLs amigables en Joomla.
-->
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">

<!--
Crea una estructura de diseño fluido usando Bootstrap.
span12 ocupa todo el ancho disponible.
-->
	<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span12">

		<!--CJoomla usa JHtml::_('bootstrap...') para gestionar pestañas con Bootstrap.
		Inicia un conjunto de pestañas (TabSet) con el ID myTab.
		Marca la pestaña "site" como activa.-->

			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'site')); ?>

			<!--Cada pestaña usa JHtml::_('bootstrap.addTab', ...) para agregar una nueva pestaña al conjunto.
			Agrega la pestaña "Información del Sistema" (COM_ADMIN_SYSTEM_INFORMATION).
			Carga la plantilla system.php dentro de la pestaña.
			Cierra la pestaña con bootstrap.endTab.-->

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'site', JText::_('COM_ADMIN_SYSTEM_INFORMATION', true)); ?>
			<?php echo $this->loadTemplate('system'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<!--Muestra la pestaña "Configuración de PHP" (COM_ADMIN_PHP_SETTINGS).
			Carga la plantilla phpsettings.php.-->

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'phpsettings', JText::_('COM_ADMIN_PHP_SETTINGS', true)); ?>
			<?php echo $this->loadTemplate('phpsettings'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'config', JText::_('COM_ADMIN_CONFIGURATION_FILE', true)); ?>
			<?php echo $this->loadTemplate('config'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'directory', JText::_('COM_ADMIN_DIRECTORY_PERMISSIONS', true)); ?>
			<?php echo $this->loadTemplate('directory'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'phpinfo', JText::_('COM_ADMIN_PHP_INFORMATION', true)); ?>
			<?php echo $this->loadTemplate('phpinfo'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<!--Finaliza la estructura de pestañas.-->
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</div>
		<!-- End Content -->
	</div>
</form>
