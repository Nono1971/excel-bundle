Symfony Excel bundle
============
[![Build Status](https://travis-ci.org/Nono1971/excel-bundle.svg?branch=master)](https://travis-ci.org/Nono1971/excel-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Nono1971/excel-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/excel-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Nono1971/excel-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/excel-bundle/?branch=master)
[![License](https://poser.pugx.org/onurb/excel-bundle/license)](https://packagist.org/packages/onurb/excel-bundle)
[![Latest Stable Version](https://poser.pugx.org/onurb/excel-bundle/v/stable)](https://packagist.org/packages/onurb/excel-bundle)
[![Total Downloads](https://poser.pugx.org/onurb/excel-bundle/downloads)](https://packagist.org/packages/onurb/excel-bundle)

## Installation

**1**  Add to composer.json to the `require` key

``` shell
    composer require onurb/excel-bundle
```

or manually in composer.json
```json
    ...,
    "require": {
            ...,
            "onurb/excel-bundle":     "~1.0"
        },
    ...
```

**2** Symfony 3 : Register the bundle in ``app/AppKernel.php``
``` php
    $bundles = array(
        // ...
        new \Onurb\Bundle\ExcelBundle\OnurbExcelBundle(),
    );
```

Symfony 4 : With symfony flex, bundle should be already automatically registered :
 ``` php
    // config/bundles.php
     return [
         // ...
         Onurb\Bundle\ExcelBundle\OnurbExcelBundle::class => ['all' => true],
     ];
 ```

## Usage

#### Create a spreadsheet
``` php
$spreadsheet = $this->get('phpspreadsheet')->createSpreadsheet();
```

#### Create a spreadsheet from an existing file
``` php
$spreadsheet = $this->get('phpspreadsheet')->createSpreadsheet('file.xlsx');
```

#### Create a Excel5 and write to a file given the object:

```php
$writer = $this->get('phpspreadsheet')->createWriter($spreadsheet, 'Xls');
$writer->save('file.xls');
```

#### Create a Excel 2007 and create a StreamedResponse:

```php
$writer = $this->get('phpspreadsheet')->createWriter($spreadsheet, 'Xlsx');
$response = $this->get('phpspreadsheet')->createStreamedResponse($writer);
```

#### Create a Excel file with an image:

```php
$writer = $this->get('phpspreadsheet')->createSpreadSheet();
$writer->setActiveSheetIndex(0);
$activesheet = $writer->getActiveSheet();
$drawingobject = $this->get('phpspreadsheet')->createSpreadsheetWorksheetDrawing();
$drawingobject->setPath('/path/to/image')
    ->setName('Image name')
    ->setDescription('Image description')
    ->setHeight(60)
    ->setOffsetY(20)
    ->setCoordinates('A1')
    ->setWorksheet($activesheet);
```

#### Create reader
```php
$reader = $this->get('phpspreadsheet')->createReader('Xlsx');
```

#### Supported file types
Types are case sensitive. Supported types are:
* `Xlsx`: Excel 2007
* `Xls`: Excel 5
* `Xml`: Excel 2003 XML
* `Slk`: Symbolic Link (SYLK)
* `Ods`: Libre Office (ODS)
* `Csv`: CSV
* `Html`: HTML

#### Optional libraries can be installed for writing:
* `Tcpdf`
* `Mpdf`
* `Dompdf`

to install these libraries :

```shell
composer require tecnick.com/tcpdf
composer require mpdf/mpdf
composer require dompdf/dompdf
```

#### liuggio/Excelbundle portability
For users already using liuggio/ExcelBundle wanting to migrate to phpspreadsheet, the bundle should be directly compatible :
old phpexcel file types are maintained, a compatibility factory has been added, and the phpexcel service is also redeclared.


#### More
See also the official [PhpSpreadsheet documentation](http://phpspreadsheet.readthedocs.io/).
