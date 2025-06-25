<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //se evita el acceso al archivo en un entorno fuera de Joomla
defined('_JEXEC') or die;

$app             = JFactory::getApplication(); //obtiene la aplicacion de joomla
$doc             = JFactory::getDocument(); //obtiene el objeto del documento HTML
$this->language  = $doc->language; //define el idioma del documento basado en la configuracion de joomla
$this->direction = $doc->direction; // defiine la direccion del texto (ltr o rtl para idiomas de derecha a izquierda)

// Add JavaScript Frameworks
// añade y/o usa estilos de javascript
JHtml::_('bootstrap.framework');

// Add Stylesheets
//añade una hoja de estilos
$doc->addStyleSheet('templates/' . $this->template . '/css/template.css');

// Load optional rtl Bootstrap css and Bootstrap bugfixes
//carga archivos adicionales de bootstrap si la direccion del texto es rtl (para idiomas como arabe o hebreo)
JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);

?>

<!--estructura del documento HTML-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />
<!--[if lt IE 9]>
	<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
<![endif]-->
</head>

<!---Estructura del documento-->
<body class="contentpane modal">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
