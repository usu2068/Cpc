<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * Este código define el controlador principal del componente com_admin en Joomla, el cual gestiona las acciones y la lógica de administración.
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


 // Se evita el acceso al archivo en un entorno fuera de Joomla
// Evita que el archivo se ejecute directamente desde el navegador.
// Protege el sistema de accesos no autorizados.
defined('_JEXEC') or die;

/**
 * Admin Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 * @since       1.6
 */

 /**
  * Define la clase AdminController que extiende JControllerLegacy, lo que significa que sigue la estructura de controladores heredados en Joomla.
  * Esta clase servirá como el controlador principal del componente com_admin.
  * @since 1.6: Indica que esta funcionalidad está disponible desde Joomla 1.6.
  * Actualmente, la clase no tiene métodos, lo que significa que se basa en las funcionalidades heredadas de JControllerLegacy.


  */
class AdminController extends JControllerLegacy
{
}

/**
 * Este controlador actúa como base y no implementa acciones específicas.
 * 📌 Si se añaden métodos, podrían incluir funcionalidades como:
 * Procesamiento de formularios.
 * Gestión de permisos de usuario.
 * Llamadas AJAX.
 * Redirección a vistas específicas.
 */