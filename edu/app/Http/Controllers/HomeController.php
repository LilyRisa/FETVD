<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;
use App\Providers\ZaloApi;
use Carbon\Carbon;
use Config;

class HomeController extends Controller
{
    public function index(){
        $present = new Requestapi('/api/v1/student-list?type=present',['Authorization' => \Session::get('ACCESS_TOKEN')]);
        $absent = new Requestapi('/api/v1/student-list?type=absent',['Authorization' => \Session::get('ACCESS_TOKEN')]);
        $class = new Requestapi('/api/v1/hiface/constants',['Authorization' => \Session::get('ACCESS_TOKEN')]);
        $present = json_decode($present->methodGet());
        $absent = json_decode($absent->methodGet());
        $present = $present->count;
        $absent = $absent->count;
        $class = json_decode($class->methodGet());
        $class = $class->data->department;
        $mytime = Carbon::now()->format('d-m-yy');
        return view('index',['absent' => $absent, 'present' => $present, 'class' =>$class,'timenow' => $mytime]);
    }

    public function LoadData(){
        $respon = new Requestapi('/api/v1/student-list?type=present',['Authorization' => \Session::get('ACCESS_TOKEN')]);
        $data = json_decode($respon->methodGet());
        $student = $data->students;
        if($student != null){
            foreach($student as $key => $value){
                if($student[$key]->time_range != null){
                    $student[$key]->time_range->checkin = $student[$key]->time_range->checkin != null ? Carbon::createFromTimestamp(intval($student[$key]->time_range->checkin))->timezone('asia/ho_chi_minh')->format('H:i:s d-m-yy') : null;
                    $student[$key]->time_range->checkout = $student[$key]->time_range->checkout != null ? Carbon::createFromTimestamp(intval($student[$key]->time_range->checkout))->timezone('asia/ho_chi_minh')->format('H:i:s d-m-yy') : null;
                    $student[$key]->avatar ='data:image/jpg;base64,'.(json_decode((new Requestapi('/api/v1/get/avatar?classroom='.$student[$key]->classroom.'&name='.$student[$key]->name,['Authorization' => \Session::get('ACCESS_TOKEN')]))->methodGet()))->avatar_base64;
                }
                
            }
        }
        
        return \response()->json($data);
    }

    public function QueryData(Request $request){
        $type_student = $request->input('type');
        $class_student = $request->input('class');
        $time = explode('-',$request->input('time'));
        $time_start_now = json_encode(Carbon::create($time[0], $time[1], $time[2], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $time_end_now = json_encode(Carbon::create($time[0], $time[1], ((int)$time[2]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        if($class_student == 'all'){
            $respon = new Requestapi('/api/v1/student-list?type='.$type_student.'&start='.$time_start_now.'&end='.$time_end_now,['Authorization' => \Session::get('ACCESS_TOKEN')]);
            $data = json_decode($respon->methodGet());
        }else{
            $respon = new Requestapi('/api/v1/student-list?type='.$type_student.'&classroom='.$class_student.'&start='.$time_start_now.'&end='.$time_end_now,['Authorization' => \Session::get('ACCESS_TOKEN')]);
            $data = json_decode($respon->methodGet());
            //dd($data);
        }
        $student = $data->students;
        if($student != null){
            foreach($student as $key => $value){
                if($student[$key]->time_range != null){
                    $student[$key]->time_range->checkin = $student[$key]->time_range->checkin != null ? Carbon::createFromTimestamp(intval($student[$key]->time_range->checkin))->timezone('asia/ho_chi_minh')->format('H:i:s d-m-yy') : null;
                    $student[$key]->time_range->checkout = $student[$key]->time_range->checkout != null ? Carbon::createFromTimestamp(intval($student[$key]->time_range->checkout))->timezone('asia/ho_chi_minh')->format('H:i:s d-m-yy') : null;
                }
                $student[$key]->avatar ='data:image/jpg;base64,'.(json_decode((new Requestapi('/api/v1/get/avatar?classroom='.$student[$key]->classroom.'&name='.$student[$key]->name,['Authorization' => \Session::get('ACCESS_TOKEN')]))->methodGet()))->avatar_base64;
            }
        }
        return \response()->json($data);
        // return \response()->json($time);
    }
    public function ExcelData(Request $request){
        $type_student = $request->input('type');
        $class_student = $request->input('class');
        $time = explode('-',$request->input('time'));
        $time_start_now = json_encode(Carbon::create($time[0], $time[1], $time[2], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $time_end_now = json_encode(Carbon::create($time[0], $time[1], ((int)$time[2]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'=>"
                    Authorization: ".\Session::get('ACCESS_TOKEN')."\r\n" .
                    "Accept-language: en\r\n" .  // check function.stream-context-create on php.net
                    "User-Agent: Koala Admin \r\n"
            )
        );
        $context  = stream_context_create($opts);
        if($class_student == 'all'){
            $url = urldecode(config('app.SERVER_IP')."/api/v1/excel/student-list?type=$type_student&start=$time_start_now&end=$time_end_now");
            $data = file_get_contents($url, false, $context);
        }else{
            $url = urldecode(config('app.SERVER_IP')."/api/v1/excel/student-list?type=$type_student&classroom=$class_student&start=$time_start_now&end=$time_end_now");
            $data = file_get_contents($url, false, $context);
        }
        return Response($data)->header('Cache-Control', 'no-cache private')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-length', strlen($data))
            ->header('Content-Disposition', 'attachment; filename=Excel-reports.xlsx')
            ->header('Content-Transfer-Encoding', 'binary');
    }
    public function Zalohook(Request $request){
        $class = $request->input('class');
        $respon = new ZaloApi('/send/studentfaces');
        try{
            $data = $respon->getResponse(['name' => $class]);
            return \response()->json(['result' => true,'mess' => $respon->url]);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            return \response()->json(['result' => false]);
        }
    }
    public function SyncZl(){
        $respon = new ZaloApi('/sync/all');
        try{
            $data = $respon->getResponse([]);
            return \response()->json(['result' => true,'mess' => $respon->url]);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            return \response()->json(['result' => false]);
        }
    }
    
}
