<?php
/**
 * HTML2PDF Librairy - HTML2PDF Exception
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 * 
 * Maneja exdepciones para la libreria HTML PDF
 * Captura eerores especificios relacionados con la conversion de HTML a PDF y proporciona mensajes detallados sobre la causa del error
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @version   4.03
 */

class HTML2PDF_exception extends exception
{
    protected $_tag = null; //etiqueta HTML que causo el error
    protected $_html = null; //codigo HTML relacionado con el error
    protected $_other = null; //informacion adicional sonbre el error
    protected $_image = null; // fuente de imagen en caso de error de carga
    protected $_messageHtml = ''; // mensaje de error en formato html

    /**
     * generate a HTML2PDF exception
     * Constructor de la excepcion HTML2PDF
     * 
     * @param    int     $err error number - codigo de error especifico
     * @param    mixed   $other additionnal informations - informacion adicional sobre el error
     * @return   string  $html optionnal code HTML associated to the error - codigo HTML opcional asociado al error
     */
    final public function __construct($err = 0, $other = null, $html = '')
    {
        // read the error
        //determinar el mensaje de error segun el codigo recibido
        switch($err)
        {
            case 1: // Unsupported tag
                $msg = (HTML2PDF_locale::get('err01'));
                $msg = str_replace('[[OTHER]]', $other, $msg);
                $this->_tag = $other;
                break;

            case 2: // too long sentence
                $msg = (HTML2PDF_locale::get('err02'));
                $msg = str_replace('[[OTHER_0]]', $other[0], $msg);
                $msg = str_replace('[[OTHER_1]]', $other[1], $msg);
                $msg = str_replace('[[OTHER_2]]', $other[2], $msg);
                break;

            case 3: // closing tag in excess
                $msg = (HTML2PDF_locale::get('err03'));
                $msg = str_replace('[[OTHER]]', $other, $msg);
                $this->_tag = $other;
                break;

            case 4: // tags closed in the wrong order
                $msg = (HTML2PDF_locale::get('err04'));
                $msg = str_replace('[[OTHER]]', print_r($other, true), $msg);
                break;

            case 5: // unclosed tag
                $msg = (HTML2PDF_locale::get('err05'));
                $msg = str_replace('[[OTHER]]', print_r($other, true), $msg);
                break;

            case 6: // image can not be loaded
                $msg = (HTML2PDF_locale::get('err06'));
                $msg = str_replace('[[OTHER]]', $other, $msg);
                $this->_image = $other;
                break;

            case 7: // too big TD content
                $msg = (HTML2PDF_locale::get('err07'));
                break;

            case 8: // SVG tag not in DRAW tag
                $msg = (HTML2PDF_locale::get('err08'));
                $msg = str_replace('[[OTHER]]', $other, $msg);
                $this->_tag = $other;
                break;

            case 9: // deprecated
                $msg = (HTML2PDF_locale::get('err09'));
                $msg = str_replace('[[OTHER_0]]', $other[0], $msg);
                $msg = str_replace('[[OTHER_1]]', $other[1], $msg);
                $this->_tag = $other[0];
                break;

            case 0: // specific error
            default:
                $msg = $other;
                break;
        }

        // create the HTML message
        //crear mensaje de error en HTML
        $this->_messageHtml = '<span style="color: #AA0000; font-weight: bold;">'.HTML2PDF_locale::get('txt01', 'error: ').$err.'</span><br>';
        $this->_messageHtml.= HTML2PDF_locale::get('txt02', 'file:').' '.$this->file.'<br>';
        $this->_messageHtml.= HTML2PDF_locale::get('txt03', 'line:').' '.$this->line.'<br>';
        $this->_messageHtml.= '<br>';
        $this->_messageHtml.= $msg;

        // create the text message
        //crear mensaje de error en texto
        $msg = HTML2PDF_locale::get('txt01', 'error: ').$err.' : '.strip_tags($msg);

        // add the optionnal html content
        //agregar codigo HTML opcional al mensaje
        if ($html) {
            $this->_messageHtml.= "<br><br>HTML : ...".trim(htmlentities($html)).'...';
            $this->_html = $html;
            $msg.= ' HTML : ...'.trim($html).'...';
        }

        // save the other informations
        //almacenar informacion adicional
        $this->_other = $other;

        // construct the exception
        //construir la excepcion
        parent::__construct($msg, $err);
    }

    /**
     * get the message as string
     * obtiene el mensaje de error en formato HTML
     *
     * @access public
     * @return string $messageHtml - mensaje en HTML
     */
    public function __toString()
    {
        return $this->_messageHtml;
    }

    /**
     * get the html tag name
     * obtiene la etiqueta HTML asociada al error
     *
     * @access public
     * @return string $tagName
     */
    public function getTAG()
    {
        return $this->_tag;
    }

    /**
     * get the optional html code
     * obtiene el codigo HTML opcional relacionado con el error
     *
     * @access public
     * @return string $html
     */
    public function getHTML()
    {
        return $this->_html;
    }

    /**
     * get the optional other informations
     * obtiene informacion adicional sobre el error
     *
     * @access public
     * @return mixed $other
     */
    public function getOTHER()
    {
        return $this->_other;
    }

    /**
     * get the image source
     * Obtiene la fuente de la imagen en caso de error de carga
     *
     * @access public
     * @return string $imageSrc
     */
    public function getIMAGE()
    {
        return $this->_image;
    }
}