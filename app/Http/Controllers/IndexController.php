<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;

class IndexController extends Controller
{
    public function main()
    {
        return view('welcome');
    }

    
}