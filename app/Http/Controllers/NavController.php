<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavController extends Controller
{


    public function home(){
        return view('home');
    }

    public function muestras(){
        return view('muestras');
    }

}
