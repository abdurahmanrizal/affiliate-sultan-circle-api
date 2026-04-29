<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Kol;
use Illuminate\Http\Request;

class JamaahController extends Controller
{
    public function index(Request $request)
    {
        $query = Jamaah::with(['kol:id,name', 'departureSchedule:id,name']);

        // Search by nama, nik_ktp, or referral_code
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('nik_ktp', 'like', "%{$searchTerm}%")
                    ->orWhere('referral_code', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by kol_id
        if ($request->has('kol_id') && $request->kol_id) {
            $query->where('kol_id', $request->kol_id);
        }

        // Filter by having_passport
        if ($request->has('having_passport') && $request->having_passport !== null) {
            $query->where('having_passport', filter_var($request->having_passport, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by keberangkatan
        if ($request->has('keberangkatan') && $request->keberangkatan) {
            $query->where('keberangkatan', 'like', "%{$request->keberangkatan}%");
        }

        // Pagination
        $perPage = $request->get('per_page', $request->has('page_size') ? $request->page_size : 10);
        $page = $request->get('page', $request->has('page') ? $request->page : 1);

        $jamaah = $query->select([
            'id', 'nama', 'bin_binti', 'alamat_domisili', 'tempat_lahir',
            'tanggal_lahir', 'nik_ktp', 'status_perkawinan', 'pekerjaan',
            'referral_code', 'having_passport', 'status', 'kol_id', 'departure_schedule_id',
        ])->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $jamaah->items(),
            'total' => $jamaah->total(),
            'page' => $jamaah->currentPage(),
            'total_pages' => $jamaah->lastPage(),
            'page_size' => $jamaah->perPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bin_binti' => 'nullable|string|max:255',
            'alamat_domisili' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nik_ktp' => 'required|string|max:16',
            'status_perkawinan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'referral_code' => 'required|string|max:255',
            'having_passport' => 'nullable|boolean',
            'departure_schedule_id' => 'required|exists:departure_schedules,id',
        ]);

        $kol = Kol::where('referral_code', $validated['referral_code'])->first();

        $jamaah = Jamaah::create([
            'nama' => $validated['nama'],
            'departure_schedule_id' => $validated['departure_schedule_id'],
            'bin_binti' => $validated['bin_binti'] ?? null,
            'alamat_domisili' => $validated['alamat_domisili'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'nik_ktp' => $validated['nik_ktp'],
            'status_perkawinan' => $validated['status_perkawinan'],
            'pekerjaan' => $validated['pekerjaan'],
            'referral_code' => $validated['referral_code'],
            'having_passport' => $validated['having_passport'] ?? null,
            'status' => 'booking',
            'kol_id' => $kol?->id,
        ]);

        return response()->json($jamaah->load('kol'), 201);
    }

    public function updateToPaid(Request $request, $id)
    {
        $jamaah = Jamaah::findOrFail($id);

        if ($jamaah->status !== 'booking') {
            return response()->json(['message' => 'Status can only be updated from booking to paid'], 400);
        }

        $jamaah->update(['status' => 'paid']);

        return response()->json($jamaah->load('kol'));
    }
}
