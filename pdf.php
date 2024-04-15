<?php

ob_start();

require('tfpdf/tfpdf.php');
require_once 'tools.php';

    class PDF extends tFPDF
    {
    function Header()
    {
        $this->Image('logo.png',10,16,50);
        $this->SetFont('Arial','B',15);
        $this->Cell(80);
        $this->Cell(30,20,'Low Stock Products',0,0,'C');
        $this->Ln(30);
    }

    function HeaderTable()
    {
        $cellHeight = 10;
        $cellWidth = 30;

        $tableWidth = $cellWidth*3;
        $xStart = ($this->GetPageWidth() - $tableWidth) / 2;
        $yStart = $this->GetY();

        $this->SetFont('Arial', 'B', 15);
        $this->SetXY($xStart, $yStart);
        $this->cell($cellWidth, $cellHeight, 'Product Name', 1, 0, 'C');
        $this->cell($cellWidth, $cellHeight, 'Prrice', 1, 0, 'C');
        $this->cell($cellWidth, $cellHeight, 'Quantity', 1, 1, 'C');
    }

    function ViewTable($data)
    {
        $this->SetFont('Arial', '', 12);
        $fill = false;
        $i = 0;
        foreach ($data as $row) {
            $this->SetX((210-30*3)/2);
            $this->Cell(30, 10, $row['name'], 1, 0, 'C', $fill);
            $this->Cell(30, 10, $row['price'], 1, 0, 'C', $fill);
            $this->Cell(30, 10, $row['quantity'], 1, 1, 'C', $fill);

            if ($i % 2 == 0){
                $this->SetFillColor(208, 238, 255);
            } else {
                $this->SetFillColor(208, 238, 255);
            }
            $fill = !$fill;
            $i++;
        }
    }

    function createPDF($destination, $fileName){
        $dataWriter = new DataWriter();
        $lowStockProducts = $dataWriter->getLowStockProducts();

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',12);
        $pdf->SetX((210-30*3)/2);
        $pdf->HeaderTable();
        $pdf->ViewTable($lowStockProducts);
        ob_clean();
        $pdf->Output($destination, $fileName);
        }
    }

?>