<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //se evita el acceso al archivo en un entorno fuera de Joomla
defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg. To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 * 
 * Este es un archivo para añadir como especifico la plantilla al renderizado del modulo, Para usarlo se debe establecer el atributo de estilo para el modulo(S) incluido en la plantilla para utilizar el estilo para cada funcion modChrome dado.
 * Por ejemplo, para renderizar un modulo mod_test en el estilo submenu, se deberia usar el siguiente include: <jdoc:include type="module" name="test" style="submenu" />
 * 
 * De este modo, los diseñadores de plantillas tienen el maximo control sobre la representacion de los modulos
 * 
 * AVISO: Todos los metodos de envoltura de chrome deben llamarse: modChrome_{STYLE} y tomar los mismos dos argumentos.
 */

/*
 * Module chrome for rendering the module in a submenu
 * Se usa para mostrar solo el contenido del módulo sin modificar su estructura.
 */
function modChrome_no($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo $module->content;
	}
}

/*
 * Estilo "well": Renderiza el módulo dentro de un div con la clase "well".
 * También agrega un encabezado si el módulo tiene un título.
 */
function modChrome_well($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
		}
		echo $module->content;
		echo "</div>";
	}
}
?>
