<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Muestra un modal que permite realizar acciones masivas sobre multiples banners a la vez
 * 
 * Permite asignar un nuevo cliente a varios banners a la vez
 * Cambia el idioma de todos los banenrs seleccionados
 * Modifica el estado de la publicacion si aplica
 * Realiza los cambios de forma masiva sin necesidad de editar uno por uno.
 */
defined('_JEXEC') or die;

$published = $this->state->get('filter.published');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('COM_BANNERS_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo JText::_('COM_BANNERS_BATCH_TIP'); ?></p>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('banner.clients'); ?>
				</div>
			</div>
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('batch.language'); ?>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<?php if ($published >= 0) : ?>
				<div class="control-group span6">
					<div class="controls">
						<?php echo JHtml::_('batch.item', 'com_banners'); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-category-id').value='';document.id('batch-client-id').value='';document.id('batch-language-id').value=''" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('banner.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
