<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Inicializa el entorno del componente, verificar permisos y despacha la tarea
 * solicitada al controlador correspondiente
 * 
 * Es el controlador principal de com_banners y se encarga de:
 * verifica si el usuario tiene permisos para acceder al componente
 * Carga comportamientos necesarios del sistema (como mantener el estado de las pestañas)
 * Ejecutar la tarea (task) solicitada por el usuario
 * Redirigir la aplicacion al destino que indique el controlador
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_banners'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Execute the task.
$controller = JControllerLegacy::getInstance('Banners');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
