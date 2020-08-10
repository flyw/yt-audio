<?php

$pdfLinuxPath = base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
$pdfWindowsPath = base_path('vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltopdf');

if (env('PDF_PASSER' , "LINUX") == 'WIN')
    $path = $pdfWindowsPath;
else
    $path = $pdfLinuxPath;

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => $path,
        'timeout' => 200,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);