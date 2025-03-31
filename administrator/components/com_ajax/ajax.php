<?php
/**
 * Archivo de entrada principal para el componente AJAX en joomla
 * @package     Joomla.Administrator
 * @subpackage  com_ajax
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //Restringe el acceso directo al archivo por seguridad
defined('_JEXEC') or die;

//incluye el archivo principal del componente JAX desde el sitio frontend
require_once JPATH_SITE . '/components/com_ajax/ajax.php';
