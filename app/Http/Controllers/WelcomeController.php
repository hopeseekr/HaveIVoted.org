<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counties = County::query()->pluck('name', 'id');

        return view('welcome', ['counties' => $counties]);
    }
}
