<?php
class JConfig {
	// Configuración de la página fuera de línea
	public $offline = '0'; // Establece si el sitio está fuera de línea (0 = no, 1 = sí)
	public $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.'; // Mensaje que se muestra cuando el sitio está fuera de línea
	public $display_offline_message = '1'; // Si se debe mostrar el mensaje de mantenimiento (0 = no, 1 = sí)
	public $offline_image = ''; // Ruta de la imagen a mostrar cuando el sitio esté fuera de línea

	// Configuración del sitio
	public $sitename = 'UTLR'; // Nombre del sitio
	public $editor = 'tinymce'; // Editor de texto predeterminado (en este caso, TinyMCE)
	public $captcha = '0'; // Si se usa CAPTCHA en formularios (0 = no, 1 = sí)
	public $list_limit = '20'; // Límite de elementos a mostrar en las listas
	public $access = '1'; // Nivel de acceso predeterminado (1 = acceso público)

	// Configuración de depuración
	public $debug = '0'; // Si la depuración está activada (0 = no, 1 = sí)
	public $debug_lang = '0'; // Si se deben mostrar los errores de idioma en depuración (0 = no, 1 = sí)

	// Configuración de la base de datos
	public $dbtype = 'mysqli'; // Tipo de base de datos (en este caso, MySQLi)
	public $host = 'localhost'; // Host de la base de datos
	public $user = 'aplicati_FIC'; // Usuario de la base de datos
	public $password = 'FIC2014'; // Contraseña de la base de datos
	public $db = 'aplicati_FIC'; // Nombre de la base de datos
	public $dbprefix = 'jo33_FIC_'; // Prefijo de las tablas de la base de datos

	// Configuración del sitio en vivo
	public $live_site = ''; // URL del sitio en vivo (si es necesario)
	public $secret = 'l3eva9zg2xy87yme'; // Clave secreta para las sesiones y seguridad
	public $gzip = '0'; // Si se debe habilitar la compresión GZIP (0 = no, 1 = sí)
	public $error_reporting = 'none'; // Nivel de informes de errores (por ejemplo, 'none', 'simple', 'development')

	// Configuración de ayuda
	public $helpurl = 'http://help.joomla.org/proxy/index.php?option=com_help&keyref=Help{major}{minor}:{keyref}'; // URL de ayuda

	// Configuración FTP
	public $ftp_host = '127.0.0.1'; // Dirección del servidor FTP
	public $ftp_port = '21'; // Puerto FTP
	public $ftp_user = ''; // Usuario FTP
	public $ftp_pass = ''; // Contraseña FTP
	public $ftp_root = ''; // Directorio raíz FTP
	public $ftp_enable = '0'; // Habilitar FTP (0 = no, 1 = sí)

	// Configuración de zona horaria
	public $offset = 'UTC'; // Zona horaria del servidor

	// Configuración de correo electrónico
	public $mailonline = '1'; // Si el sistema de correo está habilitado (0 = no, 1 = sí)
	public $mailer = 'mail'; // Método de envío de correo (por ejemplo, 'mail', 'smtp')
	public $mailfrom = 'miguel.perilla@ustarizabogados.com'; // Dirección de correo electrónico del remitente
	public $fromname = 'UTLR'; // Nombre del remitente
	public $sendmail = '/usr/sbin/sendmail'; // Ruta al ejecutable de sendmail
	public $smtpauth = '0'; // Si se requiere autenticación SMTP (0 = no, 1 = sí)
	public $smtpuser = ''; // Usuario SMTP (si se requiere autenticación)
	public $smtppass = ''; // Contraseña SMTP (si se requiere autenticación)
	public $smtphost = 'localhost'; // Servidor SMTP
	public $smtpsecure = 'none'; // Tipo de seguridad SMTP (por ejemplo, 'none', 'ssl', 'tls')
	public $smtpport = '25'; // Puerto SMTP

	// Configuración de caché
	public $caching = '0'; // Si la caché está habilitada (0 = no, 1 = sí)
	public $cache_handler = 'file'; // Método de manejo de caché (por ejemplo, 'file', 'memcache')
	public $cachetime = '15'; // Tiempo de vida de los archivos de caché en minutos

	// Meta información para SEO
	public $MetaDesc = 'Ustariz Treasury Legal Risk'; // Descripción del sitio para SEO
	public $MetaKeys = ''; // Palabras clave del sitio para SEO
	public $MetaTitle = '1'; // Si se debe mostrar el título en la metaetiqueta (0 = no, 1 = sí)
	public $MetaAuthor = '1'; // Si se debe mostrar el autor en la metaetiqueta (0 = no, 1 = sí)
	public $MetaVersion = '0'; // Si se debe mostrar la versión en la metaetiqueta (0 = no, 1 = sí)
	public $robots = ''; // Directivas para los robots de búsqueda

	// Configuración SEF (Friendly URLs)
	public $sef = '1'; // Si las URLs amigables (SEF) están habilitadas (0 = no, 1 = sí)
	public $sef_rewrite = '0'; // Si se debe reescribir las URLs (0 = no, 1 = sí)
	public $sef_suffix = '0'; // Si se debe añadir un sufijo a las URLs (0 = no, 1 = sí)
	public $unicodeslugs = '0'; // Si se deben usar slugs Unicode (0 = no, 1 = sí)

	// Configuración del feed
	public $feed_limit = '10'; // Número de elementos a mostrar en el feed

	// Rutas de logs y archivos temporales
	public $log_path = '/home/aplicati/public_html/utlr/logs'; // Ruta para los archivos de registro
	public $tmp_path = '/home/aplicati/public_html/utlr/tmp'; // Ruta para los archivos temporales

	// Configuración de la sesión
	public $lifetime = '15'; // Duración de la sesión en minutos
	public $session_handler = 'database'; // Manejador de sesiones (por ejemplo, 'database', 'file')

	// Ruta absoluta al directorio raíz del sitio Joomla
	public $mosConfig_absolute_path = '/home/aplicati/public_html/utlr/';
}
