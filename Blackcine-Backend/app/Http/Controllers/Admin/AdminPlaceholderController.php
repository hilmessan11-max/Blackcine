<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPlaceholderController extends Controller
{
    public function index($page)
    {
        return view('admin.placeholder', ['title' => ucfirst(str_replace('-', ' ', $page))]);
    }
}
