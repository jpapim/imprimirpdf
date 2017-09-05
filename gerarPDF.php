<?php
/**
 * Created by PhpStorm.
 * User: EduFerr
 * Date: 23/08/2017
 * Time: 09:21
 */

require_once ('tcpdf/tcpdf.php');


class MC_TCPDF extends TCPDF {

    /**
     * Print chapter
     * @param $num (int) chapter number
     * @param $title (string) chapter title
     * @param $file (string) name of the file containing the chapter body
     * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
     * @public
     */
    public function PrintChapter($num, $title, $file, $mode=false) {
        // add a new page
        $this->AddPage();
        // disable existing columns
        $this->resetColumns();
        // print chapter title
//        $this->ChapterTitle($num, $title);
        // set columns
        $this->setEqualColumns(2, 88);
        // print chapter body
        $this->ChapterBody($file, $mode);
    }

    /**
     * Set chapter title
     * @param $num (int) chapter number
     * @param $title (string) chapter title
     * @public
     */
    public function ChapterTitle($num, $title) {
        $this->SetFont('helvetica', '', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(180, 6, 'Prova '.$num.' : '.$title, 0, 1, '', 1);
        $this->Ln(2);
    }

    /**
     * Print chapter body
     * @param $file (string) name of the file containing the chapter body
     * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
     * @public
     */
    public function ChapterBody($file, $mode=false) {
        $this->selectColumn();
        // get esternal file content
        $content = file_get_contents($file, false);
        // set font
        $this->SetFont('times', '', 9);
        $this->SetTextColor(50, 50, 50);
        // print content
        if ($mode) {
            // ------ HTML MODE ------
            $this->writeHTML($content, true, false, true, false, 'J');
        } else {
            // ------ TEXT MODE ------
            $this->Write(0, $content, '', 0, 'J', true, 0, false, true, 0);
        }
        $this->Ln();
    }
} // end of extended class

$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('EduFerr');
$pdf->SetTitle('Prova de Direito');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


$pdf ->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/bra/eng.php')) {
    require_once(dirname(__FILE__).'/bra/eng.php');
    $pdf->setLanguageArray($l);
}

// print TEXT
//$pdf->PrintChapter(1,'O PODER DA PALAVRA','TextoParaTeste.txt', false);
$pdf->PrintChapter('','','consultaProva.php', false);


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Prova_EJUR.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
