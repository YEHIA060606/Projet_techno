<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'fpdf/fpdf182/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Liste des tâches',0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(60,10,'Titre',1);
$pdf->Cell(80,10,'Description',1);
$pdf->Cell(40,10,'Deadline',1);
$pdf->Ln();

$stmt = $pdo->prepare("SELECT title, description, deadline FROM todos WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(60,10,$row['title'],1);
    $pdf->Cell(80,10,$row['description'],1);
    $pdf->Cell(40,10,$row['deadline'],1);
    $pdf->Ln();
}

$pdf->Output();
?>
