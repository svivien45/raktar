<?php

require 'pdf.php';

if(isset($_POST['generatePDF'])) {
    $pdf = new PDF();
    $pdf->createPDF('D', 'lowStock.pdf');
}



