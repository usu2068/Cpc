<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * 
 * Este código pertenece al componente de administración de Joomla (com_admin) y define una vista (AdminViewProfile) que permite a los usuarios editar su propio perfil.
 * Se encarga de recuperar la información del usuario, validar errores, gestionar la 
 * visualización del formulario y configurar la barra de herramientas en la interfaz de administración.
 * 
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//evita el acceso directo al archivo, mejorando la seguridad
defined('_JEXEC') or die;

/**
 * View class to allow users edit their own profile.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 * @since       1.6
 */
class AdminViewProfile extends JViewLegacy
{
	protected $form; //contiene el formulario de edicion del perfil

	protected $item; //almacena los datos del usuario

	protected $state; //representa el estado actual del componente

	/**
	 * Display the view
	 */
	public function display($tpl = null) //metodo, renderiza la vista
	{
		$this->form			= $this->get('Form'); //obtiene el formulario de edicion
		$this->item			= $this->get('Item'); //recupera la informacion del usuario
		$this->state		= $this->get('State'); //obtiene el estado del componente

		// Check for errors.
		//si get('Errors') devuelve errores, los muestra y detiene la ejecución con JError::raiseError(500).
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		//elimina los valores de los campos de contraseña para evitar que se muestren en el formulario
		$this->form->setValue('password',	null);
		$this->form->setValue('password2',	null);

		//renderiza la vista (parent::display($tpl)) y añade la barra de herramientas con addToolbar().
		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */

	 //Oculta el menú principal en la vista de edición (hidemainmenu = 1), evitando que el usuario navegue fuera de la página accidentalmente.
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', 1);

		//Define el título de la página con el texto COM_ADMIN_VIEW_PROFILE_TITLE y usa un ícono de usuario.
		JToolbarHelper::title(JText::_('COM_ADMIN_VIEW_PROFILE_TITLE'), 'user user-profile');

		// Añade botones a la barra de herramientas:
		JToolbarHelper::apply('profile.apply'); //guarda los cambios al salir de la vista
		JToolbarHelper::save('profile.save'); //guarda los cambios y cierra la vista
		JToolbarHelper::cancel('profile.cancel', 'JTOOLBAR_CLOSE'); //cancela la edicion y vuelve a la pantalla anterior

		//añade un separador y un boton de ayuda
		JToolbarHelper::divider(); //agrega un separador visual en la barra
		JToolbarHelper::help('JHELP_ADMIN_USER_PROFILE_EDIT'); //abre la documentacion de joomla para la edicion de perfiles
	}
}
