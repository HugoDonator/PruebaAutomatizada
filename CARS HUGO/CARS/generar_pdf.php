<?php
require 'db_config.php';
require('fpdf/fpdf.php');

// Get character ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de personaje no especificado');
}

$id = $_GET['id'];

// Get character data
$sql = "SELECT * FROM personajes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$personaje = $stmt->fetch();

if (!$personaje) {
    die('Personaje no encontrado');
}

// Function to get level text
function getNivelTexto($nivel) {
    $niveles = [
        1 => "Protagonista",
        2 => "Secundario",
        3 => "Recurrente",
        4 => "Invitado",
        5 => "Leyenda",
        6 => "Novato",
        7 => "Experto"
    ];
    return isset($niveles[$nivel]) ? $niveles[$nivel] : $nivel;
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Header
$pdf->SetFillColor(217, 43, 43); // Cars Red
$pdf->Rect(0, 0, 210, 20, 'F');
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 15, 'PERFIL DE RADIADOR SPRINGS', 0, 1, 'C');

// Character Name
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(217, 43, 43); // Cars Red
$pdf->Cell(0, 10, utf8_decode($personaje['nombre']), 0, 1, 'C');

// Content Area with Border
$pdf->SetDrawColor(255, 204, 0); // Yellow
$pdf->SetLineWidth(1);
$pdf->Rect(10, 40, 190, 230, 'D');

// Image Section with URL
if (!empty($personaje['foto'])) {
    // Use direct URL for image
    $imageUrl = $personaje['foto'];
    
    // Center image
    $width = 120;
    $height = 90;
    $x = (210 - $width) / 2;
    
    // Add background for image
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Rect($x - 5, 45, $width + 10, $height + 10, 'F');
    
    // Insert the image from URL
    $pdf->Image($imageUrl, $x, 50, $width, $height);
    $pdf->Ln($height + 20);
} else {
    $pdf->Ln(60);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell(0, 10, 'Sin imagen disponible', 0, 1, 'C');
}

// Character Information Section
$pdf->SetY(150);

// Info box
$pdf->SetFillColor(245, 245, 245);
$pdf->Rect(20, 150, 170, 70, 'F');

// Section title
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 150);
$pdf->Cell(170, 10, 'INFORMACION DEL PERSONAJE', 1, 1, 'C');

// Color
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(30, 165);
$pdf->Cell(40, 10, 'Color:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, utf8_decode($personaje['color']), 0, 1);

// Type
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(30, 180);
$pdf->Cell(40, 10, 'Tipo:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, utf8_decode($personaje['tipo']), 0, 1);

// Level
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(30, 195);
$pdf->Cell(40, 10, 'Nivel:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, utf8_decode(getNivelTexto($personaje['nivel']) . ' (' . $personaje['nivel'] . '/7)'), 0, 1);

// Simple level bar
$pdf->SetFillColor(217, 43, 43); // Red
$pdf->Rect(30, 210, $personaje['nivel'] * 20, 10, 'F');
$pdf->SetDrawColor(0, 0, 0);
$pdf->Rect(30, 210, 140, 10, 'D');

// Footer
$pdf->SetY(270);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 10, 'Garaje de Radiador Springs - Página ' . $pdf->PageNo(), 0, 0, 'C');

// Filename
$filename = 'Perfil_' . preg_replace('/[^A-Za-z0-9]/', '_', $personaje['nombre']) . '.pdf';

// Output PDF
$pdf->Output('D', $filename);
exit;