<?php

error_reporting(E_ERROR); 

$pdf =& new FPDI_Protection();
// set the format of the destinaton file, in our case 6×9 inch
$pdf->FPDF('P', 'in', array('6','9'));

//calculate the number of pages from the original document
$pagecount = $pdf->setSourceFile($origFile);

// copy all pages from the old unprotected pdf in the new one
for ($loop = 1; $loop <= $pagecount; $loop++) {
    $tplidx = $pdf->importPage($loop);
    $pdf->addPage();
    $pdf->useTemplate($tplidx);
}

// protect the new pdf file, and allow no printing, copy etc and leave only reading allowed
$pdf->SetProtection(array(),$password);
$pdf->Output($destFile, 'F');

return $destFile;
}

//password for the pdf file
$password = '111111';

//name of the original file (unprotected)
$origFile = 'book.pdf';

//name of the destination file (password protected and printing rights removed)
$destFile ='book_protected.pdf';

//encrypt the book and create the protected file
pdfEncrypt($origFile, $password, $destFile );
?>