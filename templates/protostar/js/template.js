/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.2
 */

(function($)
{
	$(document).ready(function()
	{
		// Activa los tooltips en todos los elementos con el atributo 'rel=tooltip'
		$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		 // Convierte los botones de radio en un grupo de botones estilizado
		$('.radio.btn-group label').addClass('btn');

		// Evento de clic en las etiquetas de los botones de radio dentro de un grupo de botones
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			// Si el input no está seleccionado, actualiza la apariencia del grupo de botones
			if (!input.prop('checked')) {
				// Elimina clases activas previas en el grupo
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');

				// Agrega la clase activa y cambia el color según el valor del input
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				// Marca el input como seleccionado
				input.prop('checked', true);s
			}
		});

		// Aplica las clases correctas a los botones activos al cargar la página
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
	})
})(jQuery);