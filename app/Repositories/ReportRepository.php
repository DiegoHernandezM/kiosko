<?php

namespace App\Repositories;

use App\Models\Associate;
use App\Models\Task;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ReportRepository
{
    protected $mAssociate;
    protected $rTask;

    public function __construct()
    {
        $this->mAssociate = new Associate();
        $this->rTask = new AdminTaskRepository();
    }

    public function getWeekTaskExcel($save, $init = null)
    {
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'init' => $init !== null ? Carbon::parse($init)->startOfWeek()->format('Y-m-d' ) : Carbon::now()->startOfWeek()->format('Y-m-d' )
        ]);
        $associates = $this->rTask->getAll($request);
        $locations = [];
        $init = Carbon::parse($request->init)->startOfWeek()->addDay()->format('Y-m-d');
        $end = Carbon::parse($request->init)->addDays(5)->format('Y-m-d');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('B5', 'COLABORADOR');
        $sheet->setCellValue('B2', 'ACTIVIDADES CORRESPONDIENTES DE '.$init.' al '.$end);
        $sheet->mergeCells('B2:G3');
        $col = 'C';
        $rows = 6;

        for ($i = 1; $i <= 5; $i++) {
            $sheet->setCellValue($col.'5', Carbon::parse($request->init)->addDays($i)->format('Y-m-d'));
            $locations[Carbon::parse($request->init)->addDays($i)->format('Y-m-d')] = [
                'cell' => $col
            ];
            $col++;
        }

        foreach ($associates as $associate) {
            $sheet->setCellValue('B' . $rows, $associate->name.' '.$associate->lastnames);
            if (count($associate->tasks) > 0) {
                $col = 'C';
                for ($i = 0; $i <= 4; $i++) {
                    $sheet->setCellValue($col.$rows, '--');
                    $col++;
                }
                foreach ($associate->tasks as $task) {
                    if (array_key_exists($task->task_day, $locations)) {
                        $sheet->setCellValue($locations[$task->task_day]['cell'].$rows, Task::STATUS[$task->status]);
                    }
                }
            } else {
                foreach ($locations as $location) {
                    $sheet->setCellValue($location['cell'] . $rows, '--');
                }
            }
            $rows++;
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
        ];

        $last = end($locations);
        $sheet->getStyle('B5:'.$last['cell'].'5')->applyFromArray($styleArray)
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setARGB('D9D9D9D9');
        $sheet->getStyle('B5:'.$last['cell'].''.($rows-1))->applyFromArray($styleArray)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');
        $sheet->getStyle('B2:G3')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setAutoFilter("B5:".$last['cell'].''.($rows-1));
        $spreadsheet->getActiveSheet()->setTitle('Actividades semanales');
        $sheet->getColumnDimension('B')->setAutoSize(true);

        foreach ($locations as $location) {
            $sheet->getColumnDimension($location['cell'])->setAutoSize(true);
        }

        if ($save === false) {
            $response = response()->streamDownload(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            });
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment; filename="your_file.xlsx"');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response->send();
        } else {
            $fileName = uniqid();
            $writer = new Xlsx($spreadsheet);
            $writer->save(public_path('files/'.$fileName.'.xlsx'));
            return $fileName;
        }
    }
}
