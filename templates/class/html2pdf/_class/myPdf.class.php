<?php
/**
 * 
 *  HTML2PDF Librairy - myPdf class
 *
 * Propósito del Archivo:**
 * Este archivo define la clase `HTML2PDF_myPdf`, la cual **extiende la funcionalidad de la clase `TCPDF`** (una librería PHP para generar archivos PDF). 
 * El objetivo principal de esta clase es **proporcionar funcionalidades específicas y personalizaciones necesarias para que la librería HTML2PDF pueda convertir correctamente 
 * documentos HTML a PDF.**
 *
 * En esencia, `HTML2PDF_myPdf` actúa como un **puente o adaptador** entre la lógica de conversión de HTML a PDF implementada en HTML2PDF y las capacidades de 
 * bajo nivel de generación de PDF ofrecidas por TCPDF. Contiene métodos y propiedades que modifican el comportamiento predeterminado de TCPDF o añaden nuevas 
 * funcionalidades requeridas por el proceso de conversión de HTML.
 *
 * HTML2PDF Librairy - myPdf class
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @version   4.03
 */

require_once(dirname(__FILE__).'/tcpdfConfig.php');
require_once(dirname(__FILE__).'/../_tcpdf_'.HTML2PDF_USED_TCPDF_VERSION.'/tcpdf.php');

/**
 * Clase extendida de TCPDF para funcionalidades específicas de HTML2PDF.
 *
 * **Descripción Detallada:**
 * La clase `HTML2PDF_myPdf` hereda todas las funcionalidades de la clase `TCPDF`. Sobre esta herencia, **redefine o añade métodos** para adaptar la generación de PDF a las 
 *  necesidades particulares de la conversión desde HTML. Esto incluye:
 *
 * - **Gestión del pie de página automático:** Permite configurar y generar un pie de página con información como número de página, fecha, hora y advertencias sobre formularios, 
 *  utilizando textos traducidos por HTML2PDF.
 * - **Optimización de la carga de fuentes:** Implementa un mecanismo para evitar la duplicación innecesaria de información de fuentes al clonar objetos PDF, 
 *  mejorando el rendimiento y el uso de memoria.
 * - **Acceso controlado a atributos privados de TCPDF:** Proporciona métodos públicos para que la librería HTML2PDF pueda interactuar con ciertos atributos internos de TCPDF sin 
 *  violar su encapsulamiento.
 * - **Manipulación de transformaciones gráficas:** Ofrece métodos para iniciar, detener y aplicar transformaciones como traslación y rotación, necesarias para renderizar 
 *  correctamente ciertos elementos HTML o SVG.
 * - **Redefinición de métodos de posicionamiento:** Sobrescribe los métodos `SetX`, `SetY` y `SetXY` de TCPDF para que el manejo de las coordenadas sea controlado por la 
 *  lógica de HTML2PDF, en lugar del comportamiento automático de TCPDF.
 * - **Implementación de funcionalidades gráficas específicas:** Añade métodos para dibujar formas geométricas complejas como rectángulos con esquinas redondeadas, curvas y arcos, 
 *  que pueden ser utilizados para renderizar bordes, elementos SVG, etc.
 * - **Adaptación para el renderizado SVG:** Incluye métodos para interpretar y dibujar elementos gráficos definidos en formato SVG, como rectángulos, líneas, elipses y polígonos 
 *  avanzados.
 */

class HTML2PDF_myPdf extends TCPDF
{
    /**
     * Parámetros para la configuración del pie de página automático.
     *
     * **Descripción:**
     * Este array asociativo almacena las opciones para determinar qué información se incluirá en el pie de página generado automáticamente. Las claves del array son:
     * - `'page'`: Un valor booleano que indica si se debe mostrar el número de página.
     * - `'date'`: Un valor booleano que indica si se debe mostrar la fecha actual.
     * - `'hour'`: Un valor booleano que indica si se debe mostrar la hora actual.
     * - `'form'`: Un valor booleano que indica si se debe mostrar una advertencia relacionada con formularios.
     *
     * **Uso:**
     * Estos parámetros son configurados mediante el método `SetMyFooter()` y utilizados por el método `Footer()` para construir el contenido del pie de página.
     *
     * @var array
     */

    protected $_footerParam = array();

    /**
     * Almacena las transformaciones gráficas aplicadas.
     *
     * **Descripción:**
     * Este array se utiliza para mantener un registro de las transformaciones gráficas (como traslaciones, rotaciones, escalados) que se han aplicado al documento PDF en un momento dado. Esto es útil para gestionar el contexto gráfico durante la conversión de HTML, especialmente para elementos que requieren transformaciones complejas.
     *
     * **Uso:**
     * Las transformaciones se añaden a este array al iniciar una transformación (`startTransform()`) y se eliminan al finalizarla (`stopTransform()`). Los métodos como `setTranslate()` y `setRotation()` modifican el estado de la transformación actual.
     *
     * @var array
     */

    protected $_transf      = array();

    /**
     * Almacena el identificador del último grupo de páginas procesado.
     *
     * **Descripción:**
     * Esta variable se utiliza para rastrear el último grupo de páginas que se ha procesado. En el contexto de HTML2PDF, esto puede ser relevante para la numeración de páginas dentro de secciones o grupos específicos de un documento.
     *
     * **Uso:**
     * Su valor se actualiza durante el proceso de generación del PDF cuando se manejan diferentes grupos de páginas.
     *
     * @var mixed
     */

    protected $_myLastPageGroup = null;

    /**
     * Almacena el número de páginas del último grupo procesado.
     *
     * **Descripción:**
     * Esta variable guarda la cantidad total de páginas que pertenecían al último grupo de páginas procesado. Se utiliza en conjunto con `$_myLastPageGroup` para la gestión de la numeración de páginas dentro de grupos.
     *
     * **Uso:**
     * Su valor se actualiza al finalizar el procesamiento de un grupo de páginas.
     *
     * @var int
     */

    protected $_myLastPageGroupNb = 0;

    /**
     * Valor constante utilizado para aproximar un arco de círculo mediante una curva Bézier cúbica.
     *
     * **Descripción:**
     * El valor `0.5522847498` es una constante matemática derivada de la fórmula `(4/3 * (sqrt(2) - 1))`. Se utiliza como factor para calcular los puntos de control necesarios para dibujar un segmento de arco de círculo utilizando una curva Bézier, que es la forma en que PDF representa las curvas.
     *
     * **Uso:**
     * Esta constante es utilizada en los métodos `clippingPathStart()` y `drawCurve()` para generar las curvas que forman las esquinas redondeadas o los arcos.
     *
     * @var float
     */

    // used to make a radius with bezier : (4/3 * (sqrt(2) - 1))
    const MY_ARC = 0.5522847498;

    /**
     * Número de segmentos en los que se divide un arco para ser construido con curvas Bézier.
     *
     * **Descripción:**
     * El valor `8` indica que cada arco de círculo que se desea dibujar se aproximará mediante 8 segmentos de curvas Bézier cúbicas. Un mayor número de segmentos resultará en una aproximación más suave del arco, pero también en una mayor complejidad del archivo PDF.
     *
     * **Uso:**
     * Esta constante se utiliza en el método `_Arc()` para determinar cuántos segmentos Bézier se utilizarán para dibujar un arco.
     *
     * @var int
     */

    // nb of segment to build a arc with bezier curv
    const ARC_NB_SEGMENT = 8;
/**
     * Constructor de la clase.
     *
     * **Descripción Detallada:**
     * El constructor de la clase `HTML2PDF_myPdf` realiza las siguientes acciones:
     * 1. **Llama al constructor padre (`parent::__construct(...)`)**: Inicializa la instancia de la clase `TCPDF` con los parámetros proporcionados (orientación, unidad, formato, etc.). Esto configura la base para la generación del PDF.
     * 2. **Configura metadatos del PDF**: Establece el creador del documento PDF utilizando la constante `PDF_CREATOR` definida en la configuración de TCPDF.
     * 3. **Desactiva el salto de página automático de TCPDF**: Llama a `SetAutoPageBreak(false, 0)` para deshabilitar el mecanismo automático de TCPDF para insertar nuevos saltos de página. El manejo de los saltos de página será controlado por la lógica de HTML2PDF.
     * 4. **Establece el estilo de los extremos de las líneas**: Define el estilo de la "tapa" de las líneas dibujadas a '2 J' (Round Cap), lo que afecta la apariencia de los extremos de las líneas y los bordes.
     * 5. **Desactiva la impresión de la cabecera**: Llama a `setPrintHeader(false)` para evitar que la cabecera predeterminada de TCPDF se imprima en las páginas.
     * 6. **Establece la calidad de las imágenes JPEG**: Define la calidad de compresión para las imágenes JPEG que se incluyan en el PDF a un valor de 90.
     * 7. **Prepara el pie de página automático**: Llama al método `SetMyFooter()` para inicializar la configuración del pie de página automático con los valores predeterminados (ningún elemento visible inicialmente).
     * 8. **Establece el margen de celda a cero**: Define el margen interno de las celdas (`cMargin`) a 0.
     *
     * @param string  $orientation Orientación de la página (P para vertical, L para horizontal), igual que en TCPDF.
     * @param string  $unit        Unidad de medida del usuario, igual que en TCPDF (pt, mm, cm, in).
     * @param mixed   $format      Formato de la página, igual que en TCPDF (A4, Letter, etc.).
     * @param boolean $unicode     TRUE si el texto de entrada es Unicode (predeterminado = true).
     * @param string  $encoding    Codificación de caracteres; el valor predeterminado es UTF-8.
     * @param boolean $diskcache   Si TRUE, reduce el uso de memoria RAM almacenando datos temporales en el sistema de archivos (más lento).
     * @access public
     */

    public function __construct(
        $orientation='P',
        $unit='mm',
        $format='A4',
        $unicode=true,
        $encoding='UTF-8',
        $diskcache=false)
    {
        // call the parent constructor
        // Llama al constructor padre
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);

        // init the specific parameters used by HTML2PDF
        // Inicializa los parámetros específicos utilizados por HTML2PDF
        $this->SetCreator(PDF_CREATOR);
        $this->SetAutoPageBreak(false, 0);
        $this->linestyleCap = '2 J';
        $this->setPrintHeader(false);
        $this->jpeg_quality = 90;

        // prepare the automatic footer
        // Prepara el pie de página automático
        $this->SetMyFooter();

        $this->cMargin = 0;
    }

    /**
     * Establece los parámetros para el pie de página automático.
     *
     * **Descripción Detallada:**
     * Este método permite configurar qué elementos se mostrarán en el pie de página automático del documento PDF. Recibe cuatro parámetros booleanos que controlan la visibilidad de diferentes elementos:
     * - `$page`: Si es `true`, se mostrará el número de página actual y el número total de páginas.
     * - `$date`: Si es `true`, se mostrará la fecha actual.
     * - `$hour`: Si es `true`, se mostrará la hora actual.
     * - `$form`: Si es `true`, se mostrará un texto de advertencia relacionado con formularios (útil si el PDF contiene campos de formulario que pueden no ser totalmente interactivos en todos los lectores).
     *
     * Internamente, este método simplemente guarda los valores de estos parámetros en el array protegido `$_footerParam`, el cual será utilizado posteriormente por el método `Footer()` para generar el contenido del pie de página.
     *
     * @param boolean $page Muestra el número de página (predeterminado: false).
     * @param boolean $date Muestra la fecha (predeterminado: false).
     * @param boolean $hour Muestra la hora (predeterminado: false).
     * @param boolean $form Muestra una advertencia sobre formularios (predeterminado: false).
     * @access public
     */

    public function SetMyFooter($page = false, $date = false, $hour = false, $form = false)
    {
        $page    = ($page ? true : false);
        $date    = ($date ? true : false);
        $hour    = ($hour ? true : false);
        $form    = ($form ? true : false);

        $this->_footerParam = array('page' => $page, 'date' => $date, 'hour' => $hour, 'form' => $form);
    }

    /**
     * Este método es llamado automáticamente por TCPDF al final de cada página.
     *
     * **Descripción Detallada:**
     * El método `Footer()` se encarga de generar y dibujar el pie de página en cada página del documento PDF. Su funcionamiento es el siguiente:
     * 1. **Inicializa el texto del pie de página**: Comienza con una cadena vacía (`$txt`).
     * 2. **Añade la advertencia de formulario (si está habilitada)**: Si el parámetro `'form'` en `$_footerParam` es `true`, añade el texto traducido correspondiente a la advertencia de formulario (obtenido de `HTML2PDF_locale`).
     * 3. **Añade la fecha y/o hora (si están habilitadas)**:
     * - Si tanto `'date'` como `'hour'` son `true`, añade la fecha y la hora con un separador, utilizando el texto traducido correspondiente.
     * - Si solo `'date'` es `true`, añade solo la fecha con el separador si ya hay texto en `$txt`.
     * - Si solo `'hour'` es `true`, añade solo la hora con el separador si ya hay texto en `$txt`.
     * 4. **Añade el número de página (si está habilitado)**: Si el parámetro `'page'` en `$_footerParam` es `true`, añade el texto traducido para el número de página, incluyendo el número de página actual y el número total de páginas (que se reemplazan mediante marcadores).
     * 5. **Reemplaza los marcadores de fecha y página**: Si la cadena `$txt` no está vacía, busca y reemplaza marcadores como `[[date_d]]`, `[[date_m]]`, `[[page_cu]]`, `[[page_nb]]`, etc., con los valores actuales de fecha y número de página. Los números de página actual y total se obtienen mediante los métodos `getMyNumPage()` y `getMyAliasNbPages()` (que podrían ser extensiones o adaptaciones de métodos de TCPDF).
     * 6. **Dibuja el pie de página**:
     * - Establece la posición vertical en la parte inferior de la página (`parent::SetY(-11)`).
     * - Establece la fuente a Helvetica, en cursiva, con un tamaño de 8 puntos.
     * - Dibuja una celda que abarca todo el ancho de la página (`0`), con una altura de 10 unidades, conteniendo el texto `$txt`, sin borde (`0`), sin mover la posición después (`0`), y alineada a la derecha (`'R'`).
     *
     * @access public
     */

    public function Footer()
    {
        // prepare the text from the tranlated text
        // Prepara el texto a partir del texto traducido
        $txt = '';
        if ($this->_footerParam['form']) {
            $txt = (HTML2PDF_locale::get('pdf05'));
        }
        if ($this->_footerParam['date'] && $this->_footerParam['hour']) {
            $txt.= ($txt ? ' - ' : '').(HTML2PDF_locale::get('pdf03'));
        }
        if ($this->_footerParam['date'] && !$this->_footerParam['hour']) {
            $txt.= ($txt ? ' - ' : '').(HTML2PDF_locale::get('pdf01'));
        }
        if (!$this->_footerParam['date'] && $this->_footerParam['hour']) {
            $txt.= ($txt ? ' - ' : '').(HTML2PDF_locale::get('pdf02'));
        }
        if ($this->_footerParam['page']) {
            $txt.= ($txt ? ' - ' : '').(HTML2PDF_locale::get('pdf04'));
        }

        if (strlen($txt)>0) {
            // replace some values
            // Reemplaza algunos valores
            $toReplace = array(
                '[[date_d]]'  => date('d'),
                '[[date_m]]'  => date('m'),
                '[[date_y]]'  => date('Y'),
                '[[date_h]]'  => date('H'),
                '[[date_i]]'  => date('i'),
                '[[date_s]]'  => date('s'),
                '[[page_cu]]' => $this->getMyNumPage(),
                '[[page_nb]]' => $this->getMyAliasNbPages(),
            );
            $txt = str_replace(array_keys($toReplace), array_values($toReplace), $txt);

            // draw the footer
            // Dibuja el pie de página
            parent::SetY(-11);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, $txt, 0, 0, 'R');
        }
    }

     /**
     * Después de clonar un objeto, no queremos clonar toda la información
     * de la fuente, ya que lleva mucho tiempo y mucha memoria => usamos referencias.
     *
     * **Descripción Detallada:**
     * Este método está diseñado para optimizar el proceso de clonación de objetos `HTML2PDF_myPdf`. Cuando se clona un objeto PDF, copiar toda la información relacionada con las fuentes (definiciones, archivos, etc.) puede ser costoso en términos de tiempo y memoria.
     *
     * `cloneFontFrom()` toma como argumento una referencia a otro objeto `HTML2PDF_myPdf` ya existente. En lugar de copiar los arrays que contienen la información de las fuentes, el método **asigna las propiedades de fuente del objeto actual para que referencien los mismos arrays del objeto pasado como argumento**.
     *
     * Esto significa que ambos objetos compartirán la misma información de fuentes en memoria. Cualquier modificación a la información de fuentes a través de uno de los objetos afectará al otro. Esta técnica es segura en este contexto porque la información de las fuentes generalmente no se modifica después de la carga inicial.
     *
     * **Parámetros:**
     * @param &HTML2PDF_myPdf $pdf Referencia al objeto `HTML2PDF_myPdf` del cual se tomarán las referencias a la información de las fuentes.
     * @access public
     */

    public function cloneFontFrom(&$pdf)
    {
        $this->fonts            = &$pdf->getFonts();
        $this->FontFiles        = &$pdf->getFontFiles();
        $this->diffs            = &$pdf->getDiffs();
        $this->fontlist         = &$pdf->getFontList();
        $this->numfonts         = &$pdf->getNumFonts();
        $this->fontkeys         = &$pdf->getFontKeys();
        $this->font_obj_ids     = &$pdf->getFontObjIds();
        $this->annotation_fonts = &$pdf->getAnnotFonts();
    }

    /**
     * Múltiples accesores públicos para algunos atributos privados relacionados con las fuentes.
     * Utilizado exclusivamente por el método `cloneFontFrom`.
     *
     * **Descripción Detallada:**
     * Estos métodos (`getFonts()`, `getFontFiles()`, etc.) proporcionan acceso de **solo lectura por referencia** a los arrays privados de la clase que almacenan la información de las fuentes.
     *
     * Su único propósito es ser utilizados por el método `cloneFontFrom()` para permitir que un objeto clonado establezca referencias a la información de fuentes de otro objeto, sin necesidad de copiar los datos. Esto mejora la eficiencia en el uso de memoria y el tiempo de clonación.
     *
     * **Retorno:**
     * Cada método devuelve una **referencia (`&`)** al array privado correspondiente que contiene la información de las fuentes.
     *
     * @return &array Referencia al array del atributo solicitado.
     * @access public
     */

    public function &getFonts()
    {
        return $this->fonts;
    }
    public function &getFontFiles()
    {
        return $this->FontFiles;
    }
    public function &getDiffs()
    {
        return $this->diffs;
    }
    public function &getFontList()
    {
        return $this->fontlist;
    }
    public function &getNumFonts()
    {
        return $this->numfonts;
    }
    public function &getFontKeys()
    {
        return $this->fontkeys;
    }
    public function &getFontObjIds()
    {
        return $this->font_obj_ids;
    }
    public function &getAnnotFonts()
    {
        return $this->annotation_fonts;
    }

    /**
     * Verifica si una fuente ya ha sido cargada en el documento PDF.
     *
     * **Descripción Detallada:**
     * Este método toma una clave de fuente (`$fontKey`) como argumento y verifica si una fuente con esa clave ya está registrada en la instancia actual del documento PDF.
     *
     * La verificación se realiza buscando la clave en dos arrays internos:
     * - `$this->fonts`: Contiene información sobre las fuentes TrueType, Type1, etc., que han sido añadidas al documento.
     * - `$this->CoreFonts`: Contiene un listado de las fuentes base o "core fonts" que están disponibles en la mayoría de los lectores de PDF y que no necesitan ser incrustadas en el documento.
     *
     * **Parámetros:**
     * @param string $fontKey Clave única que identifica la fuente.
     * @return boolean `true` si la fuente con la clave especificada ya está cargada (ya sea como una fuente añadida o como una fuente base), `false` en caso contrario.
     * @access public
     */ 

    public function isLoadedFont($fontKey)
    {
        if (isset($this->fonts[$fontKey])) {
            return true;
        }

        if (isset($this->CoreFonts[$fontKey])) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene el valor actual del espaciado entre palabras.
     *
     * **Descripción:**
     * Este método devuelve el valor numérico que representa el espaciado adicional que se añade entre las palabras al renderizar texto. Esta propiedad afecta la apariencia del texto en el PDF.
     *
     * **Retorno:**
     * @access public
     * @return float El valor actual del espaciado entre palabras en la unidad de medida del documento.
     */

    public function getWordSpacing()
    {
        return $this->ws;
    }

    /**
     * Establece el espaciado entre palabras.
     *
     * **Descripción:**
     * Este método permite modificar el espaciado adicional que se aplicará entre las palabras al dibujar texto. Toma un valor numérico como argumento, que representa la cantidad de espacio adicional a añadir (en la unidad de medida del documento).
     *
     * Internamente, guarda este valor en la propiedad `$this->ws` y genera una instrucción en el flujo del PDF (`_out`) para aplicar este nuevo espaciado. La instrucción `'Tw'` (Word Spacing) en el PDF especifica este valor, multiplicado por el factor de escala interno `$this->k`.
     *
     * **Parámetros:**
     * @param float $ws El nuevo valor para el espaciado entre palabras (predeterminado: 0., sin espaciado adicional).
     * @access public
     */

    public function setWordSpacing($ws=0.)
    {
        $this->ws = $ws;
        $this->_out(sprintf('%.3F Tw', $ws*$this->k));
    }

    /**
     * Inicia la definición de una ruta de recorte rectangular con la posibilidad de tener esquinas redondeadas.
     *
     * **Descripción Detallada:**
     * Este método comienza la definición de una "clipping path" (ruta de recorte) en el documento PDF. Una ruta de recorte limita la región en la que se pueden dibujar elementos posteriores. Cualquier cosa dibujada fuera de esta ruta será invisible.
     *
     * La ruta definida por este método es un rectángulo, y opcionalmente se pueden especificar radios para redondear cada una de sus cuatro esquinas.
     *
     * @param float $x (top left corner)
     * @param float $y (top left corner)
     * @param float $w (x+w  = botom rigth corner)
     * @param float $h (y+h = botom rigth corner)
     * @param array $cornerTL radius of the Top Left corner
     * @param array $cornerTR radius of the Top Right corner
     * @param array $cornerBL radius of the Bottom Left corner
     * @param array $cornerBR radius of the Bottom Right corner
     * @access public
     */
    public function clippingPathStart(
        $x = null,
        $y = null,
        $w = null,
        $h = null,
        $cornerTL=null,
        $cornerTR=null,
        $cornerBL=null,
        $cornerBR=null)
    {
        // init the path
        // Inicializa la ruta
        $path = '';

        // if we have the position and the size of the rectangle, we can proceed
        // Si tenemos la posición y el tamaño del rectángulo, podemos proceder
        if ($x!==null && $y!==null && $w!==null && $h!==null) {
            // the positions of the rectangle's corners
            // Las posiciones de las esquinas del rectángulo (convertidas a la unidad interna del PDF)
            $x1 = $x*$this->k;
            $y1 = ($this->h-$y)*$this->k; // Inversión de Y debido al sistema de coordenadas de PDF

            $x2 = ($x+$w)*$this->k;
            $y2 = ($this->h-$y)*$this->k;

            $x3 = ($x+$w)*$this->k;
            $y3 = ($this->h-$y-$h)*$this->k;

            $x4 = $x*$this->k;
            $y4 = ($this->h-$y-$h)*$this->k;

            // if we have at least one radius corner, then we proceed to a specific path, else it is just a rectangle
            // Si hay al menos un radio en las esquinas, se construye una ruta específica con curvas Bézier
            if ($cornerTL || $cornerTR || $cornerBL || $cornerBR) {
                // prepare the radius values
                // Prepara los valores de los radios (convertidos a la unidad interna del PDF y ajustando el signo para Y)
                if ($cornerTL) {
                if ($cornerTL) {
                    $cornerTL[0] = $cornerTL[0]*$this->k;
                    $cornerTL[1] =-$cornerTL[1]*$this->k;
                }
                if ($cornerTR) {
                    $cornerTR[0] = $cornerTR[0]*$this->k;
                    $cornerTR[1] =-$cornerTR[1]*$this->k;
                }
                if ($cornerBL) {
                    $cornerBL[0] = $cornerBL[0]*$this->k;
                    $cornerBL[1] =-$cornerBL[1]*$this->k;
                }
                if ($cornerBR) {
                    $cornerBR[0] = $cornerBR[0]*$this->k;
                    $cornerBR[1] =-$cornerBR[1]*$this->k;
                }

                // if TL radius then specific start else (X1,Y1)
                if ($cornerTL) {
                    $path.= sprintf('%.2F %.2F m ', $x1+$cornerTL[0], $y1);
                } else {
                    $path.= sprintf('%.2F %.2F m ', $x1, $y1);
                }

                // if TR radius then line + arc, else line to (X2,Y2)
                if ($cornerTR) {
                    $xt1 = ($x2-$cornerTR[0])+$cornerTR[0]*self::MY_ARC;
                    $yt1 = ($y2+$cornerTR[1])-$cornerTR[1];
                    $xt2 = ($x2-$cornerTR[0])+$cornerTR[0];
                    $yt2 = ($y2+$cornerTR[1])-$cornerTR[1]*self::MY_ARC;

                    $path.= sprintf('%.2F %.2F l ', $x2-$cornerTR[0], $y2);
                    $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $x2, $y2+$cornerTR[1]);
                } else {
                    $path.= sprintf('%.2F %.2F l ', $x2, $y2);
                }

                // if BR radius then line + arc, else line to (X3, Y3)
                if ($cornerBR) {
                    $xt1 = ($x3-$cornerBR[0])+$cornerBR[0];
                    $yt1 = ($y3-$cornerBR[1])+$cornerBR[1]*self::MY_ARC;
                    $xt2 = ($x3-$cornerBR[0])+$cornerBR[0]*self::MY_ARC;
                    $yt2 = ($y3-$cornerBR[1])+$cornerBR[1];

                    $path.= sprintf('%.2F %.2F l ', $x3, $y3-$cornerBR[1]);
                    $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $x3-$cornerBR[0], $y3);
                } else {
                    $path.= sprintf('%.2F %.2F l ', $x3, $y3);
                }

                // if BL radius then line + arc, else line to (X4, Y4)
                if ($cornerBL) {
                    $xt1 = ($x4+$cornerBL[0])-$cornerBL[0]*self::MY_ARC;
                    $yt1 = ($y4-$cornerBL[1])+$cornerBL[1];
                    $xt2 = ($x4+$cornerBL[0])-$cornerBL[0];
                    $yt2 = ($y4-$cornerBL[1])+$cornerBL[1]*self::MY_ARC;

                    $path.= sprintf('%.2F %.2F l ', $x4+$cornerBL[0], $y4);
                    $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $x4, $y4-$cornerBL[1]);
                } else {
                    $path.= sprintf('%.2F %.2F l ', $x4, $y4);
                }

                // if RL radius then line + arc
                if ($cornerTL) {
                    $xt1 = ($x1+$cornerTL[0])-$cornerTL[0];
                    $yt1 = ($y1+$cornerTL[1])-$cornerTL[1]*self::MY_ARC;
                    $xt2 = ($x1+$cornerTL[0])-$cornerTL[0]*self::MY_ARC;
                    $yt2 = ($y1+$cornerTL[1])-$cornerTL[1];

                    $path.= sprintf('%.2F %.2F l ', $x1, $y1+$cornerTL[1]);
                    $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $x1+$cornerTL[0], $y1);
                }
            } else {
                $path.= sprintf('%.2F %.2F m ', $x1, $y1);
                $path.= sprintf('%.2F %.2F l ', $x2, $y2);
                $path.= sprintf('%.2F %.2F l ', $x3, $y3);
                $path.= sprintf('%.2F %.2F l ', $x4, $y4);
            }

            // close the path
            $path.= ' h W n';
        }

        // using the path as a clipping path
        $this->_out('q '.$path.' ');
    }

    /**
     * stop to use the Cliping Path
     *
     * @access public
     */
    public function clippingPathStop()
    {
        $this->_out(' Q');
    }

    /**
     * draw a filled corner of a border with a external and a internal radius
     *         /--------+ ext2
     *        /         |
     *       /  /-------+ int2
     *      /  /
     *      | /
     *      | |
     *      | |
     * ext1 +-+ int1        + cen
     *
     * @param float $ext1X
     * @param float $ext1Y
     * @param float $ext2X
     * @param float $ext2Y
     * @param float $int1X
     * @param float $int1Y
     * @param float $int2X
     * @param float $int2Y
     * @param float $cenX
     * @param float $cenY
     * @access public
     */
    public function drawCurve($ext1X, $ext1Y, $ext2X, $ext2Y, $int1X, $int1Y, $int2X, $int2Y, $cenX, $cenY)
    {
        // prepare the coordinates
        $ext1X = $ext1X*$this->k;
        $ext2X = $ext2X*$this->k;
        $int1X = $int1X*$this->k;
        $int2X = $int2X*$this->k;
        $cenX  = $cenX*$this->k;

        $ext1Y = ($this->h-$ext1Y)*$this->k;
        $ext2Y = ($this->h-$ext2Y)*$this->k;
        $int1Y = ($this->h-$int1Y)*$this->k;
        $int2Y = ($this->h-$int2Y)*$this->k;
        $cenY  = ($this->h-$cenY) *$this->k;

        // init the curve
        $path = '';

        if ($ext1X-$cenX!=0) {
            $xt1 = $cenX+($ext1X-$cenX);
            $yt1 = $cenY+($ext2Y-$cenY)*self::MY_ARC;
            $xt2 = $cenX+($ext1X-$cenX)*self::MY_ARC;
            $yt2 = $cenY+($ext2Y-$cenY);
        } else {
            $xt1 = $cenX+($ext2X-$cenX)*self::MY_ARC;
            $yt1 = $cenY+($ext1Y-$cenY);
            $xt2 = $cenX+($ext2X-$cenX);
            $yt2 = $cenY+($ext1Y-$cenY)*self::MY_ARC;
        }
        $path.= sprintf('%.2F %.2F m ', $ext1X, $ext1Y);
        $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $ext2X, $ext2Y);

        if ($int1X-$cenX!=0) {
            $xt1 = $cenX+($int1X-$cenX)*self::MY_ARC;
            $yt1 = $cenY+($int2Y-$cenY);
            $xt2 = $cenX+($int1X-$cenX);
            $yt2 = $cenY+($int2Y-$cenY)*self::MY_ARC;
        } else {
            $xt1 = $cenX+($int2X-$cenX);
            $yt1 = $cenY+($int1Y-$cenY)*self::MY_ARC;
            $xt2 = $cenX+($int2X-$cenX)*self::MY_ARC;
            $yt2 = $cenY+($int1Y-$cenY);
        }
        $path.= sprintf('%.2F %.2F l ', $int2X, $int2Y);
        $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $int1X, $int1Y);

        // draw the curve
        $this->_out($path . 'f');
    }

    /**
     * draw a filled corner of a border with only a external radius
     *         /--+ ext2
     *        /   |
     *       /    |
     *      /     |
     *      |     |
     *      |     |
     *      |     |
     * ext1 +-----+ int      + cen
     *
     * @param float $ext1X
     * @param float $ext1Y
     * @param float $ext2X
     * @param float $ext2Y
     * @param float $intX
     * @param float $intY
     * @param float $cenX
     * @param float $cenY
     * @access public
     */
    public function drawCorner($ext1X, $ext1Y, $ext2X, $ext2Y, $intX, $intY, $cenX, $cenY)
    {
        // prepare the coordinates
        $ext1X = $ext1X*$this->k;
        $ext2X = $ext2X*$this->k;
        $intX  = $intX*$this->k;
        $cenX  = $cenX*$this->k;

        $ext1Y = ($this->h-$ext1Y)*$this->k;
        $ext2Y = ($this->h-$ext2Y)*$this->k;
        $intY  = ($this->h-$intY)*$this->k;
        $cenY  = ($this->h-$cenY)*$this->k;

        // init the curve
        $path = '';

        if ($ext1X-$cenX!=0) {
            $xt1 = $cenX+($ext1X-$cenX);
            $yt1 = $cenY+($ext2Y-$cenY)*self::MY_ARC;
            $xt2 = $cenX+($ext1X-$cenX)*self::MY_ARC;
            $yt2 = $cenY+($ext2Y-$cenY);
        } else {
            $xt1 = $cenX+($ext2X-$cenX)*self::MY_ARC;
            $yt1 = $cenY+($ext1Y-$cenY);
            $xt2 = $cenX+($ext2X-$cenX);
            $yt2 = $cenY+($ext1Y-$cenY)*self::MY_ARC;
        }
        $path.= sprintf('%.2F %.2F m ', $ext1X, $ext1Y);
        $path.= sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $xt1, $yt1, $xt2, $yt2, $ext2X, $ext2Y);
        $path.= sprintf('%.2F %.2F l ', $intX, $intY);
        $path.= sprintf('%.2F %.2F l ', $ext1X, $ext1Y);

        // draw the curve
        $this->_out($path . 'f');
    }

    /**
     * Start a transformation
     *
     * @access public
     */
    public function startTransform()
    {
        $this->_out('q');
    }

    /**
     * Stop a transformation
     *
     * @access public
     */
    public function stopTransform()
    {
        $this->_out('Q');
    }

    /**
     * add a Translate transformation
     *
     * @param float $Tx
     * @param float $Ty
     * @access public
     */
    public function setTranslate($xT, $yT)
    {
        // Matrix for Translate
        $tm[0]=1;
        $tm[1]=0;
        $tm[2]=0;
        $tm[3]=1;
        $tm[4]=$xT*$this->k;
        $tm[5]=-$yT*$this->k;

        // apply the Transform Matric
        $this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0], $tm[1], $tm[2], $tm[3], $tm[4], $tm[5]));
    }

    /**
     * add a Rotate transformation
     *
     * @param float $angle
     * @param float $Cx
     * @param float $Cy
     * @access public
     */
    public function setRotation($angle, $xC=null, $yC=null)
    {
        // if no center, rotate around the current posiition
        if($xC === null) $xC=$this->x;
        if($yC === null) $yC=$this->y;

        // prepare the coordinate
        $yC=($this->h-$yC)*$this->k;
        $xC*=$this->k;

        // Matrix for Rotate
        $tm[0]=cos(deg2rad($angle));
        $tm[1]=sin(deg2rad($angle));
        $tm[2]=-$tm[1];
        $tm[3]=$tm[0];
        $tm[4]=$xC+$tm[1]*$yC-$tm[0]*$xC;
        $tm[5]=$yC-$tm[0]*$yC-$tm[1]*$xC;

        // apply the Transform Matric
        $this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0], $tm[1], $tm[2], $tm[3], $tm[4], $tm[5]));
    }

    /**
     * we redifine the original SetX method, because we don't want the automatic treatment.
     * It is HTML2PDF that make the treatment
     *
     * @param float   $x
     * @param boolean $rtloff NOT USED
     * @access public
     */
    public function SetX($x, $rtloff=false)
    {
        $this->x=$x;
    }

    /**
     * we redifine the original SetY method, because we don't want the automatic treatment.
     * It is HTML2PDF that make the treatment
     *
     * @param float   $y
     * @param boolean $resetx Reset the X position
     * @param boolean $rtloff NOT USED
     * @access public
     */
    public function SetY($y, $resetx=true, $rtloff=false)
    {
        if ($resetx)
            $this->x=$this->lMargin;

        $this->y=$y;
    }

    /**
     * we redifine the original SetXY method, because we don't want the automatic treatment.
     * It is HTML2PDF that make the treatment
     *
     * @param integer $x
     * @param integer $y
     * @param boolean $rtloff NOT USED
     * @access public
     */
    public function SetXY($x, $y, $rtloff=false)
    {
        $this->x=$x;
        $this->y=$y;
    }

    /**
     * multiple public accessor because HTML2PDF need to use TCPDF without being a extend of it
     *
     * @param  mixed
     * @return mixed
     * @access public
     */
    public function getK()
    {
        return $this->k;
    }
    public function getW()
    {
        return $this->w;
    }
    public function getH()
    {
        return $this->h;
    }
    public function getlMargin()
    {
        return $this->lMargin;
    }
    public function getrMargin()
    {
        return $this->rMargin;
    }
    public function gettMargin()
    {
        return $this->tMargin;
    }
    public function getbMargin()
    {
        return $this->bMargin;
    }
    public function setbMargin($v)
    {
        $this->bMargin=$v;
    }

    /**
     * SVG - Convert a SVG Style in PDF Style
     *
     * @param  array  $styles SVG Style
     * @return string PDF style
     * @access public
     */
    public function svgSetStyle($styles)
    {
        // init the PDF style
        $style = '';

        // Style : fill
        if ($styles['fill']) {
            $this->setFillColorArray($styles['fill']);
            $style.= 'F';
        }

        // Style : stroke
        if ($styles['stroke'] && $styles['stroke-width']) {
            $this->SetDrawColorArray($styles['stroke']);
            $this->SetLineWidth($styles['stroke-width']);
            $style.= 'D';
        }

        // Style : opacity
        if ($styles['fill-opacity']) {
            $this->SetAlpha($styles['fill-opacity']);
        }

        return $style;
    }

    /**
     * SVG - make a Rectangle
     *
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     * @param string $style PDF Style
     * @access public
     */
    public function svgRect($x, $y, $w, $h, $style)
    {
        // prepare the 4 corners
        $x1=$x;
        $x2=$x+$w;
        $x3=$x+$w;
        $x4=$x;

        $y1=$y;
        $y2=$y;
        $y3=$y+$h;
        $y4=$y+$h;

        // get the Closing operator from the PDF Style
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';

        // drawing
        $this->_Point($x1, $y1, true);
        $this->_Line($x2, $y2, true);
        $this->_Line($x3, $y3, true);
        $this->_Line($x4, $y4, true);
        $this->_Line($x1, $y1, true);
        $this->_out($op);
    }

    /**
     * SVG - make a Line
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @access public
     */
    public function svgLine($x1, $y1, $x2, $y2)
    {
        // get the Closing operator
        $op='S';

        // drawing
        $this->_Point($x1, $y1, true);
        $this->_Line($x2, $y2, true);
        $this->_out($op);
    }

    /**
     * SVG - make a Ellipse
     *
     * @param float  $x0 x Center
     * @param float  $y0 y Center
     * @param float  $rx x radius
     * @param float  $ry y radius
     * @param string $style PDF Style
     * @access public
     */
    public function svgEllipse($x0, $y0, $rx, $ry, $style)
    {
        // get the Closing operator from the PDF Style
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';

        // drawing
        $this->_Arc($x0, $y0, $rx, $ry, 0, 2*M_PI, true, true, true);
        $this->_out($op);
    }

    /**
     * SVG - make a Advanced Polygone
     *
     * @param array  $actions list of actions
     * @param string $style PDF Style
     * @access public
     */
    public function svgPolygone($actions, $style)
    {
        // get the Closing operator from the PDF Style
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';

        // To save the First action and the last point
        $first = array('', 0, 0);
        $last = array(0, 0, 0, 0);

        foreach ($actions as $action) {
            switch($action[0])
            {
                // Start the Path
                case 'M':
                case 'm':
                    $first = $action;
                    $x = $action[1]; $y = $action[2]; $xc = $x; $yc = $y;
                    $this->_Point($x, $y, true);
                    break;

                // Close the Path
                case 'Z':
                case 'z':
                    $x = $first[1]; $y = $first[2]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Line (new point)
                case 'L':
                    $x = $action[1]; $y = $action[2]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Line (vector from last point)
                case 'l':
                    $x = $last[0]+$action[1]; $y = $last[1]+$action[2]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Horizontal Line (new point)
                case 'H':
                    $x = $action[1]; $y = $last[1]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Horisontal Line (vector from last point)
                case 'h':
                    $x = $last[0]+$action[1]; $y = $last[1]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Vertical Line (new point)
                case 'V':
                    $x = $last[0]; $y = $action[1]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Vertical Line (vector from last point)
                case 'v':
                    $x = $last[0]; $y = $last[1]+$action[1]; $xc = $x; $yc = $y;
                    $this->_Line($x, $y, true);
                    break;

                // Make a Arc (new point)
                case 'A':
                    $rx = $action[1];   // rx
                    $ry = $action[2];   // ry
                    $a = $action[3];    // deviation angle of the axis X
                    $l = $action[4];    // large-arc-flag
                    $s = $action[5];    // sweep-flag
                    $x1 = $last[0];     // begin x
                    $y1 = $last[1];     // begin y
                    $x2 = $action[6];   // final x
                    $y2 = $action[7];   // final y

                    $this->_Arc2($x1, $y1, $x2, $y2, $rx, $ry, $a, $l, $s, true);
                    $x = $x2; $y = $y2; $xc = $x; $yc = $y;
                    break;

                // Make a Arc (vector from last point)
                case 'a':
                    $rx = $action[1];   // rx
                    $ry = $action[2];   // ry
                    $a = $action[3];    // deviation angle of the axis X
                    $l = $action[4];    // large-arc-flag
                    $s = $action[5];    // sweep-flag
                    $x1 = $last[0];     // begin x
                    $y1 = $last[1];     // begin y
                    $x2 = $last[0]+$action[6]; // final x
                    $y2 = $last[1]+$action[7]; // final y

                    $this->_Arc2($x1, $y1, $x2, $y2, $rx, $ry, $a, $l, $s, true);
                    $x = $x2; $y = $y2; $xc = $x; $yc = $y;
                    break;

                // Make a Bezier Curve (new point)
                case 'C':
                    $x1 = $action[1];
                    $y1 = $action[2];
                    $x2 = $action[3];
                    $y2 = $action[4];
                    $xf = $action[5];
                    $yf = $action[6];
                    $this->_Curve($x1, $y1, $x2, $y2, $xf, $yf, true);
                    $x = $xf; $y = $yf; $xc = $x2; $yc = $y2;
                    break;

                // Make a Bezier Curve (vector from last point)
                case 'c':
                    $x1 = $last[0]+$action[1];
                    $y1 = $last[1]+$action[2];
                    $x2 = $last[0]+$action[3];
                    $y2 = $last[1]+$action[4];
                    $xf = $last[0]+$action[5];
                    $yf = $last[1]+$action[6];
                    $this->_Curve($x1, $y1, $x2, $y2, $xf, $yf, true);
                    $x = $xf; $y = $yf; $xc = $x2; $yc = $y2;
                    break;

                // Unknown Path
                default:
                    throw new HTML2PDF_exception(0, 'SVG Path Error : ['.$action[0].'] unkown');
            }

            // save the last point
            $last = array($x, $y, $xc, $yc);
        }

        // finish the path
        $this->_out($op);
    }

    /**
     * SVG - go to a point
     *
     * @param float   $x
     * @param float   $y
     * @param boolean $trans apply transformation
     * @access protected
     */
    protected function _Point($x, $y, $trans = false)
    {
        if ($trans) $this->ptTransform($x, $y);

        $this->_out(sprintf('%.2F %.2F m', $x, $y));
    }

    /**
     * SVG - make a line from the last point to (x,y)
     *
     * @param float   $x
     * @param float   $y
     * @param boolean $trans apply transformation
     * @access protected
     */
    protected function _Line($x, $y, $trans = false)
    {
        if ($trans) $this->ptTransform($x, $y);

        $this->_out(sprintf('%.2F %.2F l', $x, $y));
    }

    /**
     * SVG - make a bezier curve from the last point to (xf,yf), with the 2 direction points (x1,y1) and (x2,y2)
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @param float $xf
     * @param float $yf
     * @param boolean $trans apply transformation
     * @access protected
     */
    protected function _Curve($x1, $y1, $x2, $y2, $xf, $yf, $trans = false)
    {
        if ($trans) {
            $this->ptTransform($x1, $y1);
            $this->ptTransform($x2, $y2);
            $this->ptTransform($xf, $yf);
        }
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1, $y1, $x2, $y2, $xf, $yf));
    }

    /**
     * SVG - make a arc with Center, Radius, from angleBegin to angleEnd
     *
     * @param float $xc
     * @param float $yc
     * @param float $rx
     * @param float $ry
     * @param float $angleBegin in radians
     * @param float $angleEng in radians
     * @param boolean $direction
     * @param boolean $drawFirst, true => add the first point
     * @param boolean $trans apply transformation
     * @access protected
     */
    protected function _Arc(
        $xc,
        $yc,
        $rx,
        $ry,
        $angleBegin,
        $angleEnd,
        $direction = true,
        $drawFirst = true,
        $trans=false)
    {
        // if we want the no trigo direction : add 2PI to the begin angle, to invert the direction
        if (!$direction) $angleBegin+= M_PI*2.;

        // cut in segment to convert in berize curv
        $dt = ($angleEnd - $angleBegin)/self::ARC_NB_SEGMENT;
        $dtm = $dt/3;

        // center of the arc
        $x0 = $xc; $y0 = $yc;

        // calculing the first point
        $t1 = $angleBegin;
        $a0 = $x0 + ($rx * cos($t1));
        $b0 = $y0 + ($ry * sin($t1));
        $c0 = -$rx * sin($t1);
        $d0 = $ry * cos($t1);

        // if drawFirst => draw the first point
        if ($drawFirst) $this->_Point($a0, $b0, $trans);

        // foreach segment
        for ($i = 1; $i <= self::ARC_NB_SEGMENT; $i++) {
            // calculing the next point
            $t1 = ($i * $dt)+$angleBegin;
            $a1 = $x0 + ($rx * cos($t1));
            $b1 = $y0 + ($ry * sin($t1));
            $c1 = -$rx * sin($t1);
            $d1 = $ry * cos($t1);

            // make the bezier curv
            $this->_Curve(
                $a0 + ($c0 * $dtm), $b0 + ($d0 * $dtm),
                $a1 - ($c1 * $dtm), $b1 - ($d1 * $dtm),
                $a1, $b1,
                $trans
            );

            // save the point
            $a0 = $a1;
            $b0 = $b1;
            $c0 = $c1;
            $d0 = $d1;
        }
    }

    /**
     * SVG - make a arc from Pt1 to Pt2, with Radius
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @param float $rx
     * @param float $ry
     * @param float $angle deviation angle of the axis X
     * @param boolean $l large-arc-flag
     * @param boolean $s sweep-flag
     * @param boolean $trans apply transformation
     * @access protected
     */
    protected function _Arc2($x1, $y1, $x2, $y2, $rx, $ry, $angle=0., $l=0, $s=0, $trans = false)
    {
        // array to stock the parameters
        $v = array();

        // the original values
        $v['x1'] = $x1;
        $v['y1'] = $y1;
        $v['x2'] = $x2;
        $v['y2'] = $y2;
        $v['rx'] = $rx;
        $v['ry'] = $ry;

        // rotate with the deviation angle of the axis X
        $v['xr1'] = $v['x1']*cos($angle) - $v['y1']*sin($angle);
        $v['yr1'] = $v['x1']*sin($angle) + $v['y1']*cos($angle);
        $v['xr2'] = $v['x2']*cos($angle) - $v['y2']*sin($angle);
        $v['yr2'] = $v['x2']*sin($angle) + $v['y2']*cos($angle);

        // the normalized vector
        $v['Xr1'] = $v['xr1']/$v['rx'];
        $v['Yr1'] = $v['yr1']/$v['ry'];
        $v['Xr2'] = $v['xr2']/$v['rx'];
        $v['Yr2'] = $v['yr2']/$v['ry'];
        $v['dXr'] = $v['Xr2']-$v['Xr1'];
        $v['dYr'] = $v['Yr2']-$v['Yr1'];
        $v['D'] = $v['dXr']*$v['dXr'] + $v['dYr']*$v['dYr'];

        // if |vector| is Null, or if |vector| > 2 : impossible to make a arc => Line
        if ($v['D']==0 || $v['D']>4) {
            $this->_Line($x2, $y2, $trans);
            return false;
        }

        // convert paramters for make a arc with Center, Radius, from angleBegin to angleEnd
        $v['s1'] = array();
        $v['s1']['t'] = sqrt((4.-$v['D'])/$v['D']);
        $v['s1']['Xr'] = ($v['Xr1']+$v['Xr2'])/2. + $v['s1']['t']*($v['Yr2']-$v['Yr1'])/2.;
        $v['s1']['Yr'] = ($v['Yr1']+$v['Yr2'])/2. + $v['s1']['t']*($v['Xr1']-$v['Xr2'])/2.;
        $v['s1']['xr'] = $v['s1']['Xr']*$v['rx'];
        $v['s1']['yr'] = $v['s1']['Yr']*$v['ry'];
        $v['s1']['x'] = $v['s1']['xr']*cos($angle)+$v['s1']['yr']*sin($angle);
        $v['s1']['y'] =-$v['s1']['xr']*sin($angle)+$v['s1']['yr']*cos($angle);
        $v['s1']['a1'] = atan2($v['y1']-$v['s1']['y'], $v['x1']-$v['s1']['x']);
        $v['s1']['a2'] = atan2($v['y2']-$v['s1']['y'], $v['x2']-$v['s1']['x']);
        if ($v['s1']['a1']>$v['s1']['a2']) $v['s1']['a1']-=2*M_PI;

        $v['s2'] = array();
        $v['s2']['t'] = -$v['s1']['t'];
        $v['s2']['Xr'] = ($v['Xr1']+$v['Xr2'])/2. + $v['s2']['t']*($v['Yr2']-$v['Yr1'])/2.;
        $v['s2']['Yr'] = ($v['Yr1']+$v['Yr2'])/2. + $v['s2']['t']*($v['Xr1']-$v['Xr2'])/2.;
        $v['s2']['xr'] = $v['s2']['Xr']*$v['rx'];
        $v['s2']['yr'] = $v['s2']['Yr']*$v['ry'];
        $v['s2']['x'] = $v['s2']['xr']*cos($angle)+$v['s2']['yr']*sin($angle);
        $v['s2']['y'] =-$v['s2']['xr']*sin($angle)+$v['s2']['yr']*cos($angle);
        $v['s2']['a1'] = atan2($v['y1']-$v['s2']['y'], $v['x1']-$v['s2']['x']);
        $v['s2']['a2'] = atan2($v['y2']-$v['s2']['y'], $v['x2']-$v['s2']['x']);
        if ($v['s2']['a1']>$v['s2']['a2']) $v['s2']['a1']-=2*M_PI;

        if (!$l) {
            if ($s) {
                $xc = $v['s2']['x'];
                $yc = $v['s2']['y'];
                $a1 = $v['s2']['a1'];
                $a2 = $v['s2']['a2'];
                $this->_Arc($xc, $yc, $rx, $ry, $a1, $a2, true, false, $trans);
            } else {
                $xc = $v['s1']['x'];
                $yc = $v['s1']['y'];
                $a1 = $v['s1']['a1'];
                $a2 = $v['s1']['a2'];
                $this->_Arc($xc, $yc, $rx, $ry, $a1, $a2, false, false, $trans);
            }
        } else {
            if ($s) {
                $xc = $v['s1']['x'];
                $yc = $v['s1']['y'];
                $a1 = $v['s1']['a1'];
                $a2 = $v['s1']['a2'];
                $this->_Arc($xc, $yc, $rx, $ry, $a1, $a2, true, false, $trans);
            } else {
                $xc = $v['s2']['x'];
                $yc = $v['s2']['y'];
                $a1 = $v['s2']['a1'];
                $a2 = $v['s2']['a2'];
                $this->_Arc($xc, $yc, $rx, $ry, $a1, $a2, false, false, $trans);
            }
        }
    }

    /**
     * SVG - transform the point (reference)
     *
     * @param float &$x
     * @param float &$y
     * @param boolean $trans true => convert into PDF unit
     * @return boolean
     * @access public
     */
    public function ptTransform(&$x,  &$y, $trans=true)
    {
        // load the last Transfomation Matrix
        $nb = count($this->_transf);
        if ($nb)    $m = $this->_transf[$nb-1];
        else        $m = array(1,0,0,1,0,0);

        // apply the Transformation Matrix
        list($x,$y) = array(($x*$m[0]+$y*$m[2]+$m[4]),($x*$m[1]+$y*$m[3]+$m[5]));

        // if true => convert into PDF unit
        if ($trans) {
            $x = $x*$this->k;
            $y = ($this->h-$y)*$this->k;
        }

        return true;
    }

    /**
     * SVG - add a transformation Matric
     *
     * @param array $n matrix
     * @access public
     */
    public function doTransform($n = null)
    {
        // get the last Transformation Matrix
        $nb = count($this->_transf);
        if ($nb)    $m = $this->_transf[$nb-1];
        else        $m = array(1,0,0,1,0,0);

        // if no transform, get the Identity Matrix
        if (!$n) $n = array(1,0,0,1,0,0);

        // create the new Transformation Matrix
        $this->_transf[] = array(
            $m[0]*$n[0]+$m[2]*$n[1],
            $m[1]*$n[0]+$m[3]*$n[1],
            $m[0]*$n[2]+$m[2]*$n[3],
            $m[1]*$n[2]+$m[3]*$n[3],
            $m[0]*$n[4]+$m[2]*$n[5]+$m[4],
            $m[1]*$n[4]+$m[3]*$n[5]+$m[5]
        );
    }

    /**
     * SVG - remove a transformation Matric
     *
     * @access public
     */
    public function undoTransform()
    {
        array_pop($this->_transf);
    }

    /**
     * Convert a HTML2PDF barcode in a TCPDF barcode
     *
     * @param string $code code to print
     * @param string $type type of barcode (see tcpdf/barcodes.php for supported formats)
     * @param int $x x position in user units
     * @param int $y y position in user units
     * @param int $w width in user units
     * @param int $h height in user units
     * @param int $labelFontsize of the Test Label. If false : no Label
     * @param array $color color of the foreground
     * @access public
     */
    public function myBarcode($code, $type, $x, $y, $w, $h, $labelFontsize, $color)
    {
        // the style of the barcode
        $style = array(
            'position' => 'S',
            'text' => ($labelFontsize ? true : false),
            'fgcolor' => $color,
            'bgcolor' => false,
        );

        // build the barcode
        $this->write1DBarcode($code, $type, $x, $y, $w, $h, '', $style, 'N');

        // it Label => add the FontSize to the height
        if ($labelFontsize) $h+= ($labelFontsize);

        // return the size of the barcode
        return array($w, $h);
    }

    /**
     * create a automatic Index on a page
     *
     * @param html2pdf $obj           parent object
     * @param string   $titre         Title of the Index Page
     * @param integer  $sizeTitle     Font size for hthe Title
     * @param integer  $sizeBookmark  Font size for the bookmarks
     * @param boolean  $bookmarkTitle Bookmark the Title
     * @param boolean  $displayPage   Display the page number for each bookmark
     * @param integer  $page draw the automatic Index on a specific Page. if null => add a page at the end
     * @param string   $fontName      FontName to use
     * @access public
     */
    public function createIndex(
        &$obj,
        $titre = 'Index',
        $sizeTitle = 20,
        $sizeBookmark = 15,
        $bookmarkTitle = true,
        $displayPage = true,
        $page = null,
        $fontName = 'helvetica')
    {
        // bookmark the Title if wanted
        if ($bookmarkTitle) $this->Bookmark($titre, 0, -1);

        // display the Title with the good Font size
        $this->SetFont($fontName, '', $sizeTitle);
        $this->Cell(0, 5, $titre, 0, 1, 'C');

        // set the good Font size for the bookmarks
        $this->SetFont($fontName, '', $sizeBookmark);
        $this->Ln(10);

        // get the number of bookmarks
        $size=sizeof($this->outlines);

        // get the size of the "P. xx" cell
        $pageCellSize=$this->GetStringWidth('p. '.$this->outlines[$size-1]['p'])+2;

        // Foreach bookmark
        for ($i=0;$i<$size;$i++) {
            // if we need a new page => add a new page
            if ($this->getY()+$this->FontSize>=($this->h - $this->bMargin)) {
                $obj->_INDEX_NewPage($page);
                $this->SetFont($fontName, '', $sizeBookmark);
            }

            // Offset of the current level
            $level=$this->outlines[$i]['l'];
            if($level>0) $this->Cell($level*8);

            // Caption (cut to fit on the width page)
            $str=$this->outlines[$i]['t'];
            $strsize=$this->GetStringWidth($str);
            $availableSize=$this->w-$this->lMargin-$this->rMargin-$pageCellSize-($level*8)-4;
            while ($strsize>=$availableSize) {
                $str=substr($str, 0, -1);
                $strsize=$this->GetStringWidth($str);
            }

            // if we want to display the page nmber
            if ($displayPage) {
                // display the Bookmark Caption
                $this->Cell($strsize+2, $this->FontSize+2, $str);

                //Filling dots
                $w=$this->w-$this->lMargin-$this->rMargin-$pageCellSize-($level*8)-($strsize+2);
                $nb=$w/$this->GetStringWidth('.');
                $dots=str_repeat('.', $nb);
                $this->Cell($w, $this->FontSize+2, $dots, 0, 0, 'R');

                //Page number
                $this->Cell($pageCellSize, $this->FontSize+2, 'p. '.$this->outlines[$i]['p'], 0, 1, 'R');
            } else {
                // display the Bookmark Caption
                $this->Cell($strsize+2, $this->FontSize+2, $str, 0, 1);
            }
        }
    }

    /**
     * Returns the string alias used for the total number of pages.
     *
     * @access public
     * @return string;
     * @see TCPDF::getAliasNbPages(), TCPDF::getPageGroupAlias()
     */
    public function getMyAliasNbPages()
    {
        if ($this->_myLastPageGroupNb==0) {
            return $this->getAliasNbPages();
        } else {
            $old = $this->currpagegroup;
            $this->currpagegroup = '{nb'.$this->_myLastPageGroupNb.'}';
            $new = $this->getPageGroupAlias();
            $this->currpagegroup = $old;

            return $new;
        }
    }

    /**
     * Returns the current page number.
     *
     * @access public
     * @param  integer $page
     * @return integer;
     */
    public function getMyNumPage($page=null)
    {
        if ($page===null) {
            $page = $this->page;
        }

        if ($this->_myLastPageGroupNb==0) {
            return $page;
        } else {
            return $page-$this->_myLastPageGroup;
        }
    }

    /**
     * Start a new group of pages
     *
     * @access public
     * @return integer;
     * @see tcpdf::startPageGroup
     */
    public function myStartPageGroup()
    {
        $this->_myLastPageGroup = $this->page-1;
        $this->_myLastPageGroupNb++;
    }

    /**
     * get $_myLastPageGroup;
     *
     * @access public
     * @return integer $_myLastPageGroup;
     */
    public function getMyLastPageGroup()
    {
        return $this->_myLastPageGroup;
    }

    /**
     * set $_myLastPageGroup;
     *
     * @access public
     * @param integer $myLastPageGroup;
     */
    public function setMyLastPageGroup($myLastPageGroup)
    {
        $this->_myLastPageGroup = $myLastPageGroup;
    }

    /**
     * get $_myLastPageGroupNb;
     *
     * @access public
     * @return integer $_myLastPageGroupNb;
     */
    public function getMyLastPageGroupNb()
    {
        return $this->_myLastPageGroupNb;
    }

    /**
     * set $_myLastPageGroupNb;
     *
     * @access public
     * @param integer $myLastPageGroupNb;
     */
    public function setMyLastPageGroupNb($myLastPageGroupNb)
    {
        $this->_myLastPageGroupNb = $myLastPageGroupNb;
    }
}
