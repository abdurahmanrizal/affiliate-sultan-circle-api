<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartureSchedule;
use Illuminate\Http\Request;

class DepartureScheduleController extends Controller
{
    public function index(Request $request)
    {
        $data = DepartureSchedule::query();
        if ($request->has('all') && $request->all != 'true') {
            $data->where('status', 'active');
        }
        $data = $data->get();

        return response()->json($data, 200);
    }
}
