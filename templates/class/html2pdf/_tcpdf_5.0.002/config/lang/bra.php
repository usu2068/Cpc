<?php
//============================================================+
// File name   : eng.php
// Begin       : 2004-03-03
// Last Update : 2010-02-17
// 
// Description : Language module for TCPDF
//               (contains translated texts)
// 
// Author: Nicola Asuni
// 
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * TCPDF language file (contains translated texts).
 * Archivo de idioma para TCPDF (contiene textos traducidos).
 * 
 * Este archivo define traducciones de textos usados por la biblioteca TCPDF,
 * permitiendo la personalización del idioma de los documentos PDF generados.
 * 
 * @package com.tecnick.tcpdf
 * @abstract TCPDF language file.
 * @author Nicola Asuni
 * @copyright 2004-2009 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link http://tcpdf.sourceforge.net
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @since 2004-03-03
 */
 
// ENGLISH

// Variable global que contiene los textos traducidos
global $l;
$l = Array();

// PAGE META DESCRIPTORS --------------------------------------
// METADATOS DE LA PÁGINA --------------------------------------

/**
 * Conjunto de caracteres utilizado (codificación)
 * UTF-8 es compatible con la mayoría de los idiomas.
 */
$l['a_meta_charset'] = 'UTF-8';

/**
 * Dirección del texto: 'ltr' (left-to-right / izquierda a derecha).
 * Útil para idiomas como inglés, español, francés, etc.
 * Para idiomas como árabe o hebreo se usaría 'rtl'.
 */
$l['a_meta_dir'] = 'ltr';

/**
 * Código de idioma utilizado en el documento.
 * Aunque el archivo es "eng" (inglés), aquí está configurado como 'pt' (portugués),
 * lo cual podría ser un error o una configuración personalizada.
 */
$l['a_meta_language'] = 'pt';

// TRANSLATIONS --------------------------------------
// TRADUCCIONES DE TEXTO --------------------------------------

/**
 * Traducción de la palabra "página".
 * Esta palabra se usará en los números de página, encabezados o pies de página.
 */
$l['w_page'] = 'página';

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
