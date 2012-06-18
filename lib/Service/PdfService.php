<?php

class PdfService
{

    public function generatePdf($documentName, $modul, $component, array $options = array())
    {
        sfProjectConfiguration::getActive()->loadHelpers(array('Partial', 'Asset'));
        $content = get_partial($modul.'/'.$component, $options);
        $html = get_partial('global/layoutPdf', array('myContent' => $content));

        $noHeader = !isset($options['with_header']);
        if ($noHeader) {
            $mpdf = new mPDF('utf-8', 'A4', '', '');
        } else {
            $mpdf = new mPDF('utf-8', 'A4', '', '', 15, 15, 40);

        }

        $cssfiles = array(
            'pdf.css',
        );
        foreach ($cssfiles as $css) {
            $cssPath = stylesheet_path($css, true);
            $css = file_get_contents($cssPath);
            $mpdf->WriteHTML($css, 1);
        }

        if (!$noHeader) {
            $header = get_partial('global/pdfHeader');
            $mpdf->SetHTMLHeader($header);
        }
        $mpdf->WriteHTML($html);

        $destination = isset($options['destination']) ? $options['destination'] : 'I';
        return $mpdf->Output($documentName, $destination);
    }
}
