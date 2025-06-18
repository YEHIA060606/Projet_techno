<?php
class FPDF {
    // Ceci est une version vide/simplifiée uniquement pour éviter les erreurs.
    function AddPage() {}
    function SetFont($family, $style = '', $size = null) {}
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        echo $txt . "\n";
    }
    function Ln($h = null) {}
    function Output() {
        echo "PDF généré (simulation FPDF).";
    }
}
?>
