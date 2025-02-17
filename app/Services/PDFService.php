<?php

namespace App\Services;

use FPDF;
use Carbon\Carbon;

class PDFService {
    public function createPDF($title, $nombre, $evento, $fecha, $horaInicio, $horaFin, $lugar){
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->SetFillColor(0, 102, 204); // Azul
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190, 15, $title, 0, 1, 'C', true);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 10, "Evento: $evento", 0, 1, 'C');
        $pdf->Cell(190, 10, "Asistente: $nombre", 0, 1, 'C');
        $pdf->Cell(190, 10, "Fecha: " . Carbon::parse($fecha)->format('d F Y') . "" , 0, 1, 'C');
        $pdf->Cell(190, 10, "Hora de inicio: $horaInicio", 0, 1, 'C');
        $pdf->Cell(190, 10, "Hora de fin: $horaFin", 0, 1, 'C');
        $pdf->Cell(190, 10, "Lugar: $lugar", 0, 1, 'C');
        $pdf->Ln(10);

        $pdfPath = storage_path('app/public/ticket'. $nombre .'.pdf');
        $pdf->Output($pdfPath, 'F');

        return $pdfPath;
    }
}