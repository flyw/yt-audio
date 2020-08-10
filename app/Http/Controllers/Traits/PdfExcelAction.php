<?php

namespace App\Http\Controllers\Traits;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 02/04/2018
 * Time: 15:03
 */

trait PdfExcelAction
{
    protected function getPdf(Request $request , $filename = false, $baseRoute = false) {
        ($filename)?:$filename = 'pdf_'.Carbon::now()->format('Y_m_d_H_i_s');
        ($baseRoute)?:$baseRoute = preg_replace("/\/.*?$/" , '', Route::getFacadeRoot()->current()->uri);

        /** @var SnappyPdf $pdf */
        return SnappyPdf::loadView('backend.includes.partials.pdf', [
            'data'=>$this->getResult($request),
            'baseRoute' => $baseRoute,
            'startDate' =>$this->getStartDate($request)->timestamp,
            'endDate'=>$this->getEndDate($request)->timestamp,
            'rangeString'=> $this->getDateRangeString($request),
            'filename'=> $filename,
        ])
            ->setOption('footer-right', '[page]')
            ->setOrientation('landscape')
            ->stream(iconv("UTF-8","GB2312//IGNORE",$filename.'_'.$this->getDateRangeString($request).".pdf"));
    }

    public function getExcel(Request $request, $filename = false, $baseRoute = false) {

        ($filename)?:$filename = 'excel_'.Carbon::now()->format('Y_m_d_H_i_s');
        ($baseRoute)?:$baseRoute = preg_replace("/\/.*?$/" , '', Route::getFacadeRoot()->current()->uri);

        Excel::create(iconv("UTF-8","GB2312//IGNORE",$filename.'_'.$this->getDateRangeString($request)), function($excel) use ($request , $filename , $baseRoute) {
            $excel->sheet($filename, function($sheet)use ($request , $filename , $baseRoute) {
                $sheet->setAutoSize(true)
                    ->setOrientation('landscape')
                    ->loadView('backend.includes.partials.excel')
                    ->with('filename', $filename)
                    ->with('baseRoute' , $baseRoute)
                    ->with('data', $this->getResult($request , false))
                    ->with('rangeString' , $this->getDateRangeString($request))
                    ->with('startDate' , $this->getStartDate($request)->timestamp)
                    ->with('endDate' , $this->getEndDate($request)->timestamp)
                ;
            });
        })->export('xls');
    }

    private function getDates(Request $request) {
        $startDate =($request->get('startDate'))?  $request->get('startDate') :
            Carbon::now()->subDay(1)->timestamp;
        $endDate =($request->get('endDate'))?  $request->get('endDate') :
            Carbon::now()->subDay(1)->timestamp;

        $startDate = Carbon::createFromTimestamp($startDate)->setTimezone('Asia/Shanghai');
        $endDate = Carbon::createFromTimestamp($endDate)->setTimezone('Asia/Shanghai');

        return [$startDate , $endDate];
    }

    /**
     * @param Request $request
     * @return Carbon
     */
    private function getStartDate(Request $request) {
        list($startDate , $endDate) = $this->getDates($request);
        return $startDate;
    }
    /**
     * @param Request $request
     * @return Carbon
     */
    private function getEndDate(Request $request) {
        list($startDate , $endDate) = $this->getDates($request);
        return $endDate;
    }
    /**
     * @param Request $request
     * @return String
     */
    private function getDateRangeString(Request $request) {
        /**
         * @var Carbon $startDate
         * @var Carbon $endDate
         */
        list($startDate , $endDate) = $this->getDates($request);
        return ($startDate->toDateString()==$endDate->toDateString())?
            $endDate->toDateString():
            $startDate->toDateString() .' 至 ' .$endDate->toDateString();
    }
}