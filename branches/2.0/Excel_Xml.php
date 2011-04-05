<?php

/**
 * Excel/XML generator
 * 
 * @author Oliver Schwarz <oliver.schwarz@gmail.com>
 * @todo   Add MIT license
 */

/**
 * Excel/XML generating class
 * 
 * This class generates a table-like structure from
 * a multi-dimensional array. The structure may then
 * be sent to the browser or written to a file to be
 * opened in various calc/spreadsheet softwares.
 * 
 * It uses Microsoft's Open XML document format, which
 * is a valid foundation for OO as well.
 * 
 * @author Oliver Schwarz <oliver.schwarz@gmail.com>
 * @link http://blogs.msdn.com/b/brian_jones/archive/2005/11/21/495466.aspx
 */
class Excel_Xml
{
    /**
     * Microsoft Open XML header for Excel/Spreadsheets
     * @var string
     * @static
     */
    const sOpenXmlHeader = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

    /**
     * Microsoft Open XML footer for Excel/Spreadsheets
     * @var string
     * @static
     */
    const sFooter = "</Workbook>";

    /**
     * Worksheet data
     * @var array
     */
    protected $aWorksheetData;

    /**
     * Output string (Open XML)
     * @var string
     */
    protected $sOutput;

    /**
     * Encoding
     * @var string
     */
    protected $sEncoding;

    /**
     * Workbook title validation and sanitization
     * 
     * Corrects the filename of the workbook by ignoring all non-
     * allowed characters based on a whitelist regular expression.
     * 
     * @param string $filename Desired filename
     * @return string Valid filename according to whitelist rules
     */
    private function getSanitizedWorkbookTitle($filename)
    {
        return preg_replace('/[^aA-zZ0-9\_\-\.]/', '', $filename);
    }

    /**
     * Worksheet title validation and sanitization
     * 
     * Corrects the desired worksheet name, since excel only allows
     * a few characters in these titles. Uses a whitelist based
     * regexp.
     * 
     * @param string $sheettitle Desired worksheet title
     * @return string Sanitized worksheet title
     */
    private function getSanitizedWorksheetTitle($sheettitle)
    {
        $sWorksheetTitle = preg_replace('/[\\\|:|\/|\?|\*|\[|\]]', '', $sheettitle);
        return substr($sWorksheetTitle, 0, 31);
    }

    /**
     * Constructor
     * 
     * Sets up the default properties and accepts different
     * encodings to be used. However, when an encoding has
     * been set, the generator requires the input to be exactly
     * in that encoding.
     * 
     * @param string $encoding Encoding to use (default: UTF-8) [optional]
     * @return void
     */
    public function __construct($encoding = 'UTF-8')
    {
        $this->sEncoding = $encoding;
        $this->sOutput = '';
    }

    /**
     * Deconstructor
     * 
     * Resets the main object properties which eventually carry a lot of
     * data.
     * 
     * @return void
     */
    public function __destruct()
    {
        unset($this->aWorksheetData);
        unset($this->sOutput);
    }

}