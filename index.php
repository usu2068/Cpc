<?php
/**
 * Aqui ya se hace referencia a configuracion del sitio y no a las propiedades del administrador
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
	//verifica si la version de PHP es 5.3.10 o superior, si no, termina la ejecucion y muestra un mensaje de error.
	die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 * 
 * se define una constante para evitar el acceso directo a los archivos.
 * Se utiliza "define()" en lugar de "const" para evitar errores en PHP 5.2 y versiones anteriores
 */
define('_JEXEC', 1);

//verifica si el archivo "defines.php" existe en el directorio actual y lo incluye si esta presente
if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

//si la contante "_JDEFINES" no esta deinida, se define la constante JPATH_BASE
//y se incluye el archivo "defines.php" desde el directorio base de joomla
if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__); //define la ruta base del sitio joomla
	require_once JPATH_BASE . '/includes/defines.php'; //incluye el archivo de definiciones de joomla
}

//requiere el archivo "framework.php", que es el nucleo del framework joomla.
require_once JPATH_BASE . '/includes/framework.php';

// Mark afterLoad in the profiler.
//marca el final de la carga en el perfil de joomla, si la depuracion esta activada.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
//instancia la aplicacion joomla para el sitio publico
$app = JFactory::getApplication('site');

// Execute the application.
//ejecuta la aplicacion
$app->execute();
