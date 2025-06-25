<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // Restringe el acceso directo al archivo
defined('_JEXEC') or die;

// Si no existe la variable de error, se genera un error 404
if (!isset($this->error))
{
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false; // Desactiva la depuración por defecto
}

// Get language and direction
// Obtiene el idioma y la dirección del documento
$doc             = JFactory::getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->error->getCode(); ?> - <?php echo htmlspecialchars($this->error->getMessage()); ?></title>

	<!-- Carga la hoja de estilos para errores -->
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/error.css" type="text/css" />

	<!-- Si el idioma es RTL (de derecha a izquierda), carga una hoja de estilos adicional -->
	<?php if ($this->direction == 'rtl') : ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/error_rtl.css" type="text/css" />
	<?php endif; ?>

	 <!-- Si el modo de depuración está activado, carga la hoja de estilos de depuración -->
	<?php $debug = JFactory::getConfig()->get('debug_lang'); ?>
	<?php if ((defined('JDEBUG') && JDEBUG) || $debug) : ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
	<?php endif; ?>
</head>
<body>
	<div class="error">
		<div id="outline">
		<div id="errorboxoutline">
			<div id="errorboxheader"><?php echo $this->error->getCode(); ?> - <?php echo htmlspecialchars($this->error->getMessage()); ?></div>
			<div id="errorboxbody">
			<p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
				<ol>
					<!-- mensaje de error indicando que el usuario podria estar usando un marcador desactualizado -->
					<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
					<!-- mensaje de error indicando que el usuario pudo haber escrito mal la direccion URL -->
					<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
					<!-- mensaje de error indicando que el usuario podria estar usando un marcador desactualizado -->
					<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
					<!-- mensaje de error indicando que el usuario no tiene permisos para ver la pagina-->
					<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
					<!-- mensaje de error indicando que el recurso solicitado no fue encontrado -->
					<li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
					<!-- mensaje generico indicando que ocurrio un error al procesar la solicitud-->
					<li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
				</ol>
			<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

				<ul>
					<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
				</ul>

			<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</p>
			<div id="techinfo">
			<p><?php echo htmlspecialchars($this->error->getMessage()); ?></p>
			<p>
				<?php if ($this->debug) :
					echo $this->renderBacktrace();
				endif; ?>
			</p>
			</div>
			</div>
		</div>
		</div>
	</div>
</body>
</html>
<!-- Notas:
		JText::_() es una función de Joomla que permite traducir cadenas de texto utilizando el sistema de idiomas del CMS. 
		Si Joomla está configurado en español y hay traducciones disponibles, los textos mostrados cambiarán al idioma correspondiente.
 		En resumen, este código genera una lista con posibles razones de error y las muestra en el idioma configurado en Joomla.-->