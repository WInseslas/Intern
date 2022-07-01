<?php

    namespace App\Service;

    use App\Entity\Certificate;
    use setasign\Fpdi\Fpdi;

    class GenerationPdfService
    {
        private $pdf;

        public function __construct()
        {
            $this->pdf = new Fpdi();
        }

        public function geration(Certificate $certificate) : void
        {
            // positions
            $coordinates = [];
            $coordinate = explode('/', $certificate->getTemplate()->getCoordinates());
            foreach ($coordinate as $key => $value) {
                $tmp = explode(";", $value);
                $coord['width'] = $tmp[0];
                $coord['height'] = $tmp[1];
                $coordinates [] = $coord;
            }
            // data
            $start = $certificate->getPeople()->getStartdate();
            $end = $certificate->getPeople()->getEnddate(); 
            $interval = $end->diff($start);
            $month = (int) $interval->format('%m');

            if(count($coordinates) === 9){
                $data = [
                    'name' => $certificate->getPeople()->getFirstname() . " " . $certificate->getPeople()->getLastname(), 
                    'dateofbirth' => $certificate->getPeople()->getDateofbirth()->format("d-M-Y"),
                    'place' => "Greenwich",
                    'cni' => uniqid(),
                    'post' => $certificate->getPeople()->getPost(),
                    'startdate' => $certificate->getPeople()->getStartdate()->format("d-M-Y"),
                    'enddate' => $certificate->getPeople()->getEnddate()->format("d-M-Y"),
                    'createdat' => date("d-M-Y"), 
                    'coded' => $certificate->getCoded()
                ];
            } else {
                $data = [
                    'author' => $certificate->getPeople()->getFirstname() . " " . $certificate->getPeople()->getLastname(),
                    'object' => "Academique",
                    'month' => $month,
                    'topic' => $certificate->getPeople()->getTopic(),
                    'createdat' => date("d-M-Y"),
                    'coded' => $certificate->getCoded()
                ];
            }

            $keys = $this->getdate(data: $data);
            $dir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . $certificate->getTemplate()->getFile();
            $this->pdf->setSourceFile($dir);
            //$this->pdf->SetFontSize(20);
            
            $template = $this->pdf->importPage(1);
            $size = $this->pdf->getTemplateSize($template);
            $this->pdf->addPage();
            $this->pdf->useTemplate($template, 1, 1, $size['width'], $size['height'], true);

            foreach ($keys as $key =>  $value) {
                $this->pdf->AddFont('Times', '', 'times.php');
                $this->pdf->SetFont('Times', '', 18);
                if ($key === 0) {
                    $this->pdf->AddFont('Timesb', '', 'timesb.php');
                    $this->pdf->SetFont('Timesb', '', 32);
                }

                if ($value ==="coded") {
                    $this->pdf->SetFont('Times', '', 11);
                }
                //$this->pdf->Image($value.'.png', ($size['width'] + intval($coordinates[$key]['width'])), ($size['height'] + intval($coordinates[$key]['height'])), 0, 0, 'png');
                $this->pdf->Text(($size['width'] + intval($coordinates[$key]['width'])), ($size['height'] + intval($coordinates[$key]['height'])), $data[$value]);
                
            }
            $this->pdf->Output();
        }

        private function getdate($data) : array
        {
            $keys = [];
            foreach ($data as $key => $value) {
                $keys[] = $key;
            }
            return $keys;
        }
    }
    