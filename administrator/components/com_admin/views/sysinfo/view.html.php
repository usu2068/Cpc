<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código define la vista Sysinfo en el componente com_admin del panel de administración de Joomla.
 * Su función principal es mostrar información del sistema, como la configuración de PHP, la versión de Joomla y la accesibilidad de directorios.
 * 
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
 * Sysinfo View class for the Admin component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 * @since       1.6
 */

 //Maneja la presentación de información del sistema en el backend de Joomla.
class AdminViewSysinfo extends JViewLegacy
{
	//Estas propiedades almacenan los valores obtenidos del modelo.

	/**
	 * @var array some php settings
	 */
	protected $php_settings = null; //configuracion de PHP

	/**
	 * @var array config values
	 */
	protected $config = null; //configuración del sistema

	/**
	 * @var array somme system values
	 */
	protected $info = null; //información general del sistema

	/**
	 * @var string php info
	 */
	protected $php_info = null; //información de PHP (phpinfo)

	/**
	 * @var array informations about writable state of directories
	 */
	protected $directory = null; //estado de permisos en directorios

	/**
	 * Display the view
	 */
	//carga y muestra la vista
	public function display($tpl = null)
	{
		// Access check.
		//verifica si el usuario tiene permisos de administrador (core.admin)
		//si no tiene acceso, muestra un error JError::raiseWarning(404).
		if (!JFactory::getUser()->authorise('core.admin'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		//obtiene los datos del modelo mediante $this->get('NombreDelMétodoDelModelo').
		$this->php_settings	= $this->get('PhpSettings');
		$this->config		= $this->get('config');
		$this->info			= $this->get('info');
		$this->php_info		= $this->get('PhpInfo');
		$this->directory	= $this->get('directory');

		//configuracion de la interfaz
		$this->addToolbar(); //agrega botones a la barra de herramientas del administrador
		$this->_setSubMenu(); //configura un submenu para la navegación
		parent::display($tpl);
	}

	/**
	 * Setup the SubMenu
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @note    Necessary for Hathor compatibility
	 */

	 //carga la plantilla de navegación (navigation.php).
	 //agrega el contenido al buffer del documento en la posicion submenu

	protected function _setSubMenu()
	{
		//se usa try-catch para evitar errores si la plantilla no existe
		try
		{
			$contents = $this->loadTemplate('navigation');
			$document = JFactory::getDocument();
			$document->setBuffer($contents, 'modules', 'submenu');
		}
		catch (Exception $e)
		{
		}
	}

	/**
	 * Setup the Toolbar
	 *
	 * @since   1.6
	 */

	 //este metodo agrega botones a la barra de herramientas del administrador
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('COM_ADMIN_SYSTEM_INFORMATION'), 'info-2 systeminfo'); //muestra el titulo "información del sistema" con un icono
		JToolbarHelper::help('JHELP_SITE_SYSTEM_INFORMATION'); //agrega un boton de ayuda que enlaza a la documentacion de joomla
	}
}

/**
 * Notas:
 * Usa JViewLegacy para la arquitectura MVC en Joomla.
 * Verifica permisos (core.admin) para restringir acceso.
 * Carga datos del modelo (PhpSettings, Config, Info, etc.).
 * Agrega una barra de herramientas con JToolbarHelper.
 * Usa try-catch para prevenir errores en la carga del submenú.
 */
