<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 *  Este código pertenece a la administración de Joomla y muestra información sobre la configuración de PHP en el servidor.
 * Se basa en la variable $this->php_info, que almacena los datos generados por la función phpinfo() o un equivalente en Joomla.
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
Crea un contenedor <fieldset> con la clase adminform, usada en la administración de Joomla para aplicar estilos.
	Define un título (legend) con JText::_('COM_ADMIN_PHP_INFORMATION'), permitiendo traducción automática a múltiples idiomas.
-->
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_PHP_INFORMATION'); ?></legend>
<!-- 
Muestra el contenido de $this->php_info, que probablemente contenga:
	Salida de phpinfo() → Configuración detallada de PHP.
	Información obtenida a través de una función de Joomla que procesa phpinfo() y lo formatea para la administración.
-->
	<?php echo $this->php_info; ?>
</fieldset>
