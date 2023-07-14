<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\models\Pegawai;

class PerawatanController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPegawaiTanpaUserinfo()
    {
        return $this->render('pegawai-tanpa-userinfo');
    }

    public function actionPegawaiTanpaCheckinout()
    {
        return $this->render('pegawai-tanpa-checkinout');
    }


    public function actionExport()
    {
        $PHPExcel = new \PHPExcel();

        $PHPExcel->setActiveSheetIndex();

        $sheet = $PHPExcel->getActiveSheet();

        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $setBorderArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama');
        $sheet->setCellValue('C3', 'NIP');
        $sheet->setCellValue('D3', 'Jumlah Userinfo');
        $sheet->setCellValue('E3', 'Unit Kerja');

        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Data KegiatanHarian');

        $PHPExcel->getActiveSheet()->mergeCells('A1:D1');

        $sheet->getStyle('A1:D3')->getFont()->setBold(true);
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 3;
        $i=1;

        $query = Pegawai::find()
            ->andWhere('status_hapus = 0')
            ->andWhere('jumlah_userinfo = 0')
            ->orderBy(['id_instansi'=>SORT_ASC]);

        foreach($query->all() as $data){
            $row++;
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $data->nama);
            $sheet->setCellValue('C' . $row, $data->nip);
            $sheet->setCellValue('D' . $row, $data->jumlah_userinfo);
            $sheet->setCellValue('E' . $row, @$data->instansi->nama);

            $i++;
        }

        $sheet->getStyle('A3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:D' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A3:D' . $row)->applyFromArray($setBorderArray);

        $path = 'exports/';
        $filename = time() . '_Export.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $objWriter->save($path.$filename);
        return $this->redirect($path.$filename);
    }
}
