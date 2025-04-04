<?php
/**
 * HTML2PDF Librairy - HTML2PDF Locale -manejo de localizacion de idioma
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 * 
 * Esta clase se encarga de cargar, almacenar y obtener textos localizados
 *(traducciones) que utiliza la librería HTML2PDF.
 *
 * Carga archivos CSV desde un directorio específico, donde cada archivo contiene
 * claves y sus respectivas traducciones.
 *
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @version   4.03
 */

class HTML2PDF_locale
{
    /**
     * code of the current used locale
     * codigo del idioma actualmente cargado (por ejemplo: 'es', 'en', 'fr')
     * @var string
     */
    static protected $_code = null;

    /**
     * texts of the current used locale
     * arreglo asociativo que contiene las traducciones cargadas
     * Formato: 'clave' => 'texto traducido'.
     * @var array
     */
    static protected $_list = array();

    /**
     * directory where locale files are
     * Ruta del directorio donde se encuentran los archivos de idioma (.csv).
     * @var string
     */
    static protected $_directory = null;

    /**
     * load the locale
     * Cargar y aplicar un idioma (locale) específico.
     * 
     * Este método busca y carga un archivo CSV con las traducciones correspondientes
     * al código de idioma proporcionado.
     * 
     * @access public
     * @param  string $code
     */
    static public function load($code)
    {
        // Establecer el directorio de idioma si no está definido
        if (self::$_directory===null) {
            self::$_directory = dirname(dirname(__FILE__)).'/locale/';
        }

        // must be in lower case
        // Convertir el código a minúsculas
        $code = strtolower($code);

        // must be [a-z-0-9]
         // Validar que el código solo contenga letras y números
        if (!preg_match('/^([a-z0-9]+)$/isU', $code)) {
            throw new HTML2PDF_exception(0, 'invalid language code ['.self::$_code.']');
        }

        // save the code
        // Guardar el código
        self::$_code = $code;

        // get the name of the locale file
        // Construir la ruta del archivo de idioma
        $file = self::$_directory.self::$_code.'.csv';

        // the file must exist
        // Verificar que el archivo exista
        if (!is_file($file)) {
            throw new HTML2PDF_exception(0, 'language code ['.self::$_code.'] unknown. You can create the translation file ['.$file.'] and send it to the webmaster of html2pdf in order to integrate it into a future release');
        }

        // load the file
        // Leer y cargar las traducciones
        self::$_list = array();
        $handle = fopen($file, 'r');
        while (!feof($handle)) {
            $line = fgetcsv($handle);
            if (count($line)!=2) continue;
            self::$_list[trim($line[0])] = trim($line[1]);
        }
        fclose($handle);
    }

    /**
     * clean the locale
     * Limpiar el idioma cargado actual y reiniciar los datos internos.
     * @access public static
     */
    static public function clean()
    {
        self::$_code = null;
        self::$_list = array();
    }

    /**
     * get a text
     * Obtener una cadena de texto traducida a partir de una clave.
     * 
     * @access public static 
     * @param  string $key
     * @return string
     */
    static public function get($key, $default='######')
    {
        return (isset(self::$_list[$key]) ? self::$_list[$key] : $default);
    }
}