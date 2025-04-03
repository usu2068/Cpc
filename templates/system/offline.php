<?php
/**
 * Este archivo es una plantilla del sistema de Joomla para mostrar una página de "sitio fuera de línea". 
 * Contiene el código PHP y HTML necesarios para mostrar un formulario de inicio de sesión cuando el sitio está en modo offline.
 * 
 * Encabezado y definiciones iniciales
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 //Previene el acceso directo al archivo, garantizando que sólo pueda ejecutarse dentro de Joomla.
defined('_JEXEC') or die;

//Obtiene la instancia de la aplicación de Joomla.
$app = JFactory::getApplication();

// Add JavaScript Frameworks
//Carga los frameworks de Bootstrap necesarios.
JHtml::_('bootstrap.framework');

//Incluye el archivo de ayuda de usuarios y obtiene los métodos de autenticación en dos pasos disponibles.
require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

$twofactormethods = UsersHelper::getTwoFactorMethods();
?>

<!--ESTRUCTURA HTML DE LA PAGINA-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

	<!--incluye los archivos CSS y JS necesarios para joomla-->
	<jdoc:include type="head" />

	<!--Carga la hoja de estilos principal-->
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/offline.css" type="text/css" />

	<!--carga una hoja de estilos adicional si el idioma es de derecha a izquierda (RTL)-->
	<?php if ($this->direction == 'rtl') : ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/offline_rtl.css" type="text/css" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
</head>
<body>

<!--cuerpo de la pagina-->
<!--muestra de mensajes del sistema-->
<jdoc:include type="message" />
	<div id="frame" class="outline">

	<!--si hay una imagen de "offiline" configurada, se muestra.-->
		<?php if ($app->get('offline_image') && file_exists($app->get('offline_image'))) : ?>
			<img src="<?php echo $app->get('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->get('sitename')); ?>" />
		<?php endif; ?>
		
		<!--muestra el nombre del sitio web-->
		<h1>
			<?php echo htmlspecialchars($app->get('sitename')); ?>
		</h1>

		<!--Muestra un mensaje de "offline" si está configurado..-->
	<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
		<p>
			<?php echo $app->get('offline_message'); ?>
		</p>
	<?php elseif ($app->get('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
		<p>
			<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
		</p>
	<?php endif; ?>

	<!--Crea un formulario que envía la información al controlador de usuarios de Joomla-->
	<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
	<fieldset class="input">
		<p id="form-login-username">
			<!--Campo para el nombre de usuario-->
			<label for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
			<input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" size="18" />
		</p>
		<p id="form-login-password">
			<!--Campo para la contraseña-->
			<label for="passwd"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
			<input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" id="passwd" />
		</p>

		<!--Si hay más de un método de autenticación en dos pasos, se muestra un campo para la clave secreta-->
		<?php if (count($twofactormethods) > 1) : ?>
			<p id="form-login-secretkey">
				<label for="secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
				<input type="text" name="secretkey" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" id="secretkey" />
			</p>
		<?php endif; ?>

		<!--Si el plugin "Recordar sesión" está activado, se muestra la opción "Recordarme".-->
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<p id="form-login-remember">
				<label for="remember"><?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?></label>
				<input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>" id="remember" />
			</p>
		<?php endif; ?>
		<p id="submit-buton">
			<label>&nbsp;</label>
			<input type="submit" name="Submit" class="button login" value="<?php echo JText::_('JLOGIN'); ?>" />
		</p>

			<!--Campos ocultos para el envío seguro del formulario.-->
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	</form>
	</div>
</body>
</html>
