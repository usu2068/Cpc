<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // Evita el acceso directo al archivo para mayor seguridad.
defined('_JEXEC') or die;

/*
 * none (output raw module content)
 */
function modChrome_none($module, &$params, &$attribs)
{
	echo $module->content;
}

/*
 * html5 (chosen html5 tag and font headder tags)
 */
function modChrome_html5($module, &$params, &$attribs)
{
	$moduleTag      = $params->get('module_tag', 'div'); //Define la etique HTML a usar para el modulo
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h3')); //Define la etiqueta HTML para el titulo del módulo
	$bootstrapSize  = (int) $params->get('bootstrap_size', 0); //define el tamaño de bootstrap
	$moduleClass    = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';

	// Temporarily store header class in variable
	//obtiene la clase CSS para el titulo del modulo
	$headerClass	= $params->get('header_class');
	$headerClass	= !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass) . '"' : '';

	if (!empty ($module->content)) : ?>
		<<?php echo $moduleTag; ?> class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')) . $moduleClass; ?>">

		<?php if ((bool) $module->showtitle) :?>
			<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
		<?php endif; ?>

			<?php echo $module->content; ?>

		</<?php echo $moduleTag; ?>>

	<?php endif;
}

/*
 * Module chrome that wraps the module in a table
 * Renderiza el modulo dentro de una tabla HTML
 */
function modChrome_table($module, &$params, &$attribs)
{ ?>
	<table cellpadding="0" cellspacing="0" class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
	<?php if ((bool) $module->showtitle) : ?>
		<tr>
			<th>
				<?php echo $module->title; ?>
			</th>
		</tr>
	<?php endif; ?>
		<tr>
			<td>
				<?php echo $module->content; ?>
			</td>
		</tr>
		</table>
	<?php
}

/*
 * Module chrome that wraps the tabled module output in a <td> tag of another table
 * Renderiza el modulo dentro de una tabla con una estructura adicional
 */
function modChrome_horz($module, &$params, &$attribs)
{ ?>
	<table cellspacing="1" cellpadding="0" width="100%">
		<tr>
			<td>
				<?php modChrome_table($module, $params, $attribs); ?>
			</td>
		</tr>
	</table>
	<?php
}

/*
 * xhtml (divs and font headder tags)
 */
function modChrome_xhtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ((bool) $module->showtitle) : ?>
			<h3><?php echo $module->title; ?></h3>
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}

/*
 * Module chrome that allows for rounded corners by wrapping in nested div tags
 * Renderiza el modulo dentro de varios <div>, simulando esquinas redondeadas con css
 */
function modChrome_rounded($module, &$params, &$attribs)
{ ?>
		<div class="module<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
			<div>
				<div>
					<div>
						<?php if ((bool) $module->showtitle) : ?>
							<h3><?php echo $module->title; ?></h3>
						<?php endif; ?>
					<?php echo $module->content; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
}

/*
 * Module chrome that add preview information to the module
 * Renderiiza el modulo mostrando informacion de depuracion, como la posicion y el estilo del modulo
 */
function modChrome_outline($module, &$params, &$attribs)
{
	static $css = false;

	//agrega estilos css para la vista de depuracion
	if (!$css)
	{
		$css = true;
		$doc = JFactory::getDocument();

		$doc->addStyleDeclaration(".mod-preview-info { padding: 2px 4px 2px 4px; border: 1px solid black; position: absolute; background-color: white; color: red;}");
		$doc->addStyleDeclaration(".mod-preview-wrapper { background-color:#eee; border: 1px dotted black; color:#700;}");
	}
	?>
	<div class="mod-preview">
		<div class="mod-preview-info"><?php echo 'Position: ' . $module->position . ' [ Style: ' . $module->style . ']'; ?></div>
		<div class="mod-preview-wrapper">
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php
}

/**
 * Este codigo define diferentes formas de renderizar los modulos en joomla, lo que permite personalizar su apariencia y estructura.
 */
