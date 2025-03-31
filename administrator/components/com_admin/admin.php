<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código establece un controlador en Joomla para gestionar solicitudes en el componente com_admin.
 * Utiliza JControllerLegacy para ejecutar tareas basadas en la entrada del usuario.
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //se evita el acceso al archivo en un entorno fuera de Joomla, Evita que el archivo se ejecute directamente desde el navegador, Protege el sistema de accesos no autorizados.
defined('_JEXEC') or die;

//restauracion del estado de las pestañas, Permite que Joomla recuerde la última pestaña abierta al recargar la página.
JHtml::_('behavior.tabstate');

// No access check.

//Crea una instancia del controlador principal del componente com_admin.
//JControllerLegacy es la clase base para controladores en Joomla.
$controller = JControllerLegacy::getInstance('Admin');

//Obtiene la tarea (task) desde la entrada del usuario.
//Ejecuta la acción correspondiente en el controlador.
$controller->execute(JFactory::getApplication()->input->get('task'));

//Redirige al usuario a la página correspondiente después de ejecutar la tarea.
//Si la tarea genera un mensaje (setRedirect()), se mostrará en la redirección.
$controller->redirect();
