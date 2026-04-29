<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartureSchedule;
use Illuminate\Http\Request;

class DepartureScheduleController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DepartureSchedule::where('status', 'active')->get(), 200);
    }
}
