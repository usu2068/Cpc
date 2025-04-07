<?php 

//Permite la edicion de informacion de entidades o usuarios en la base de datos. Recibe parametros por POST para:
	//editar entidades( (tipo = 'E')
	//editar usuarios tipo = 'U' ), en cuyo caso tambien se encripta la contraseña usando la libreria passwordhash

	// Incluye la clase para manejo de consultas SQL personalizadas
include('../../class/consultas.php');

// Si se usa en el mismo host de origen, se deja tal cual. Si no, hay que descargar y enrutar localmente.
require_once "/home/aplicati/public_html/utlr/templates/class/PasswordHash.php";// Aqui se carga la libreria si el codigo que ustedes usan esta en nuestro host solo dejela como esta de lo contrario descargue la libreria y enrute aca

// Recupera el tipo de acción (Editar entidad 'E' o Editar usuario 'U')
$tipo = $_POST['tipo'];

/**
 * Función para generar un hash seguro de la contraseña usando la clase PasswordHash
 * @param string $pass Contraseña en texto plano
 * @return string Contraseña encriptada
 */
function josHashPassword($pass)	{   // $pass es la contraseña que elige el usuario sin encriptar
	
	$phpass = new PasswordHash(10, false);
	$crypt = $phpass->HashPassword($pass, PASSWORD_DEFAULT); 
	$hash = $crypt; 
	return $hash;
}

// Si el tipo es 'E', se edita una entidad
if($tipo == 'E'){
	
	// Se reciben los datos de la entidad
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$nit = $_POST['nit'];
	$image = $_POST['image'];

	// Se instancia la clase mysql y se prepara la actualización
	$consult = new mysql();
	$table = array('jo33_FIC_categories'); // Tabla a actualizar
	$val = array($nombre, $nit, $image); // Nuevos valores
	$valC = array('title','alias','description'); // Columnas a actualizar
	$valU = array('id = '.$id); // Condición WHERE

	// Ejecuta la actualización
	$sql_u_ent = $consult -> sql('U',$table,$val,$valC, $valU);
	
	// Devuelve mensaje de éxito
	echo '	<div class="alert alert-success span 4" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
				<p>Entidad Editada Exitosamente.</p>
			</div>'
	;

	// Si el tipo es 'U', se edita un usuario
}elseif($tipo == 'U'){
	
	// Se reciben los datos del usuario
	$id = $_POST['id_admin'];
	$ent = $_POST['entidad'];
	$nom = $_POST['nombre'];
	$ape = $_POST['apellido'];
	$ced = $_POST['cedula'];
	$ema = $_POST['email'];
	$usu = $_POST['usuario'];
	$pass =  josHashPassword($_POST['pass']); // Se encripta la contraseña
	
	// Instancia de clase mysql y armado de la consulta
	$consult = new mysql();
	$table = array('jo33_FIC_users', 'jo33_FIC_content');
	$val = array($nom."  ".$ape,  // nombre completo
	$ema,  // email
	$usu, // nombre de usuario
	$pass, // contraseña encriptada
	$nom."  ".$ape,  // título (en tabla de contenido)
	"<p>".$ced."</p>", // texto introductorio
	$ent); // ID de la entidad (categoría)
	$valC = array('jo33_FIC_users.name', 'jo33_FIC_users.email', 'jo33_FIC_users.username', 'jo33_FIC_users.password', 'jo33_FIC_content.title', 'jo33_FIC_content.introtext', 'jo33_FIC_content.catid');
	$valU = array('jo33_FIC_users.id = '.$id, 'jo33_FIC_content.alias = jo33_FIC_users.id');
	
	// Ejecuta la actualización
	$sql_u_usu = $consult -> sql('U',$table,$val,$valC, $valU);
	
	// Devuelve mensaje de éxito
	echo '	<div class="alert alert-success span 4" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
				<p>Usuario Editado Exitosamente.</p>
			</div>';
}

?>