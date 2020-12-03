<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Middleware\StartSession;
use App\Providers\Requestapi;
use App\Providers\SetEnv;

class AuthController extends Controller
{
    public function index(){
        if(\Session::get('ACCESS_TOKEN') != null){
            return redirect()->route('home');
        }else{
            return view('login');
        }
        
        
    }
    public function authenticate(Request $request){
        $response = new Requestapi('/api/v1/auth');
        $data = \json_decode($response->getResponse(['username'=>$request->input('username'), 'password' => $request->input('password')]));
        if(isset($data->error_code)){
            return \response()->json($data);
        }else if(isset($data->authorization)){
            \Session::put('ACCESS_TOKEN', $data->authorization);
            return \response()->json(['status' => 1]);
        }else{
            return \response()->json($data);
        }
        //dd($data);

    }

    public function logout(){
        session()->forget(['ACCESS_TOKEN']);
        return redirect()->route('login');
    }
}
