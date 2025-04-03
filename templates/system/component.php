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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<!-- Incluye el encabezado del documento, como metaetiquetas y scripts -->
	<jdoc:include type="head" />

	<!-- Carga la hoja de estilos general del sistema -->
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />

	<!-- Carga la hoja de estilos específica de la plantilla actual -->
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

	<!-- Si el sitio está en un idioma con escritura de derecha a izquierda (RTL), carga la hoja de estilos correspondiente -->
<?php if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template_rtl.css" type="text/css" />
<?php endif; ?>
</head>
<body class="contentpane">
	<!-- Muestra mensajes del sistema (como errores, alertas o notificaciones) -->
	<jdoc:include type="message" />

	<!-- Renderiza el componente principal de la página (contenido dinámico del sitio) -->
	<jdoc:include type="component" />
</body>
</html>
