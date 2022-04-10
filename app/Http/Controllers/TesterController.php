<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TesterController extends Controller
{
    public function index(){
        return view('tester.index');
    }
}
