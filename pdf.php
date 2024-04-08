<?php

ob_start();

if(isset($_POST['generatePDF'])) {
    require('tfpdf/tfpdf.php');

    class PDF extends tFPDF
    {
    function Header()
    {
        $this->Image('logo.png',10,16,70);
        $this->SetFont('Arial','B',15);
        $this->Cell(80);
        $this->Cell(30,20,'Furniture',0,0,'C');
        $this->Ln(20);
    }

    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $pdf->Output();
}
?>