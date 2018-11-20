<?php

namespace ishop\libs;

class Mypdf extends \TCPDF {

    //Page header
    public function Header() {
        // Logo
        //$image_file = PATH . '/images/logo_winter_5.png';
       // $this->Image($image_file, 8, 8, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        //$this->SetFont('dejavusans', 'B', 20);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

}
