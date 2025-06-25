<?php
/**
 * el paquete joomla.administrator, esta definido para que el administrador del sitio pueda usar los elementos de joomla sobre la cual esta montada la pagina
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //evita el acceso y/o ejecucion del archivo fuera del ambiente de joomla
defined('_JEXEC') or die;

// Include the component HTML helpers.
//incluye ayudantes de HTML del componente para utilizar funciones adicionales en la vista
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

//habilita la validacion del formulario en joomla
JHtml::_('behavior.formvalidation');

// Load chosen.css
//carga chosen,css para mejorar la apariencia de los elementos <select>
JHtml::_('formbehavior.chosen', 'select');

// Get the form fieldsets.
//obtiene los fieldsets definidos en el formulario
$fieldsets = $this->form->getFieldsets();
?>

<!--
Define la función Joomla.submitbutton, que se ejecuta cuando se envía el formulario.
Si la tarea (task) es cancelar (profile.cancel), el formulario se envía sin validación.
Si la tarea requiere validación, usa document.formvalidator.isValid() para asegurarse de que el formulario está correctamente completado.
-->
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'profile.cancel' || document.formvalidator.isValid(document.id('profile-form')))
		{
			Joomla.submitform(task, document.getElementById('profile-form'));
		}
	}
</script>

<!--
action: Define la URL de destino con el ID del usuario a editar.
method="post": Usa el método POST para enviar datos.
name="adminForm" y id="profile-form": Identificadores del formulario.
class="form-validate form-horizontal":
form-validate: Habilita la validación en Joomla.
form-horizontal: Usa el diseño de formularios de Bootstrap.
enctype="multipart/form-data": Permite la carga de archivos.
-->
<form action="<?php echo JRoute::_('index.php?option=com_admin&view=profile&layout=edit&id=' . $this->item->id); ?>" method="post" name="adminForm" id="profile-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	
<!--Inicia un conjunto de pestañas Bootstrap con el identificador mytab y marca account como la activa-->
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'account')); ?>

<!--Crea una pestaña "Detalles de Cuenta" (COM_ADMIN_USER_ACCOUNT_DETAILS).
	Itera sobre los campos del fieldset user_details y genera los campos de entrada con sus etiquetas.
-->
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('COM_ADMIN_USER_ACCOUNT_DETAILS', true)); ?>
	<?php foreach ($this->form->getFieldset('user_details') as $field) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?></div>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
	<?php endforeach; ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<!--
	Itera sobre todos los fieldsets para generar pestañas dinámicamente.
	Si el fieldset es user_details, lo ignora (ya se mostró antes).
	Crea una pestaña para cada fieldset restante.
	Muestra los campos del fieldset, diferenciando entre ocultos (hidden) y visibles.
	-->

	<!--finaliza el conjunto de pestañas de bootstrap-->
	<?php foreach ($fieldsets as $fieldset) : ?>
		<?php
		if ($fieldset->name == 'user_details')
		{
			continue;
		}
		?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', $fieldset->name, JText::_($fieldset->label, true)); ?>
		<?php foreach ($this->form->getFieldset($fieldset->name) as $field) : ?>
			<?php if ($field->hidden) : ?>
				<div class="control-group">
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			<?php else: ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?></div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endforeach; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<!--<input type="hidden" name="task" value="" />: Define un campo oculto para almacenar la acción a realizar.
				JHtml::_('form.token'):
					Agrega un token de seguridad CSRF para proteger contra ataques de falsificación de solicitudes.
	-->
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
