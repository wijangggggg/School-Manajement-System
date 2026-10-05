<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;

class LogActivityController extends Controller
{
    public function index()
    {
        // Ambil semua log, urutkan dari yang terbaru (latest), sertakan data user (with)
        $logs = LogActivity::with('user')->oldest()->get();

        return view('logs.index', compact('logs'));
    }

}
