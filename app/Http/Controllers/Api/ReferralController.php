<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartureSchedule;
use App\Models\Kol;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        return response()->json(
            Referral::with('kol')->latest()->get()
        );
    }

    public function trackClick($referralCode, Request $request)
    {
        $kol = Kol::where('referral_code', $referralCode)->first();

        if (! $kol) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $visitorHash = hash('sha256', $ip.'|'.$userAgent);

        $id_departure_schedule = base64_decode($request->query('departure_schedule_id'));

        $departureSchedule = DepartureSchedule::findOrFail($id_departure_schedule);

        if ($departureSchedule->status !== 'active') {
            return response()->json(['message' => 'Referral Keberangkatan Sudah Tidak Aktif'], 404);
        }

        $alreadyClicked = Referral::query()
            ->where('kol_id', $kol->id)
            ->where('departure_schedule_id', $id_departure_schedule)
            ->where('visitor_hash', $visitorHash)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        Referral::create([
            'kol_id' => $kol->id,
            'referral_code' => $kol->referral_code,
            'departure_schedule_id' => $id_departure_schedule,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'visitor_hash' => $visitorHash,
            'is_unique' => ! $alreadyClicked,
            'status' => 'clicked',
        ]);

        $kol->increment('total_click');

        return response()->json(['message' => 'ok']);
    }

    public function registerReferral(Request $request)
    {
        $kol = Kol::where('referral_code', $request->referral_code)->first();

        if (! $kol) {
            return response()->json(['message' => 'Not found'], 404);
        }

        Referral::create([
            'kol_id' => $kol->id,
            'referral_code' => $kol->referral_code,
            'user_name' => $request->user_name,
            'status' => 'registered',
        ]);

        $kol->increment('total_register');

        return response()->json(['message' => 'ok']);
    }
}
