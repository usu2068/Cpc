<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * subclase JViewLegacy, usada para mostrar el formulario de edicion/creacion de un cliente en el administrador de joomla.
 * se encarga de:
 * cargar los deatos del cliente y el formulario
 * validar que no haya errores
 * Definir la barrera de harramientas (toolbar) contextual
 * Renderizar la vista en el backend del administrador
 */
defined('_JEXEC') or die;

JLoader::register('BannersHelper', JPATH_COMPONENT . '/helpers/banners.php');

/**
 * View to edit a client.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.5
 */
class BannersViewClient extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * @var  JObject  Object containing permissions for the item
	 */
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= JHelperContent::getActions('com_banners');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= $this->canDo;

		JToolbarHelper::title($isNew ? JText::_('COM_BANNERS_MANAGER_CLIENT_NEW') : JText::_('COM_BANNERS_MANAGER_CLIENT_EDIT'), 'bookmark banners-clients');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create')))
		{
			JToolbarHelper::apply('client.apply');
			JToolbarHelper::save('client.save');
		}

		if (!$checkedOut && $canDo->get('core.create'))
		{
			JToolbarHelper::save2new('client.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolbarHelper::save2copy('client.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('client.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_banners.client', $this->item->id);
			}

			JToolbarHelper::cancel('client.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_BANNERS_CLIENTS_EDIT');
	}
}
