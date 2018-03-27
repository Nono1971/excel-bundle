<?php
namespace Onurb\Bundle\ExcelBundle\Factory;

class CompatibilityHelper
{
    /**
     * @var array
     */
    private $types = array(
        'CSV'           => 'Csv',
        'Excel2003XML'  => 'Xml',
        'Excel2007'     => 'Xlsx',
        'Excel5'        => 'Xls',
        'HTML'          => 'Html',
        'OOCalc'        => 'Ods',
        'OpenDocument'  => 'Ods',
        'PDF'           => 'Pdf',
        'SYLK'          => 'Slk',

    );

    /**
     * Documentation link:
     * https://phpspreadsheet.readthedocs.io/en/develop/topics/migration-from-PHPExcel/#renamed-readers-and-writers
     *
     * @param string $type
     *
     * @return string
     */
    public function convertType($type)
    {
        return isset($this->types[$type]) ? $this->types[$type] : $type;
    }
}
